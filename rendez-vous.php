<?php
require_once 'config.php';
include 'header.php';

$base_query = "
    SELECT 
        r.id,
        r.date_rdv,
        r.statut,
        c.nom AS client_nom,
        c.prenom AS client_prenom,
        v.marque,
        v.modele,
        v.immatriculation
    FROM rendez_vous r
    JOIN client c ON r.client_id = c.id
    JOIN vehicule v ON r.vehicule_id = v.id
";

$stmt_today = $pdo->prepare("$base_query WHERE DATE(r.date_rdv) = CURDATE() ORDER BY r.date_rdv ASC");
$stmt_today->execute();
$rdv_today = $stmt_today->fetchAll(PDO::FETCH_ASSOC);

$stmt_future = $pdo->prepare("$base_query WHERE DATE(r.date_rdv) > CURDATE() AND r.statut = 'Prévu' ORDER BY r.date_rdv ASC");
$stmt_future->execute();
$rdv_future = $stmt_future->fetchAll(PDO::FETCH_ASSOC);

$stmt_past = $pdo->prepare("$base_query WHERE DATE(r.date_rdv) < CURDATE() OR r.statut IN ('Effectué', 'Annulé') ORDER BY r.date_rdv DESC LIMIT 10");
$stmt_past->execute();
$rdv_past = $stmt_past->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="rdv-page-container">

    <section id="rdv-jour">
        <h2>Rendez-vous du jour</h2>
        <div class="rdv-list">
            <?php if (empty($rdv_today)): ?>
                <p>Aucun rendez-vous prévu pour aujourd'hui.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Heure</th>
                            <th>Client</th>
                            <th>Véhicule</th>
                            <th>Immat.</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rdv_today as $rdv): ?>
                            <tr>
                                <td><?php echo date('H:i', strtotime($rdv['date_rdv'])); ?></td>
                                <td><?php echo htmlspecialchars($rdv['client_prenom'] . ' ' . $rdv['client_nom']); ?></td>
                                <td><?php echo htmlspecialchars($rdv['marque'] . ' ' . $rdv['modele']); ?></td>
                                <td><?php echo htmlspecialchars($rdv['immatriculation']); ?></td>
                                <td><span class="statut-<?php echo strtolower($rdv['statut']); ?>"><?php echo htmlspecialchars($rdv['statut']); ?></span></td>
                                <td>
                                    <a href="controle.php?id=<?php echo $rdv['id']; ?>" class="btn">Contrôle technique</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </section>

    <section id="rdv-avenir">
        <h2>Rendez-vous à venir</h2>
        <div class="rdv-list">
            <?php if (empty($rdv_future)): ?>
                <p>Aucun rendez-vous à venir.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Véhicule</th>
                            <th>Immat.</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rdv_future as $rdv): ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i', strtotime($rdv['date_rdv'])); ?></td>
                                <td><?php echo htmlspecialchars($rdv['client_prenom'] . ' ' . $rdv['client_nom']); ?></td>
                                <td><?php echo htmlspecialchars($rdv['marque'] . ' ' . $rdv['modele']); ?></td>
                                <td><?php echo htmlspecialchars($rdv['immatriculation']); ?></td>
                                <td>
                                    <a href="controle.php?id=<?php echo $rdv['id']; ?>" class="btn">Contrôle technique</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </section>

    <section id="rdv-passes">
        <h2>Rendez-vous passés</h2>
        <div class="rdv-list">
             <?php if (empty($rdv_past)): ?>
                <p>Aucun rendez-vous passé.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Véhicule</th>
                            <th>Immat.</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rdv_past as $rdv): ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i', strtotime($rdv['date_rdv'])); ?></td>
                                <td><?php echo htmlspecialchars($rdv['client_prenom'] . ' ' . $rdv['client_nom']); ?></td>
                                <td><?php echo htmlspecialchars($rdv['marque'] . ' ' . $rdv['modele']); ?></td>
                                <td><?php echo htmlspecialchars($rdv['immatriculation']); ?></td>
                                <td><span class="statut-<?php echo strtolower($rdv['statut']); ?>"><?php echo htmlspecialchars($rdv['statut']); ?></span></td>
                                <td>
                                    <a href="controle.php?id=<?php echo $rdv['id']; ?>" class="btn">Contrôle technique</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="pagination-link">
                    <a href="historique_rdv.php" class="btn">Voir tout l'historique</a>
                </div>
            <?php endif; ?>
        </div>
    </section>

</div>

<?php
include 'footer.php';
?>
