<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=auto_set', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $client_id = $_POST['client_id'] ?? null;
    $vehicule_id = $_POST['vehicule_id'] ?? 1; // pour test
    $professionnel_id = $_POST['professionnel_id'] ?? null;
    $garage_id = $_POST['garage_id'] ?? 1;
    $date = $_POST['date_rdv'] ?? null;
    $start = $_POST['start'] ?? null;
    $end = $_POST['end'] ?? null;
    $type_rdv = $_POST['type_rdv'] ?? 'Controle technique';

    if (!$client_id || !$professionnel_id || !$date || !$start || !$end) {
        echo json_encode(['status' => 'error', 'message' => 'missing_data']);
        exit;
    }

    // Combine date et heure
    $date_debut = date('Y-m-d H:i:s', strtotime("$date $start"));
    $date_fin   = date('Y-m-d H:i:s', strtotime("$date $end"));

    $stmt = $pdo->prepare("INSERT INTO rendez_vous 
        (client_id, vehicule_id, professionnel_id, garage_id, date_rdv, type_rdv) 
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$client_id, $vehicule_id, $professionnel_id, $garage_id, $date_debut, $type_rdv]);

    echo json_encode(['status' => 'success']);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
