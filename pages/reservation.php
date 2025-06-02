<?php
// Inclut le fichier d'authentification pour vérifier si l'utilisateur est connecté
include '../includes/auth.php';

// Vérifie si l'utilisateur est connecté, sinon redirige vers la page de connexion
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Récupère l'ID de la salle depuis la requête GET
$salle_id = $_GET['salle_id'];

// Récupère les informations de la salle correspondante
$salle = $pdo->prepare("SELECT * FROM salles WHERE id = ?");
$salle->execute([$salle_id]);
$salle = $salle->fetch(PDO::FETCH_ASSOC);

// Vérifie si la salle existe, sinon affiche un message d'erreur
if (!$salle) {
    die("Salle introuvable.");
}

// Gestion du formulaire de réservation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date_reservation = $_POST['date_reservation'];  // Date de réservation saisie par l'utilisateur
    $heure_debut = $_POST['heure_debut'];  // Heure de début saisie par l'utilisateur
    $heure_fin = $_POST['heure_fin'];   // Heure de fin saisie par l'utilisateur

    // Vérifie s'il y a des conflits avec d'autres réservations pour la même salle
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM reservations 
        WHERE salle_id = ? 
        AND date_reservation = ?
        AND (
            (heure_debut < ? AND heure_fin > ?) OR
            (heure_debut < ? AND heure_fin > ?) OR
            (? >= heure_debut AND ? < heure_fin)
        )
    ");
    $stmt->execute([$salle_id, $date_reservation, $heure_fin, $heure_debut, $heure_fin, $heure_debut, $heure_debut, $heure_debut]);
    $conflict = $stmt->fetchColumn();

    // Si un conflit est détecté, affiche un message d'erreur
    if ($conflict > 0) {
        $error = "La salle est déjà réservée pour ce créneau.";
    } else {
        // Insère la réservation dans la base de données
        $stmt = $pdo->prepare("INSERT INTO reservations (salle_id, utilisateur_id, date_reservation, heure_debut, heure_fin) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$salle_id, $_SESSION['user_id'], $date_reservation, $heure_debut, $heure_fin]);
        $success = "Réservation réussie !";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réserver <?= htmlspecialchars($salle['nom']) ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="container">
            <h1>Réserver <?= htmlspecialchars($salle['nom']) ?></h1>
            <nav>
                <a href="index.php">Retour à la liste des salles</a>
                <a href="dashboard.php">Tableau de bord</a>
            </nav>
        </div>
    </header>

    <main class="container mt-4">
        <div class="grid grid-2">
            <div class="card">
                <h2>Informations de la salle</h2>
                <p class="mb-2"><?= htmlspecialchars($salle['description']) ?></p>
                <p><strong>Équipements :</strong> <?= htmlspecialchars($salle['equipements']) ?></p>
            </div>

            <div class="card">
                <h2>Formulaire de réservation</h2>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-error mb-3">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success mb-3">
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="fade-in">
                    <div class="form-group">
                        <label for="date_reservation">Date de réservation</label>
                        <input type="date" id="date_reservation" name="date_reservation" required>
                    </div>

                    <div class="form-group">
                        <label for="heure_debut">Heure de début</label>
                        <input type="time" id="heure_debut" name="heure_debut" required>
                    </div>

                    <div class="form-group">
                        <label for="heure_fin">Heure de fin</label>
                        <input type="time" id="heure_fin" name="heure_fin" required>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">Réserver</button>
                </form>
            </div>
        </div>
    </main>

    <footer class="container text-center mt-4 mb-4">
        <p>&copy; <?= date('Y') ?> Système de réservation de salles</p>
    </footer>
</body>
</html>