<?php
// Démarre la session pour accéder aux données de session existantes
session_start();

// Détruit toutes les données de la session en cours
session_destroy();

// Redirige l'utilisateur vers la page de connexion
header('Location: login.php');

// Arrête l'exécution du script après la redirection
exit;
?>
