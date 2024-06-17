<?php
// Fonction pour vérifier si une recette est valide
function isValidRecipe(array $recipe): bool {
    if (array_key_exists('is_enabled', $recipe)) {
        return $recipe['is_enabled'];
    }
    return false;
}

// Fonction pour obtenir les recettes valides
function getRecipes(array $recipes): array {
    $validRecipes = [];
    foreach ($recipes as $recipe) {
        if (isValidRecipe($recipe)) {
            $validRecipes[] = $recipe;
        }
    }
    return $validRecipes;
}

// Fonction pour afficher le nom de l'auteur
function displayAuthor(string $authorEmail, array $users): string {
    foreach ($users as $user) {
        if ($authorEmail === $user['email']) {
            return $user['full_name'] . ' (' . $user['age'] . ' ans)';
        }
    }
    return 'Auteur inconnu';
}

// Fonction pour afficher la date et l'heure actuelles
function getCurrentDateTime(): string {
    $date = date('d/m/Y');
    $time = date('H:i');
    return "Nous sommes le {$date} et il est {$time}";
}
?>
