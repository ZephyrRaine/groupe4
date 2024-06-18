<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Boutique en ligne simplifiée</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="panier.php">Panier</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="gestionproduits.php">Gestion produits</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="compte.php">Compte</a>
                </li>
            </ul>
            <form class="d-flex" action="process_login.php" method="post">
                <input class="form-control me-2" type="email" placeholder="Email" aria-label="Email" name="email" required>
                <input class="form-control me-2" type="password" placeholder="Mot de passe" aria-label="Mot de passe" name="password" required>
                <button class="btn btn-outline-success" type="submit">Connexion</button>
            </form>
        </div>
    </div>
</nav>
