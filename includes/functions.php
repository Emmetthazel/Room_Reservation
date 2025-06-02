<?php
// Redirige l'utilisateur vers une autre URL
function redirect($url) {
    header("Location: $url"); // Définit l'en-tête HTTP pour la redirection
    exit; // Arrête l'exécution du script
}

// Nettoie les données d'entrée pour éviter les injections et XSS
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data))); 
    // Supprime les espaces inutiles (trim), les balises HTML (strip_tags) 
    // et encode les caractères spéciaux (htmlspecialchars)
}
?>
