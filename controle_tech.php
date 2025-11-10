<?php
require_once 'config.php'; 

$rdv = null;

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("
        SELECT 
            r.id,
            r.date_rdv,
            r.client_id,
            r.vehicule_id,
            r.garage_id,
            c.nom AS nom_client,
            c.prenom AS prenom_client,
            v.immatriculation,
            v.marque,
            v.modele
        FROM rendez_vous r
        JOIN client c ON r.client_id = c.id
        JOIN vehicule v ON r.vehicule_id = v.id
        WHERE r.id = ?
    ");
    $stmt->execute([$_GET['id']]);
    $rdv = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enregistrer'])) {
    $vehicule_id = $rdv['vehicule_id'];
    $professionnel_id = 1; 
    $garage_id = $rdv['garage_id'];
    $date_controle = $_POST['date'];
    $resultat = $_POST['resultat'];
    $observations = $_POST['observations'] ?? '';

    $stmt = $pdo->prepare("
        INSERT INTO controle_technique (vehicule_id, professionnel_id, garage_id, date_controle, resultat, observations)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$vehicule_id, $professionnel_id, $garage_id, $date_controle, $resultat, $observations]);

    $update = $pdo->prepare("UPDATE rendez_vous SET statut = 'Effectué' WHERE id = ?");
    $update->execute([$rdv['id']]);

    echo "<script>alert('Contrôle enregistré avec succès !');window.location='rendezvous.php';</script>";
    exit;
}
?>

<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Fiche de contrôle technique - Garagiste</title>
  <link rel="stylesheet" href="style_controle.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>
<body>
  <div class="container" id="pdfArea">
    <header>
      <div class="brand">Contrôle-tech</div>
      <div>
        <h1>Fiche de contrôle technique</h1>
        <p class="lead">Formulaire à remplir par le garagiste durant le contrôle</p>
      </div>
    </header>

    <form class="card" id="inspectionForm" method="POST">
      <section class="section">
        <h2>Informations générales</h2>
        <div class="grid cols-3">
          <div>
            <label for="date">Date</label>
            <input type="date" id="date" name="date"
              value="<?= isset($rdv['date_rdv']) ? date('Y-m-d', strtotime($rdv['date_rdv'])) : '' ?>" required>
          </div>
          <div>
            <label for="heure">Heure</label>
            <input type="time" id="heure" name="heure"
              value="<?= isset($rdv['date_rdv']) ? date('H:i', strtotime($rdv['date_rdv'])) : '' ?>" required>
          </div>
          <div>
            <label for="atelier">Atelier / Station</label>
            <input type="text" id="atelier" value="Auto-Set" name="atelier" placeholder="Nom de l'atelier">
          </div>
        </div>

        <div class="grid cols-3" style="margin-top:10px">
          <div>
            <label for="nomClient">Nom du client</label>
            <input type="text" id="nomClient" name="nomClient"
              value="<?= htmlspecialchars(($rdv['prenom_client'] ?? '') . ' ' . ($rdv['nom_client'] ?? '')) ?>" readonly>
          </div>
          <div>
            <label for="immatriculation">Immatriculation</label>
            <input type="text" id="immatriculation" name="immatriculation"
              value="<?= htmlspecialchars($rdv['immatriculation'] ?? '') ?>" readonly>
          </div>
          <div>
            <label for="vehicule">Véhicule</label>
            <input type="text" id="vehicule" name="vehicule"
              value="<?= htmlspecialchars(($rdv['marque'] ?? '') . ' ' . ($rdv['modele'] ?? '')) ?>" readonly>
          </div>
        </div>
      </section>

      <section class="section">
        <h2>Contrôles principaux</h2>
        <div class="checks">
          <?php
          $controles = ['Freinage', 'Éclairage & Signalisation', 'Pneumatiques', 'Direction',
                        'Structure et châssis', 'Pollution et émissions', 'Ceintures et sécurité', 'Visibilité et vitrage'];
          foreach ($controles as $ctrl): ?>
          <div class="check">
            <div class="title"><?= $ctrl ?></div>
            <div class="inline">
              <label><input type="radio" name="<?= strtolower(str_replace(' ', '_', $ctrl)) ?>" value="ok" checked> OK</label>
              <label><input type="radio" name="<?= strtolower(str_replace(' ', '_', $ctrl)) ?>" value="non"> NON</label>
            </div>
            <input type="text" name="<?= strtolower(str_replace(' ', '_', $ctrl)) ?>_com" placeholder="Remarque">
          </div>
          <?php endforeach; ?>
        </div>
      </section>

      <section class="section">
        <h2>Résultat & Signature</h2>
        <div class="row">
          <div>
            <label>Résultat global</label>
            <div class="inline">
              <label><input type="radio" name="resultat" value="OK" checked> Conforme</label>
              <label><input type="radio" name="resultat" value="Contre-visite"> Contre-visite</label>
              <label><input type="radio" name="resultat" value="Echec"> Échec</label>
            </div>
          </div>
          <div>
            <label>Observations</label>
            <textarea name="observations" placeholder="Remarques générales..."></textarea>
          </div>
        </div>
      </section>

      <div class="actions no-print">
        <button type="button" class="ghost" onclick="resetForm()">Réinitialiser</button>
        <button type="button" class="secondary" onclick="saveDraft()">Enregistrer brouillon</button>
        <button type="button" onclick="downloadPDF()">Télécharger PDF</button>
        <button type="submit" name="enregistrer">✅ Enregistrer en base</button>
      </div>
    </form>

    <footer>
      <div class="small">Fiche générée pour usage interne. Conserver la fiche signée 3 ans selon réglementation.</div>
      <div class="small">Version 1.2</div>
    </footer>
  </div>

  <script>
    const form = document.getElementById('inspectionForm');

    function saveDraft(){
      const data = new FormData(form);
      const obj = {};
      for(const [k,v] of data.entries()){ obj[k]=v; }
      localStorage.setItem('controleTechDraft', JSON.stringify(obj));
      alert('Brouillon enregistré localement');
    }

    function loadDraft(){
      const raw = localStorage.getItem('controleTechDraft');
      if(!raw) return;
      try{
        const obj = JSON.parse(raw);
        for(const k in obj){
          const el = form.elements[k];
          if(!el) continue;
          if(el.type === 'radio'){
            const radios = form.querySelectorAll(`[name="${k}"]`);
            radios.forEach(r=>{ if(r.value===obj[k]) r.checked=true});
          } else {
            el.value = obj[k];
          }
        }
      }catch(e){console.warn('impossible de charger le brouillon',e)}
    }

    function resetForm(){
      if(confirm('Réinitialiser le formulaire ?')) form.reset();
    }

    function downloadPDF(){
      const element = document.getElementById('pdfArea');
      const opt = {
        margin: 0.3,
        filename: `Controle_Technique_${new Date().toISOString().slice(0,10)}.pdf`,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
      };
      html2pdf().set(opt).from(element).save();
    }

    document.addEventListener('DOMContentLoaded', loadDraft);
  </script>
</body>
</html>
