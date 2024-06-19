<?php
requireonce(_DIR . '/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $POST['confirmpassword'];

    // Check if passwords match
    if ($password !== $confirmpassword) {
        $errormessage = "Les mots de passe ne correspondent pas.";
    } else {
        // Check if the email is already registered
        $sql = "SELECT * FROM utilisateurs WHERE email = :email";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $error_message = "Cet email est déjà enregistré.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $sql = "INSERT INTO utilisateurs (email, mot_de_passe) VALUES (:email, :password)";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);

            if ($stmt->execute()) {
                header('Location: connexion.php');
                exit;
            } else {
                $error_message = "Une erreur est survenue lors de l'inscription.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
<div class="container">
    <?php require_once(__DIR . '/header.php'); ?>
    <h1 class="mt-4">Inscription</h1>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once(__DIR . '/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>