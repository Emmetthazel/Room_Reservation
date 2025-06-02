<?php
// Inclut le fichier d'authentification pour vérifier si l'utilisateur est connecté
include '../includes/auth.php';

// Redirige l'utilisateur vers la page de connexion s'il n'est pas connecté
if (!isLoggedIn()) {
    header('Location: login.php');   // Redirection
    exit;      // Stoppe l'exécution du script
}

// Récupère les réservations de l'utilisateur connecté depuis la base de données
global $pdo;    // Utilisation de la connexion PDO globale
$stmt = $pdo->prepare("
    SELECT r.*, s.nom AS salle_nom 
    FROM reservations r 
    JOIN salles s ON r.salle_id = s.id 
    WHERE r.utilisateur_id = ?
    ORDER BY r.date_reservation DESC, r.heure_debut DESC
");
$stmt->execute([$_SESSION['user_id']]);   // Exécute la requête avec l'ID utilisateur
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);   // Récupère toutes les données sous forme de tableau associatif

// Calculer les statistiques
$total_reservations = count($reservations);
$reservations_aujourdhui = 0;
$reservations_futures = 0;
$date_aujourdhui = date('Y-m-d');

foreach ($reservations as $reservation) {
    if ($reservation['date_reservation'] === $date_aujourdhui) {
        $reservations_aujourdhui++;
    }
    if ($reservation['date_reservation'] > $date_aujourdhui) {
        $reservations_futures++;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            text-align: center;
        }
        .stat-card h3 {
            color: white;
            font-size: 2rem;
            margin: 0;
        }
        .stat-card p {
            color: rgba(255, 255, 255, 0.9);
            margin: 0.5rem 0 0;
        }
        .reservation-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            margin-bottom: 1rem;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: transform 0.2s ease;
        }
        .reservation-card:hover {
            transform: translateX(5px);
        }
        .reservation-info {
            flex: 1;
        }
        .reservation-actions {
            display: flex;
            gap: 1rem;
        }
        .date-badge {
            background: var(--accent-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }
        .time-badge {
            background: var(--text-light);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-left: 0.5rem;
        }
        .quick-actions {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .quick-action-btn {
            flex: 1;
            text-align: center;
            padding: 1rem;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: all 0.3s ease;
        }
        .quick-action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .quick-action-btn i {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Tableau de bord</h1>
            <nav>
                <a href="index.php">Réserver une salle</a>
                <a href="logout.php">Se déconnecter</a>
            </nav>
        </div>
    </header>

    <main class="container mt-4">
        <div class="card mb-4">
            <h2>Bienvenue, <?= htmlspecialchars($_SESSION['user_nom'] ?? 'utilisateur') ?> !</h2>
            <p>Voici un aperçu de vos réservations et activités.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3><?= $total_reservations ?></h3>
                <p>Total des réservations</p>
            </div>
            <div class="stat-card">
                <h3><?= $reservations_aujourdhui ?></h3>
                <p>Réservations aujourd'hui</p>
            </div>
            <div class="stat-card">
                <h3><?= $reservations_futures ?></h3>
                <p>Réservations à venir</p>
            </div>
        </div>

        <div class="quick-actions">
            <a href="index.php" class="quick-action-btn">
                <i>📅</i>
                <p>Nouvelle réservation</p>
            </a>
            <a href="index.php" class="quick-action-btn">
                <i>🔍</i>
                <p>Voir les salles</p>
            </a>
            <a href="dashboard.php" class="quick-action-btn">
                <i>📊</i>
                <p>Mes statistiques</p>
            </a>
        </div>

        <h2 class="mb-3">Vos réservations récentes</h2>
        
        <?php if (empty($reservations)): ?>
            <div class="card text-center">
                <p>Vous n'avez aucune réservation pour le moment.</p>
                <a href="index.php" class="btn btn-primary mt-2">Réserver une salle</a>
            </div>
        <?php else: ?>
            <?php foreach ($reservations as $reservation): ?>
                <div class="reservation-card fade-in">
                    <div class="reservation-info">
                        <h3><?= htmlspecialchars($reservation['salle_nom']) ?></h3>
                        <div class="mb-2">
                            <span class="date-badge"><?= htmlspecialchars($reservation['date_reservation']) ?></span>
                            <span class="time-badge"><?= htmlspecialchars($reservation['heure_debut']) ?> - <?= htmlspecialchars($reservation['heure_fin']) ?></span>
                        </div>
                    </div>
                    <div class="reservation-actions">
                        <a href="index.php" class="btn btn-secondary">Voir la salle</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>

    <footer class="container text-center mt-4 mb-4">
        <p>&copy; <?= date('Y') ?> Système de réservation de salles</p>
    </footer>
</body>
</html>