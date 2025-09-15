<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Job Matching</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include "views/layout/header.php"; ?>

<header class="text-center my-5">
    <h2 class="display-4 fw-bold">Trouvez votre emploi idéal</h1>
    <p class="lead">Connectez-vous ou inscrivez-vous pour accéder aux offres adaptées à vos compétences.</p>
    <a href="inscription.php" class="btn btn-success btn-lg me-3">S'inscrire</a>
    <a href="connexion.php" class="btn btn-primary btn-lg">Se connecter</a>
</header>

<footer>
    &copy; 2025 Job Matching. Tous droits réservés.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
