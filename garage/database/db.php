<?php

function connectDB(){
    $host = 'db'; 
    $user = 'garage_user';
    $password = 'garage_password';
    $database = 'garage_vroum';
    
    // Établir la connexion
    $conn = new mysqli($host, $user, $password, $database);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }
    
    return $conn;
}

function getTotalClients() {
    $conn = connectDB();
    $result = $conn->query("SELECT COUNT(*) AS total_clients FROM clients");
    return $result->fetch_assoc()['total_clients'];
}

function getTotalVehicules() {
    $conn = connectDB();
    $result = $conn->query("SELECT COUNT(*) AS total_vehicules FROM vehicules");
    return $result->fetch_assoc()['total_vehicules'];
}

function getTotalRendezvous() {
    $conn = connectDB();
    $result = $conn->query("SELECT COUNT(*) AS total_rendezvous FROM rendezvous");
    return $result->fetch_assoc()['total_rendezvous'];
}

function getVehicules() {
    $conn = connectDB();
    return $conn->query("
        SELECT v.*, c.nom AS client 
        FROM vehicules v 
        LEFT JOIN clients c ON v.client_id = c.id
    ");
}

