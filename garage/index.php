<?php
require_once('database/db.php');
require_once('security/connexion.php');

session_start();            // On démarre la session tout en haut

function sanitize($input) {
    return htmlspecialchars(trim($input));
}

// Extraire la route depuis l'URL
$requestUri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];

// Définir les routes disponibles
$routes = [
    '/' => 'pages/home.php',
    '/dashboard' => 'pages/dashboard.php',
    '/login' => 'pages/login.php',
    '/logout' => 'pages/logout.php',
    '/vehicules-add' => 'pages/vehicules_add.php',
    '/vehicules-edit' => 'pages/vehicules_edit.php',
    '/vehicules-delete' => 'pages/vehicules_delete.php',

    '/rendezvous' => 'pages/rendezvous.php',

    '/test' => 'pages/test.php',
];


// Vérifier si la route existe
if (array_key_exists($requestUri, $routes)) {
    ob_start(); // Capture le contenu de la page
    require $routes[$requestUri];
    $contenu = ob_get_clean(); // Stocke le contenu
    require 'layout.php'; // Affiche le layout avec le contenu
} else {
    // Si la route n'existe pas, afficher une erreur 404
    http_response_code(404);
    $contenu = "<h1>Erreur 404</h1><p>Page non trouvée.</p>";
    require 'layout.php';
}
