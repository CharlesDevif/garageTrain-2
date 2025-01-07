<?php
require_once('database/db.php');

function generateToken() {
    return bin2hex(random_bytes(32));
}

function loginWithToken($email, $password) {
    $conn = connectDB();
    

    // Préparer la requête
    $query = "SELECT id, email, password_hash FROM administrateurs WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);


    // Exécuter la requête
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérifier si un administrateur est trouvé
    if ($result->num_rows === 1) {

        $row = $result->fetch_assoc();
        $adminId = $row['id'];
        $hashedPassword = $row['password_hash'];


        // Vérifier le mot de passe
        if (password_verify($password, $hashedPassword)) {

            // Générer le token
            $token = generateToken();

            // Insérer le token dans la base de données
            $expirationDate = date('Y-m-d H:i:s', strtotime('+1 day'));
            $insertQuery = "INSERT INTO tokens (user_id, token, expiration_date) VALUES (?, ?, ?)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param("iss", $adminId, $token, $expirationDate);
            $insertStmt->execute();


            return $token;
        } else {
            echo "Mot de passe incorrect ou email incorrect.<br>";
        }
    } else {
    }

    return false;
}


function isTokenInDatabase($token) {
    $conn = connectDB();
    $query = "SELECT COUNT(*) AS token_count FROM tokens WHERE token = ? AND expiration_date > NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        return $row['token_count'] > 0;
    }
    return false;
}

function isTokenValid($token) {
    return isset($token) && !empty($token) && isTokenInDatabase($token);
}
