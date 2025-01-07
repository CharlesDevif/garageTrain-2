<?php
require_once('database/db.php');
require_once('security/connexion.php');

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['token']) && isTokenValid($_SESSION['token'])) {
    header("Location: /dashboard");
    exit;
} else {
    header("Location: /login");
    exit;
}
?>
