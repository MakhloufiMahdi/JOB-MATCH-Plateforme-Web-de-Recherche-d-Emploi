<?php include "views/layout/header.php"; ?>
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Optionnel : Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<!-- Fichier CSS personnalisé -->
<link rel="stylesheet" href="assets/css/style.css">


<h1><?= htmlspecialchars($job['titre']) ?></h1>
<p><strong>Entreprise :</strong> <?= htmlspecialchars($job['entreprise']) ?></p>
<p><strong>Lieu :</strong> <?= htmlspecialchars($job['lieu']) ?></p>
<p><strong>Type de contrat :</strong> <?= htmlspecialchars($job['type_contrat']) ?></p>
<p><strong>Date de publication :</strong> <?= htmlspecialchars($job['date_publication']) ?></p>
<p><strong>Description :</strong><br><?= nl2br(htmlspecialchars($job['description'])) ?></p>

<a href="index.php?controller=jobs&action=index" class="btn btn-secondary mt-3">Retour à la liste</a>

<?php include "views/layout/footer.php"; ?>
