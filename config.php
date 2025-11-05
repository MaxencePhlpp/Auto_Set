<?php
// --- CONFIGURATION DE LA CONNEXION À LA BASE DE DONNÉES ---
$host = '127.0.0.1:3306';
$dbname = 'auto_set';
$username = 'root'; // Votre nom d'utilisateur
$password = '';     // Votre mot de passe

try {
    // Crée une instance de PDO pour la connexion
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Configure PDO pour qu'il signale les erreurs SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // En cas d'échec de la connexion, arrête le script et affiche un message d'erreur
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}
?>