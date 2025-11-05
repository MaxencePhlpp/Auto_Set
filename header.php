<?php

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Set - Contrôle Technique</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-content">
            
            <h1>
                <a href="rendez-vous.php">
                    <img src="logo_autoset.png" alt="AutoSet Logo" id="logo">
                </a>
            </h1>
            <div class="user-info">
                <?php if (isset($_SESSION['user_name'])): ?>
                    <span>Connecté : <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <?php endif; ?>
            </div>
        </div>
        <nav>
            <ul>
                <li><a href="rendez-vous.php">Liste des RDV</a></li>
                <li><a href="agenda.html">Prendre un RDV</a></li>
                <li><a href="logout.php" class="logout-button">Se déconnecter</a></li>
            </ul>
        </nav>
    </header>
    <main>