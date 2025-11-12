<?php
// Connexion à la BDD
$pdo = new PDO('mysql:host=localhost;dbname=auto_set', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Récupérer clients et professionnels pour les selects
$clients = $pdo->query("SELECT id, nom, prenom FROM client ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
$pros = $pdo->query("SELECT id, nom, prenom FROM professionnel ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Agenda - Auto Set</title>
  <link rel="stylesheet" href="style_Agenda_VF.css" />
</head>
<body>
  <header>
    <img src="logo_autoset.png" alt="Logo Auto Set">
    <nav>
      <ul class="menu">
        <li><a href="#"><span class="icon">📋</span> Liste des RDV</a></li>
        <li><a href="#"><span class="icon">📅</span> Agenda</a></li>
        <li><a href="#"><span class="icon">📊</span> Résultats</a></li>
        <li><a href="#"><span class="icon">🔐</span> Connexion</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <section id="formulaire">
      <h2>Ajouter un rendez-vous</h2>
      <form id="rdvForm">
        <!-- Sélection dynamique du client -->
        <label>Nom du client :</label>
        <select id="client_id" required>
          <option value="">-- Sélectionner un client --</option>
          <?php foreach($clients as $c): ?>
            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['prenom'] . ' ' . $c['nom']) ?></option>
          <?php endforeach; ?>
        </select>

        <label>Immatriculation :</label>
        <input type="text" id="immat" required>

        <!-- Sélection dynamique du professionnel -->
        <label>Professionnel :</label>
        <select id="professionnel_id" required>
          <option value="">-- Sélectionner un professionnel --</option>
          <?php foreach($pros as $p): ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['prenom'] . ' ' . $p['nom']) ?></option>
          <?php endforeach; ?>
        </select>

        <label>Date :</label>
        <input type="date" id="date" required>

        <label>Heure de début :</label>
        <input type="time" id="start" required>

        <label>Heure de fin :</label>
        <input type="time" id="end" required>

        <!-- Champs cachés pour la BDD -->
        <input type="hidden" id="vehicule_id" value="1">
        <input type="hidden" id="garage_id" value="1">
        <input type="hidden" id="type_rdv" value="Controle technique">

        <button type="button" onclick="addRDV()">Ajouter</button>
      </form>
    </section>

    <section id="agenda">
      <div id="week-nav">
        <button id="prevWeek">← Semaine précédente</button>
        <h2 id="weekLabel"></h2>
        <button id="nextWeek">Semaine suivante →</button>
      </div>
      <div id="calendar"></div>
    </section>
  </main>

  <footer>
    <p>© 2025 Auto Set - Contrôle Technique</p>
  </footer>

  <!-- Fenêtre modale pour les détails d’un rendez-vous -->
  <div id="modal" class="modal">
    <div class="modal-content">
      <h3>Détails du rendez-vous</h3>
      <form id="editForm">
        <label>Nom du client :</label>
        <input type="text" id="editClient">

        <label>Immatriculation :</label>
        <input type="text" id="editImmat">

        <label>Professionnel :</label>
        <select id="editPro">
          <?php foreach($pros as $p): ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['prenom'] . ' ' . $p['nom']) ?></option>
          <?php endforeach; ?>
        </select>

        <label>Date :</label>
        <input type="date" id="editDate">

        <label>Heure de début :</label>
        <input type="time" id="editStart">

        <label>Heure de fin :</label>
        <input type="time" id="editEnd">

        <div class="modal-buttons">
          <button type="submit">Enregistrer</button>
          <button type="button" id="deleteRdv">Supprimer</button>
          <button type="button" id="closeModal">Fermer</button>
        </div>
      </form>
    </div>
  </div>

  <script src="script_Agenda_VF.js"></script>
</body>
</html>
