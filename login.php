<?php
// Démarrer la session pour pouvoir stocker l'information de l'utilisateur connecté
session_start();
require_once 'config.php';

$error_message = '';

// Si l'utilisateur est déjà connecté, le rediriger vers la page des RDV
if (isset($_SESSION['user_id'])) {
    header('Location: rendez-vous.php');
    exit();
}

// Traitement du formulaire lors de sa soumission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['mot_de_passe'] ?? '';

    if (empty($email) || empty($password)) {
        $error_message = 'Veuillez remplir tous les champs.';
    } else {
        $stmt = $pdo->prepare("SELECT id, prenom, nom, mot_de_passe FROM professionnel WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Vérifie si l'utilisateur existe et si le mot de passe correspond
        // ATTENTION : Pour un vrai projet, utilisez password_verify($password, $user['mot_de_passe'])
        // Cela suppose que les mots de passe ont été créés avec password_hash()
        if ($user && $password === $user['mot_de_passe']) {
            // Le mot de passe est correct, on enregistre les infos en session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];
            
            // Redirection vers la page principale
            header('Location: rendez-vous.php');
            exit();
        } else {
            $error_message = 'Email ou mot de passe incorrect.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Set - Connexion</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="login-page-body">

    <div class="login-box">
        <h2>Connexion</h2>
        <form action="login.php" method="POST">
            <div class="user-box">
                <input type="email" name="email" required>
                <label>Email</label>
            </div>
            <div class="user-box">
                <input type="password" name="mot_de_passe" required>
                <label>Mot de passe</label>
            </div>

            <?php if (!empty($error_message)): ?>
                <p class="login-error"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <button type="submit">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                Se connecter
            </button>
        </form>
    </div>

</body>
</html>