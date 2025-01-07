<?php
require_once('database/db.php');
require_once('security/connexion.php');

if (!isset($_SESSION['token']) || !isTokenValid($_SESSION['token'])) {
    header("Location: /login");
    exit;
}

$title = "Tableau de Bord";

// Récupération des données
$totalClients = getTotalClients();
$totalVehicules = getTotalVehicules();
$totalRendezvous = getTotalRendezvous();
$vehicules = getVehicules();
?>

<div class="container mt-5">
    <h1 class="mb-4"><?= $title ?></h1>

    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Clients</h5>
                    <p class="card-text"><?= $totalClients ?></p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Véhicules</h5>
                    <p class="card-text"><?= $totalVehicules ?></p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Rendez-vous</h5>
                    <p class="card-text"><?= $totalRendezvous ?></p>
                </div>
            </div>
        </div>
    </div>

    <a href="/rendezvous" class="btn btn-secondary">Gérer les Rendez-vous</a>


    <h2 class="mt-5">Liste des Véhicules</h2>
    <a href="/vehicules-add" class="btn btn-primary mb-3">Ajouter un Véhicule</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Marque</th>
                <th>Modèle</th>
                <th>Année</th>
                <th>Plaque</th>
                <th>Client</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($vehicule = $vehicules->fetch_assoc()): ?>
                <tr>
                    <td><?= $vehicule['id'] ?></td>
                    <td><?= htmlspecialchars($vehicule['marque']) ?></td>
                    <td><?= htmlspecialchars($vehicule['modele']) ?></td>
                    <td><?= htmlspecialchars($vehicule['annee']) ?></td>
                    <td><?= htmlspecialchars($vehicule['plaque']) ?></td>
                    <td><?= htmlspecialchars($vehicule['client'] ?? 'Non assigné') ?></td>
                    <td>
                        <a href="/vehicules-edit?id=<?= $vehicule['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                        <a href="/vehicules-delete?id=<?= $vehicule['id'] ?>" class="btn btn-sm btn-danger">Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

