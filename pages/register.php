<?php
// Inclut le fichier d'authentification pour utiliser la fonction d'inscription
include '../includes/auth.php';

// Vérifie si le formulaire a été soumis avec la méthode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];   // Récupère le nom du formulaire
    $email = $_POST['email'];   // Récupère l'email du formulaire
    $password = $_POST['password'];   // Récupère le mot de passe du formulaire

    // Tente d'inscrire un nouvel utilisateur
    if (register($nom, $email, $password)) {
        header('Location: login.php');    // Redirige vers la page de connexion en cas de succès
        exit;
    } else {
        $error = "Erreur lors de l'inscription.";   // Message d'erreur si l'inscription échoue
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="container">
            <h1>Inscription</h1>
        </div>
    </header>

    <main class="container mt-4">
        <div class="card" style="max-width: 400px; margin: 0 auto;">
            <?php if (isset($error)): ?>
                <div class="alert alert-error mb-3">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="fade-in">
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">S'inscrire</button>
            </form>

            <p class="text-center mt-3">
                Déjà un compte ? <a href="login.php" class="btn btn-secondary">Se connecter</a>
            </p>
        </div>
    </main>

    <footer class="container text-center mt-4 mb-4">
        <p>&copy; <?= date('Y') ?> Système de réservation de salles</p>
    </footer>
</body>
</html>