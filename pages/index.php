<?php
// Inclut le fichier d'authentification pour vérifier si l'utilisateur est connecté
include '../includes/auth.php';

// Redirige vers la page de connexion si l'utilisateur n'est pas connecté
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Récupère la liste des salles depuis la base de données
global $pdo;     // Utilisation de la connexion PDO globale
$salles = $pdo->query("SELECT * FROM salles")->fetchAll(PDO::FETCH_ASSOC);
// Récupère toutes les salles sous forme de tableau associatif
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des salles</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="container">
            <h1>Liste des salles disponibles</h1>
            <nav>
                <a href="dashboard.php">Tableau de bord</a>
                <a href="logout.php">Se déconnecter</a>
            </nav>
        </div>
    </header>

    <main class="container mt-4">
        <div class="grid grid-3">
            <?php foreach ($salles as $salle): ?>
                <div class="card fade-in">
                    <h3><?= htmlspecialchars($salle['nom']) ?></h3>
                    <p class="mb-2"><?= htmlspecialchars($salle['description']) ?></p>
                    <p class="mb-3"><strong>Équipements :</strong> <?= htmlspecialchars($salle['equipements']) ?></p>
                    <a href="reservation.php?salle_id=<?= $salle['id'] ?>" class="btn btn-primary">Réserver</a>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer class="container text-center mt-4 mb-4">
        <p>&copy; <?= date('Y') ?> Système de réservation de salles</p>
    </footer>
</body>
</html>