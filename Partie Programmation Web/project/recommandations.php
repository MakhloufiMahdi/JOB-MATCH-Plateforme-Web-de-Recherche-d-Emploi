<?php
session_start();
require_once "config/database.php";
include "views/layout/header.php";


if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}


$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();


$stmt = $pdo->prepare("SELECT * FROM jobs 
                       WHERE (description LIKE ? OR titre LIKE ?) 
                       LIMIT 10");
$spec = "%" . $user['specialite'] . "%";
$stmt->execute([$spec, $spec]);
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">

    <h2 class="mb-4 text-center" style="color:#3b82f6;"> Offres Recommandées pour Vous</h2>

    <?php if ($jobs): ?>
        <div class="row">
            <?php foreach($jobs as $job): ?>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0 h-100 job-card">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-primary"><?= htmlspecialchars($job['titre']) ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($job['entreprise']) ?> - <?= htmlspecialchars($job['lieu']) ?></h6>
                            <p class="card-text flex-grow-1"><?= substr(htmlspecialchars($job['description']), 0, 140) ?>...</p>
                            <a href="<?= htmlspecialchars($job['lien']) ?>" target="_blank" class="btn btn-gradient btn-sm mt-2">Voir l'offre</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-center text-muted">Aucune recommandation pour l'instant</p>
    <?php endif; ?>

</div>

<style>
    /* Titres */
    h2 {
        font-weight: 700;
        margin-bottom: 1.5rem;
    }
    h5.card-title {
        font-weight: 600;
    }
    h6.card-subtitle {
        font-size: 0.9rem;
    }

    /* Cartes d'emploi */
    .job-card {
        border-radius: 12px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .job-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    /* Bouton "Voir l'offre" */
    .btn-gradient {
        background: linear-gradient(90deg, #ec4899, #3b82f6);
        color: #fff;
        border: none;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        color: #fff;
    }

    /* Responsive spacing */
    @media (max-width: 768px) {
        .job-card {
            margin-bottom: 1rem;
        }
    }
</style>

<?php include "views/layout/footer.php"; ?>
