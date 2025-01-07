<?php
$title = "Ajouter un véhicule";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['token']) || !isTokenValid($_SESSION['token'])) {
    header("Location: /login");
    exit;
}

// Connexion à la base
$conn = connectDB();
$error = "";

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $marque = sanitize($_POST['marque']);
    $modele = sanitize($_POST['modele']);
    $annee = (int)$_POST['annee'];
    $plaque = sanitize($_POST['plaque']);
    $clientId = (int)$_POST['client_id'];

    if ($marque && $modele && $annee && $plaque && $clientId) {
        $stmt = $conn->prepare("INSERT INTO vehicules (marque, modele, annee, plaque, client_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisi", $marque, $modele, $annee, $plaque, $clientId);

        if ($stmt->execute()) {
            header("Location: /dashboard");
            exit;
        } else {
            $error = "Erreur lors de l'ajout du véhicule. La plaque est peut-être déjà utilisée.";
        }
    } else {
        $error = "Tous les champs sont obligatoires.";
    }
}

// Récupérer la liste des clients
$clients = $conn->query("SELECT id, nom FROM clients");
?>

<div class="container mt-5">
    <h1 class="text-center mb-4"><?= $title ?></h1>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="shadow-sm p-4 bg-white rounded">
        <div class="mb-3">
            <label for="marque" class="form-label">Marque</label>
            <input type="text" id="marque" name="marque" class="form-control" placeholder="Ex. Toyota" required>
        </div>
        <div class="mb-3">
            <label for="modele" class="form-label">Modèle</label>
            <input type="text" id="modele" name="modele" class="form-control" placeholder="Ex. Camry" required>
        </div>
        <div class="mb-3">
            <label for="annee" class="form-label">Année</label>
            <input type="number" id="annee" name="annee" class="form-control" placeholder="Ex. 2022" required>
        </div>
        <div class="mb-3">
            <label for="plaque" class="form-label">Plaque d'immatriculation</label>
            <input type="text" id="plaque" name="plaque" class="form-control" placeholder="Ex. AA-123-BB" required>
        </div>
        <div class="mb-3">
            <label for="client_id" class="form-label">Propriétaire</label>
            <select id="client_id" name="client_id" class="form-select" required>
                <option value="">Sélectionnez un client</option>
                <?php while ($client = $clients->fetch_assoc()): ?>
                    <option value="<?= $client['id'] ?>"><?= htmlspecialchars($client['nom']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary w-100">Ajouter</button>
    </form>
</div>

