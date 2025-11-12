<?php
$host = "localhost";      // ou 127.0.0.1
$dbname = "auto_set";      // remplace par le nom exact de ta base
$username = "root";       // ton utilisateur MySQL
$password = "";           // ton mot de passe MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
