<?php
include 'db.php';      // Inclusion du fichier de connexion à la base de données
session_start();       // Démarrage de la session pour gérer les données utilisateur à travers les pages

function isLoggedIn() {
    return isset($_SESSION['user_id']);  // Vérifie si la session contient un identifiant utilisateur.
}

function login($email, $password) {
    global $pdo;    // Utilisation de la variable globale $pdo pour interagir avec la base de données.

    if (empty($email) || empty($password)) {
        return false;
    }

    // Prépare et exécute une requête pour récupérer l'utilisateur correspondant à l'email fourni, y compris le nom.
    $stmt = $pdo->prepare("SELECT id, nom, mot_de_passe FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Vérifie que le mot de passe haché en base correspond au mot de passe fourni et que l'utilisateur existe.
    if ($user && password_verify($password, $user['mot_de_passe'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nom'] = $user['nom']; // Store the user's name in the session
        return true;
    }
    return false;
}

function register($nom, $email, $password) {
    global $pdo;  // Utilisation de la variable globale $pdo pour interagir avec la base de données.

    // Hache le mot de passe pour le stocker en toute sécurité.
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Prépare une requête pour insérer un nouvel utilisateur dans la base de données.
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe) VALUES (?, ?, ?)");
    return $stmt->execute([$nom, $email, $hashedPassword]);
}
?>