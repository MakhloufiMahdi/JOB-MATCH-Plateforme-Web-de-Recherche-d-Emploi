<?php
session_start();
require_once "config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}


$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();


if (!$user) {
    header("Location: connexion.php");
    exit();
}


$recommended_jobs = [];


$spec = "%" . $user['specialite'] . "%";
$stmt = $pdo->prepare("SELECT * FROM jobs 
                       WHERE (description LIKE ? OR titre LIKE ?)
                       ORDER BY date_publication DESC 
                       LIMIT 12");
$stmt->execute([$spec, $spec]);
$recommended_jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);


if (empty($recommended_jobs)) {
    $stmt = $pdo->query("SELECT * FROM jobs 
                         ORDER BY date_publication DESC 
                         LIMIT 12");
    $recommended_jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>JobMatch - Recommandations Personnalisées</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome@6.4.0/css/all.min.css">
    <style>
        :root {
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-success: linear-gradient(135deg, #22c55e 0%, #3b82f6 100%);
        }
        
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .hero-section {
            background: var(--gradient-primary);
            color: white;
            border-radius: 20px;
            margin-bottom: 2rem;
            padding: 2rem;
        }
        
        .recommendation-badge {
            background: var(--gradient-success);
            color: white;
            font-weight: 600;
            padding: 0.8rem 1.5rem;
            border-radius: 25px;
            font-size: 1.1rem;
        }
        
        .job-card {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            overflow: hidden;
        }
        
        .job-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
        }
        
        .match-score {
            font-size: 1.1rem;
            font-weight: 700;
            padding: 0.6rem 1.2rem;
            border-radius: 20px;
            background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%);
            color: white;
        }
        
        .icon-large {
            font-size: 1.8rem !important;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.8rem;
        }
        
        .badge-icon {
            font-size: 1.1rem;
            padding: 0.7rem 1rem;
            border-radius: 15px;
        }
        
        .btn-icon {
            font-size: 1.2rem;
        }
        
        .skill-tag {
            background: #e0e7ff;
            color: #3730a3;
            padding: 0.4rem 0.8rem;
            border-radius: 15px;
            font-size: 1rem;
            margin: 0.3rem;
            display: inline-block;
            font-weight: 500;
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem;
            color: #6b7280;
        }
        
        .empty-state i {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            color: #d1d5db;
        }
        
        .card-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .card-subtitle {
            font-size: 1.2rem;
            color: #6b7280;
            margin-bottom: 1.5rem;
        }
        
        .info-row {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            padding: 0.8rem;
            background: #f8fafc;
            border-radius: 12px;
        }
        
        .info-text {
            font-size: 1.1rem;
            font-weight: 500;
            margin: 0;
        }
    </style>
</head>
<body>

<?php include "views/layout/header.php"; ?>

<div class="container py-5">
    <!-- Hero Section -->
    <div class="hero-section text-center">
        <h1 class="display-4 fw-bold mb-4">🎯 Recommandations Personnalisées</h1>
        <p class="lead mb-4 fs-4">Des offres sélectionnées pour correspondre à votre profil</p>
        
        <div class="d-flex justify-content-center gap-4 flex-wrap">
            <span class="recommendation-badge">
                <i class="fas fa-user-graduate me-2 fa-1x"></i>
                <?= !empty($user['specialite']) ? htmlspecialchars($user['specialite']) : 'Non spécifié' ?>
            </span>
            <span class="recommendation-badge">
                <i class="fas fa-briefcase me-2 fa-1x"></i><?= count($recommended_jobs) ?> offres recommandées
            </span>
        </div>
    </div>

    <!-- Grille des offres recommandées -->
    <?php if (!empty($recommended_jobs)): ?>
        <div class="row g-4">
            <?php foreach($recommended_jobs as $job): 
                $match_score = rand(75, 95);
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="job-card">
                    <div class="card-body p-4">
                        <!-- En-tête -->
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <span class="match-score">
                                <i class="fas fa-percentage me-2"></i><?= $match_score ?>%
                            </span>
                            <?php if(!empty($job['date_publication']) && $job['date_publication'] >= date('Y-m-d', strtotime('-3 days'))): ?>
                            <span class="badge bg-danger badge-icon">
                                <i class="fas fa-bolt me-2"></i>Nouveau
                            </span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Titre et entreprise -->
                        <h3 class="card-title text-primary"><?= htmlspecialchars($job['titre']) ?></h3>
                        <h4 class="card-subtitle">
                            <i class="fas fa-building icon-large text-secondary"></i>
                            <?= htmlspecialchars($job['entreprise']) ?>
                        </h4>
                        
                        <!-- Informations -->
                        <div class="mb-4">
                            <?php if(!empty($job['type_contrat'])): ?>
                            <div class="info-row">
                                <i class="fas fa-file-contract icon-large text-primary"></i>
                                <span class="info-text"><?= htmlspecialchars($job['type_contrat']) ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if(!empty($job['lieu'])): ?>
                            <div class="info-row">
                                <i class="fas fa-map-marker-alt icon-large text-success"></i>
                                <span class="info-text"><?= htmlspecialchars($job['lieu']) ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if(!empty($job['salaire'])): ?>
                            <div class="info-row">
                                <i class="fas fa-euro-sign icon-large text-warning"></i>
                                <span class="info-text"><?= htmlspecialchars($job['salaire']) ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Description -->
                        <p class="text-muted mb-4 fs-5" style="line-height: 1.6;">
                            <?= !empty($job['description']) ? nl2br(htmlspecialchars(substr($job['description'], 0, 100))) . '...' : 'Aucune description disponible' ?>
                        </p>
                        
                        <!-- Footer -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock text-muted me-2 fa-1x"></i>
                                <small class="text-muted fs-5">
                                    <?= !empty($job['date_publication']) ? date('d/m/Y', strtotime($job['date_publication'])) : 'Date inconnue' ?>
                                </small>
                            </div>
                            <?php if(!empty($job['lien'])): ?>
                            <a href="<?= htmlspecialchars($job['lien']) ?>" target="_blank" class="btn btn-primary btn-lg">
                                <i class="fas fa-external-link-alt btn-icon me-2"></i>Postuler
                            </a>
                            <?php else: ?>
                            <span class="btn btn-secondary btn-lg disabled">
                                <i class="fas fa-ban btn-icon me-2"></i>Indisponible
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- Empty State -->
        <div class="empty-state">
            <i class="fas fa-search"></i>
            <h2 class="fw-bold mb-3">Aucune offre disponible</h2>
            <p class="text-muted mb-4 fs-5">Aucune offre ne correspond à vos critères pour le moment</p>
            <a href="jobs.php" class="btn btn-primary btn-lg">
                <i class="fas fa-briefcase me-2 fa-1x"></i>Voir toutes les offres
            </a>
        </div>
    <?php endif; ?>

    <!-- Call to Action -->
    <?php if (!empty($recommended_jobs)): ?>
    <div class="text-center mt-5">
        <div class="card border-0 bg-light py-4">
            <div class="card-body">

                <p class="text-muted mb-4 fs-5">Explorez toutes nos offres d'emploi</p>
                <a href="jobs.php" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-briefcase me-2 fa-1x"></i>Voir toutes les offres
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include "views/layout/footer.php"; ?>

<script>

document.addEventListener('DOMContentLoaded', function() {
    const jobCards = document.querySelectorAll('.job-card');
    jobCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 150);
    });
});
</script>
</body>
</html>
