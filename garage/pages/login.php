<?php
require_once('security/connexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['mail']);
    $password = $_POST['pass'];

    $logUser = loginWithToken($email, $password);
    if ($logUser) {
        $_SESSION['token'] = $logUser;
        header("Location: /dashboard");
        exit;
    } else {
        $error = "Identifiants incorrects.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <h1>Connexion</h1>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <form method="POST">
        <label>Email :</label>
        <input type="email" name="mail" required>
        <label>Mot de passe :</label>
        <input type="password" name="pass" required>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
