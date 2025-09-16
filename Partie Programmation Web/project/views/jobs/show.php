<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once "../../config/database.php"; 

if(!isset($_SESSION['user_email'])) {
    header("Location: ../../connexion.php"); 
    exit();
}


if(!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../../jobs.php"); 
    exit();
}

$job_id = (int)$_GET['id'];


try {
    $stmt = $pdo->prepare("SELECT * FROM jobs WHERE id = :id");
    $stmt->execute([':id' => $job_id]);
    $job = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if(!$job) {
        header("Location: ../../jobs.php"); 
        exit();
    }
} catch(PDOException $e) {
    die("Erreur database: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>JobMatch - <?= htmlspecialchars($job['titre']) ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome@6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .job-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 10px 10px 0 0;
        }
        .job-details {
            padding: 2rem;
        }
        .back-button {
            margin-top: 2rem;
        }
    </style>
</head>
<body>

<?php include "../../views/layout/header.php"; ?> 

<div class="container mt-4">
    <div class="card shadow">
        <!-- En-tête -->
        <div class="job-header">
            <h1 class="display-5 fw-bold"><?= htmlspecialchars($job['titre']) ?></h1>
            <p class="lead mb-0"><?= htmlspecialchars($job['entreprise']) ?></p>
        </div>

        <!-- Détails -->
        <div class="job-details">
            <div class="row mb-4">
                
                <div class="col-md-6">
                    <p><strong><i class="fas fa-file-contract me-2"></i>Type de contrat :</strong> 
                    <?= htmlspecialchars($job['type_contrat']) ?></p>
                </div>
            </div>

            <?php if(!empty($job['salaire'])): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <p><strong><i class="fas fa-euro-sign me-2"></i>Salaire :</strong> 
                    <?= htmlspecialchars($job['salaire']) ?></p>
                </div>
            </div>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-12">
                    <p><strong><i class="fas fa-calendar me-2"></i>Date de publication :</strong> 
                    <?= date('d/m/Y', strtotime($job['date_publication'])) ?></p>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h4><i class="fas fa-align-left me-2"></i>Description du poste</h4>
                    <div class="border-start border-3 border-primary ps-3 mt-3">
                        <p class="lead"><?= nl2br(htmlspecialchars($job['description'])) ?></p>
                    </div>
                </div>
            </div>

            <?php if(!empty($job['lien'])): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <a href="<?= htmlspecialchars($job['lien']) ?>" target="_blank" class="btn btn-success btn-lg">
                        <i class="fas fa-external-link-alt me-2"></i>Postuler sur le site original
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bouton retour -->
    <div class="text-center back-button">
        <a href="../../jobs.php" class="btn btn-primary"> 
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste des offres
        </a>
    </div>
</div>

<?php include "../../views/layout/footer.php"; ?> 

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
