<?php
// Fichier : logout.php

session_start(); // Accède à la session en cours

session_unset(); // Supprime toutes les variables de la session (comme 'user_id' et 'user_name')

session_destroy(); // Détruit complètement la session sur le serveur

// Redirige l'utilisateur vers la page de connexion
header('Location: login.php'); 
exit(); // Arrête l'exécution du script pour s'assurer que la redirection se fait immédiatement
?>