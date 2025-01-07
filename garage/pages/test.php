<?php
require_once('database/db.php');
require_once('security/connexion.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['token']) || !isTokenValid($_SESSION['token'])) {
    echo "<p class='alert alert-danger'>Vous n'êtes pas connecté. Connectez-vous pour tester les fonctionnalités.</p>";
    exit;
}

// Connexion à la base de données
$conn = connectDB();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tests Fonctionnalités - Garage Train</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Page de Test des Fonctionnalités</h1>
        <hr>

        <!-- Test de connexion utilisateur -->
        <h2>Test Connexion</h2>
        <?php if (isset($_SESSION['token']) && isTokenValid($_SESSION['token'])): ?>
            <p class="alert alert-success">La session est valide. L'utilisateur est connecté.</p>
        <?php else: ?>
            <p class="alert alert-danger">Problème de connexion. Veuillez vérifier votre login ou token.</p>
        <?php endif; ?>

        <!-- Test récupération des données -->
        <h2>Test Récupération des Données</h2>
        <?php
        try {
            $totalClients = getTotalClients();
            $totalVehicules = getTotalVehicules();
            $totalRendezvous = getTotalRendezvous();
            echo "<p class='alert alert-success'>Clients : $totalClients, Véhicules : $totalVehicules, Rendez-vous : $totalRendezvous</p>";
        } catch (Exception $e) {
            echo "<p class='alert alert-danger'>Erreur lors de la récupération des données : {$e->getMessage()}</p>";
        }
        ?>

        <!-- Test ajout d'un véhicule -->
        <h2>Test Ajout d'un Véhicule</h2>
        <?php
        try {
            $stmt = $conn->prepare("INSERT INTO vehicules (marque, modele, annee, plaque, client_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssisi", $marque, $modele, $annee, $plaque, $clientId);
            $marque = "Test Marque";
            $modele = "Test Modèle";
            $annee = 2025;
            $plaque = "TEST-123";
            $clientId = 1;
            if ($stmt->execute()) {
                echo "<p class='alert alert-success'>Véhicule ajouté avec succès.</p>";
            } else {
                echo "<p class='alert alert-danger'>Erreur lors de l'ajout d'un véhicule : {$stmt->error}</p>";
            }
        } catch (Exception $e) {
            echo "<p class='alert alert-danger'>Erreur lors de l'ajout d'un véhicule : {$e->getMessage()}</p>";
        }
        ?>

        <!-- Test suppression d'un véhicule -->
        <h2>Test Suppression d'un Véhicule</h2>
        <?php
        try {
            $stmt = $conn->prepare("DELETE FROM vehicules WHERE plaque = ?");
            $stmt->bind_param("s", $plaque);
            $plaque = "TEST-123";
            if ($stmt->execute()) {
                echo "<p class='alert alert-success'>Véhicule supprimé avec succès.</p>";
            } else {
                echo "<p class='alert alert-danger'>Erreur lors de la suppression d'un véhicule : {$stmt->error}</p>";
            }
        } catch (Exception $e) {
            echo "<p class='alert alert-danger'>Erreur lors de la suppression d'un véhicule : {$e->getMessage()}</p>";
        }
        ?>

        <!-- Test modification d'un véhicule -->
        <h2>Test Modification d'un Véhicule</h2>
        <?php
        try {
            $stmt = $conn->prepare("UPDATE vehicules SET marque = ? WHERE id = ?");
            $stmt->bind_param("si", $newMarque, $vehiculeId);
            $newMarque = "Modifié Marque";
            $vehiculeId = 1;
            if ($stmt->execute()) {
                echo "<p class='alert alert-success'>Véhicule modifié avec succès.</p>";
            } else {
                echo "<p class='alert alert-danger'>Erreur lors de la modification d'un véhicule : {$stmt->error}</p>";
            }
        } catch (Exception $e) {
            echo "<p class='alert alert-danger'>Erreur lors de la modification d'un véhicule : {$e->getMessage()}</p>";
        }
        ?>

        <!-- Test rendez-vous -->
        <h2>Test Récupération des Rendez-vous</h2>
        <?php
        try {
            $result = $conn->query("SELECT * FROM rendezvous");
            if ($result->num_rows > 0) {
                echo "<p class='alert alert-success'>Rendez-vous récupérés avec succès. Nombre : {$result->num_rows}</p>";
            } else {
                echo "<p class='alert alert-warning'>Aucun rendez-vous trouvé.</p>";
            }
        } catch (Exception $e) {
            echo "<p class='alert alert-danger'>Erreur lors de la récupération des rendez-vous : {$e->getMessage()}</p>";
        }
        ?>
    </div>
</body>
</html>
