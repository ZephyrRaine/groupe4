<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $content = $_POST['content'];

    $stmt = $pdo->prepare('INSERT INTO posts (user_id, content) VALUES (?, ?)');
    $stmt->execute([$user_id, $content]);
}

$posts = $pdo->query('SELECT posts.content, posts.created_at, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC')->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Accueil - Mini Réseau Social</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1>Mini Réseau Social</h1>
    <a href="logout.php" class="btn btn-danger">Se déconnecter</a>

    <h2>Publier un message</h2>
    <form action="home.php" method="post">
        <div class="mb-3">
            <label for="content" class="form-label">Message</label>
            <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Publier</button>
    </form>

    <h2>Messages</h2>
    <?php foreach ($posts as $post) : ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($post['username']); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($post['content']); ?></p>
                <p class="card-text"><small class="text-muted"><?php echo $post['created_at']; ?></small></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html> 