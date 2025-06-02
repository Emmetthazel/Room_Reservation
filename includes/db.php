<?php
// Informations de connexion à la base de données
$host = 'localhost'; // Adresse du serveur MySQL
$port = '3307'; // Port utilisé par le serveur MySQL
$dbname = 'reservation'; // Nom de la base de données
$username = 'root'; // Nom d'utilisateur MySQL
$password = ''; // Mot de passe MySQL (vide par défaut)

// Tentative de connexion à la base de données avec PDO
try {
    $pdo = new PDO("mysql:host=localhost;port=3307;dbname=reservation", $username, $password); // Création de la connexion
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Activation des exceptions pour les erreurs PDO
} catch (PDOException $e) {
    // En cas d'erreur, afficher un message et arrêter le script
    die("Erreur de connexion : " . $e->getMessage());
}
?>
