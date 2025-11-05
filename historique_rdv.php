<?php
require_once 'config.php';
include 'header.php';

$limite_par_page = 10;

$page_actuelle = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page_actuelle < 1) {
    $page_actuelle = 1;
}
$offset = ($page_actuelle - 1) * $limite_par_page;

$base_query = "
    FROM rendez_vous r
    JOIN client c ON r.client_id = c.id
    JOIN vehicule v ON r.vehicule_id = v.id
    WHERE DATE(r.date_rdv) < CURDATE() OR r.statut IN ('Effectué', 'Annulé')
";

$stmt_total = $pdo->query("SELECT COUNT(r.id) $base_query");
$total_rdv = (int)$stmt_total->fetchColumn();
$total_pages = ceil($total_rdv / $limite_par_page);

$sql_data = "
    SELECT 
        r.date_rdv, r.statut,
        c.nom AS client_nom, c.prenom AS client_prenom,
        v.marque, v.modele, v.immatriculation
    $base_query
    ORDER BY r.date_rdv DESC
    LIMIT :limite OFFSET :offset
";

$stmt_page = $pdo->prepare($sql_data);

$stmt_page->bindParam(':limite', $limite_par_page, PDO::PARAM_INT);
$stmt_page->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt_page->execute();
$rdv_history = $stmt_page->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="rdv-page-container">
    <section id="rdv-historique">
        <h2>Historique des rendez-vous</h2>

        <div class="rdv-list">
            <?php if (empty($rdv_history)): ?>
                <p>Aucun rendez-vous trouvé dans l'historique.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Véhicule</th>
                            <th>Immat.</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rdv_history as $rdv): ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i', strtotime($rdv['date_rdv'])); ?></td>
                                <td><?php echo htmlspecialchars($rdv['client_prenom'] . ' ' . $rdv['client_nom']); ?></td>
                                <td><?php echo htmlspecialchars($rdv['marque'] . ' ' . $rdv['modele']); ?></td>
                                <td><?php echo htmlspecialchars($rdv['immatriculation']); ?></td>
                                <td><span class="statut-<?php echo strtolower($rdv['statut']); ?>"><?php echo htmlspecialchars($rdv['statut']); ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php if ($page_actuelle > 1): ?>
                    <a href="?page=<?php echo $page_actuelle - 1; ?>">&laquo; Précédent</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="<?php echo ($i == $page_actuelle) ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page_actuelle < $total_pages): ?>
                    <a href="?page=<?php echo $page_actuelle + 1; ?>">Suivant &raquo;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <br>
        <a href="rendez-vous.php" class="btn-secondary">Retour au tableau de bord</a>
        
    </section>
</div>

<?php
// (N'oubliez pas de mettre le bon nom pour votre page principale ci-dessus)
include 'footer.php';
?>