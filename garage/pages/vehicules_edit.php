<?php
$title = "Modifier un Véhicule";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['token']) || !isTokenValid($_SESSION['token'])) {
    header("Location: /login");
    exit;
}

// Connexion à la base
$conn = connectDB();
$error = "";

// Récupérer l'ID du véhicule
if (!isset($_GET['id'])) {
    header("Location: /dashboard");
    exit;
}

$id = (int)sanitize($_GET['id']);
$vehicule = $conn->query("SELECT * FROM vehicules WHERE id = $id")->fetch_assoc();

if (!$vehicule) {
    $error = "Véhicule non trouvé.";
}

// Récupération des clients
$clients = $conn->query("SELECT id, nom FROM clients");

// Mise à jour du véhicule
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $marque = sanitize($_POST['marque']);
    $modele = sanitize($_POST['modele']);
    $annee = (int)$_POST['annee'];
    $plaque = sanitize($_POST['plaque']);
    $clientId = (int)$_POST['client_id'];

    if ($marque && $modele && $annee && $plaque && $clientId) {
        $stmt = $conn->prepare("UPDATE vehicules SET marque = ?, modele = ?, annee = ?, plaque = ?, client_id = ? WHERE id = ?");
        $stmt->bind_param("ssisii", $marque, $modele, $annee, $plaque, $clientId, $id);

        if ($stmt->execute()) {
            header("Location: /dashboard");
            exit;
        } else {
            $error = "Erreur lors de la mise à jour du véhicule.";
        }
    } else {
        $error = "Tous les champs sont obligatoires.";
    }
}
?>

<h1>Modifier le Véhicule</h1>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="POST">
    <div class="mb-3">
        <label for="marque" class="form-label">Marque</label>
        <input type="text" id="marque" name="marque" value="<?= htmlspecialchars($vehicule['marque']) ?>" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="modele" class="form-label">Modèle</label>
        <input type="text" id="modele" name="modele" value="<?= htmlspecialchars($vehicule['modele']) ?>" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="annee" class="form-label">Année</label>
        <input type="number" id="annee" name="annee" value="<?= htmlspecialchars($vehicule['annee']) ?>" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="plaque" class="form-label">Plaque d'immatriculation</label>
        <input type="text" id="plaque" name="plaque" value="<?= htmlspecialchars($vehicule['plaque']) ?>" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="client_id" class="form-label">Propriétaire</label>
        <select id="client_id" name="client_id" class="form-select" required>
            <?php while ($client = $clients->fetch_assoc()): ?>
                <option value="<?= $client['id'] ?>" <?= $client['id'] == $vehicule['client_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($client['nom']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
</form>
