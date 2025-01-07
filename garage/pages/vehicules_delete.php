<?php
// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['token']) || !isTokenValid($_SESSION['token'])) {
    header("Location: /login");
    exit;
}

// Connexion à la base
$conn = connectDB();

// Récupérer l'ID du véhicule
if (!isset($_GET['id'])) {
    header("Location: /dashboard");
    exit;
}

$id = (int)sanitize($_GET['id']);

// Supprimer le véhicule
$stmt = $conn->prepare("DELETE FROM vehicules WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: /dashboard");
    exit;
} else {
    echo "Erreur lors de la suppression.";
}
