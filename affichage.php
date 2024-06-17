<?php
echo "<h3>Détails des recettes activées:</h3>";
echo "<table border='1'>";
echo "<tr><th>Title</th><th>Description</th><th>Author</th></tr>";
foreach ($recipeDetails as $recipe) {
    if ($recipe['is_enabled']) {
        echo "<tr>";
        echo "<td>{$recipe['title']}</td>";
        echo "<td>{$recipe['description']}</td>";
        echo "<td>{$recipe['author']}</td>";
        echo "</tr>";
    }
}
echo "</table>";
?><?php
$array['newKey'] = $newElement;
?>