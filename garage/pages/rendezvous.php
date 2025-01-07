<?php
$title = "Gestion des Rendez-vous";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['token']) || !isTokenValid($_SESSION['token'])) {
    header("Location: /login");
    exit;
}

$conn = connectDB();
$error = "";

// Ajouter un rendez-vous
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dateHeure = sanitize($_POST['date_heure']);
    $vehiculeId = (int)$_POST['vehicule_id'];
    $description = sanitize($_POST['description']);

    if ($dateHeure && $vehiculeId && $description) {
        $stmt = $conn->prepare("INSERT INTO rendezvous (date_heure, vehicule_id, description) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $dateHeure, $vehiculeId, $description);

        if ($stmt->execute()) {
            header("Location: /rendezvous");
            exit;
        } else {
            $error = "Erreur lors de l'ajout du rendez-vous.";
        }
    } else {
        $error = "Tous les champs sont obligatoires.";
    }
}

// Récupérer les rendez-vous
$rendezvous = $conn->query("
    SELECT rv.id, rv.date_heure, rv.description, v.plaque, c.nom
    FROM rendezvous rv
    JOIN vehicules v ON rv.vehicule_id = v.id
    JOIN clients c ON v.client_id = c.id
");

// Récupérer les véhicules pour le formulaire
$vehicules = $conn->query("SELECT id, plaque FROM vehicules");
?>

<h1>Gestion des Rendez-vous</h1>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<h2>Liste des Rendez-vous</h2>
<table class="table">
    <thead>
        <tr>
            <th>Date & Heure</th>
            <th>Véhicule</th>
            <th>Client</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($rv = $rendezvous->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($rv['date_heure']) ?></td>
                <td><?= htmlspecialchars($rv['plaque']) ?></td>
                <td><?= htmlspecialchars($rv['nom']) ?></td>
                <td><?= htmlspecialchars($rv['description']) ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<h2>Ajouter un Rendez-vous</h2>
<form method="POST">
    <div class="mb-3">
        <label for="date_heure" class="form-label">Date & Heure</label>
        <input type="datetime-local" id="date_heure" name="date_heure" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="vehicule_id" class="form-label">Véhicule</label>
        <select id="vehicule_id" name="vehicule_id" class="form-select" required>
            <option value="">Sélectionnez un véhicule</option>
            <?php while ($vehicule = $vehicules->fetch_assoc()): ?>
                <option value="<?= $vehicule['id'] ?>"><?= htmlspecialchars($vehicule['plaque']) ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea id="description" name="description" class="form-control" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
</form>
