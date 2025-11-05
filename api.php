<?php
header('Content-Type: application/json');

// --- CONNEXION À LA BASE DE DONNÉES ---
// À adapter avec vos informations de connexion
$host = '127.0.0.1:3306';
$dbname = 'auto_set';
$username = 'root'; // ou votre utilisateur
$password = ''; // ou votre mot de passe

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de connexion à la base de données: ' . $e->getMessage()]);
    exit();
}

// --- ROUTEUR D'ACTIONS ---
$action = $_GET['action'] ?? '';

if ($action === 'get_today_appointments') {
    getTodaysAppointments($pdo);
} elseif ($action === 'book_appointment' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    bookAppointment($pdo);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Action non valide']);
}

// --- FONCTIONS ---

function getTodaysAppointments($pdo) {
    // Sélectionne les RDV d'aujourd'hui en joignant les tables pour avoir les noms et modèles
    $stmt = $pdo->prepare("
        SELECT 
            r.date_rdv,
            c.nom AS client_nom,
            c.prenom AS client_prenom,
            v.marque,
            v.modele,
            v.immatriculation
        FROM rendez_vous r
        JOIN client c ON r.client_id = c.id
        JOIN vehicule v ON r.vehicule_id = v.id
        WHERE DATE(r.date_rdv) = CURDATE() AND r.statut = 'Prévu'
        ORDER BY r.date_rdv ASC
    ");
    $stmt->execute();
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($appointments);
}

function bookAppointment($pdo) {
    $data = $_POST;

    try {
        $pdo->beginTransaction();

        // 1. Gérer le client : le créer s'il n'existe pas
        $stmt = $pdo->prepare("SELECT id FROM client WHERE email = ?");
        $stmt->execute([$data['client_email']]);
        $client = $stmt->fetch();

        if ($client) {
            $clientId = $client['id'];
        } else {
            $stmt = $pdo->prepare("INSERT INTO client (nom, prenom, email, telephone) VALUES (?, ?, ?, ?)");
            $stmt->execute([$data['client_nom'], $data['client_prenom'], $data['client_email'], $data['client_tel']]);
            $clientId = $pdo->lastInsertId();
        }

        // 2. Gérer le véhicule : le créer s'il n'existe pas
        $stmt = $pdo->prepare("SELECT id FROM vehicule WHERE immatriculation = ?");
        $stmt->execute([$data['vehicule_immat']]);
        $vehicle = $stmt->fetch();

        if ($vehicle) {
            $vehicleId = $vehicle['id'];
        } else {
            $stmt = $pdo->prepare("INSERT INTO vehicule (client_id, marque, modele, immatriculation) VALUES (?, ?, ?, ?)");
            $stmt->execute([$clientId, $data['vehicule_marque'], $data['vehicule_modele'], $data['vehicule_immat']]);
            $vehicleId = $pdo->lastInsertId();
        }

        // 3. Insérer le rendez-vous
        $datetime_rdv = $data['rdv_date'] . ' ' . $data['rdv_heure'];
        $stmt = $pdo->prepare("INSERT INTO rendez_vous (client_id, vehicule_id, date_rdv, type_rdv, statut) VALUES (?, ?, ?, 'Controle technique', 'Prévu')");
        $stmt->execute([$clientId, $vehicleId, $datetime_rdv]);

        $pdo->commit();
        echo json_encode(['success' => 'Rendez-vous enregistré avec succès.']);

    } catch (Exception $e) {
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la création du rendez-vous: ' . $e->getMessage()]);
    }
}
?>