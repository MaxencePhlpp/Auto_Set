<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once __DIR__ . './vendor/autoload.php';

// --- 1. Configuration de Doctrine ---
$cheminsDesEntites = [__DIR__ . '/src/Entity']; // dossier contenant toutes tes entités
$modeDeDev = true; // true = développement (affiche plus de logs), false = production

$configDoctrine = Setup::createAnnotationMetadataConfiguration(
    $cheminsDesEntites,
    $modeDeDev
);

// --- 2. Paramètres de connexion à la base ---
$connexionBDD = [
    'driver'   => 'pdo_mysql',
    'host'     => '127.0.0.1',
    'port'     => 3306,
    'dbname'   => 'auto_set',
    'user'     => 'root',
    'password' => '',
    'charset'  => 'utf8mb4',
];

// --- 3. Création de l’EntityManager ---
$entityManager = EntityManager::create($connexionBDD, $configDoctrine);

return $entityManager;