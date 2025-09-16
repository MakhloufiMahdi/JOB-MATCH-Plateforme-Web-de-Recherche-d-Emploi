<?php
session_start();
require_once "config/database.php";
if(!isset($_SESSION['user_id'])) header("Location: connexion.php");


$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Statistiques de matching
$stmt = $pdo->prepare("SELECT COUNT(*) FROM jobs WHERE description LIKE ? OR titre LIKE ?");
$spec = '%' . $user['specialite'] . '%';
$stmt->execute([$spec, $spec]);
$matching_jobs = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM jobs");
$total_jobs = $stmt->fetchColumn();
$match_score = ($total_jobs > 0) ? round(($matching_jobs / $total_jobs) * 100) : 0;

// Dernières offres correspondantes
$stmt = $pdo->prepare("SELECT * FROM jobs 
                       WHERE description LIKE ? OR titre LIKE ? 
                       ORDER BY date_publication DESC 
                       LIMIT 5");
$stmt->execute([$spec, $spec]);
$recent_matching_jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Compétences de l'utilisateur (si disponibles)
$user_skills = [];
if (!empty($user['competences'])) {
    $user_skills = explode(',', $user['competences']);
    $user_skills = array_map('trim', $user_skills);
    $user_skills = array_slice($user_skills, 0, 8);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>JobMatch - Mon Profil</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome@6.4.0/css/all.min.css">
    <style>
        :root {
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-success: linear-gradient(135deg, #22c55e 0%, #3b82f6 100%);
            --gradient-warning: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
        }
        
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
        }
        
        .main-content {
            flex: 1;
        }
        
        .profile-header {
            background: var(--gradient-primary);
            color: white;
            border-radius: 20px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .profile-avatar {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 3rem;
            border: 4px solid white;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            margin-bottom: 1.5rem;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #667eea;
        }
        
        .progress-container {
            background: #f8fafc;
            border-radius: 15px;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }
        
        .progress {
            height: 30px;
            border-radius: 15px;
            background: #e2e8f0;
            overflow: hidden;
        }
        
        .progress-bar {
            background: var(--gradient-primary);
            border-radius: 15px;
            font-weight: 600;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .skill-tag {
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            color: #3730a3;
            padding: 0.6rem 1.2rem;
            border-radius: 20px;
            font-size: 1.1rem;
            font-weight: 500;
            margin: 0.5rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .job-card {
            background: white;
            border-radius: 12px;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
            margin-bottom: 1rem;
        }
        
        .job-card:hover {
            transform: translateX(5px);
        }
        
        .btn-gradient {
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 15px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
            color: white;
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 3px solid #667eea;
        }
        
        /* Correction pour le footer */
        .footer-fix {
            margin-top: auto;
            width: 100%;
        }
    </style>
</head>
<body>

<?php include "views/layout/header.php"; ?>

<div class="main-content">
    <div class="container py-5">
        <!-- Header du profil -->
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user"></i>
            </div>
            <h1 class="display-5 fw-bold mb-2"><?= htmlspecialchars($user['prenom']) ?> <?= htmlspecialchars($user['nom'] ?? '') ?></h1>
            <p class="lead mb-0">Bienvenue sur votre espace personnel</p>
        </div>

        <div class="row">
            <!-- Colonne des statistiques -->
            <div class="col-lg-4 mb-4">
                <div class="stat-card p-4 text-center">
                    <i class="fas fa-user-circle stat-icon"></i>
                    <h3 class="fw-bold mb-3">Mon Profil</h3>
                    
                    <div class="text-start mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-envelope text-primary me-3 fa-lg"></i>
                            <div>
                                <small class="text-muted d-block">Email</small>
                                <strong><?= htmlspecialchars($user['email']) ?></strong>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-graduation-cap text-success me-3 fa-lg"></i>
                            <div>
                                <small class="text-muted d-block">Spécialité</small>
                                <strong><?= htmlspecialchars($user['specialite']) ?></strong>
                            </div>
                        </div>
                        
                        <?php if(!empty($user['telephone'])): ?>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-phone text-warning me-3 fa-lg"></i>
                            <div>
                                <small class="text-muted d-block">Téléphone</small>
                                <strong><?= htmlspecialchars($user['telephone']) ?></strong>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                   
                </div>
            </div>

            <!-- Colonne principale -->
            <div class="col-lg-8">
                <!-- Score de matching -->
                <div class="stat-card p-4 mb-4">
                    <h3 class="section-title">
                        <i class="fas fa-chart-line me-2"></i>Score de Matching
                    </h3>
                    
                    <div class="progress-container">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold">Compatibilité avec les offres</span>
                            <span class="fw-bold text-primary"><?= $match_score ?>%</span>
                        </div>
                        
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: <?= $match_score ?>%" 
                                 aria-valuenow="<?= $match_score ?>" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                <?= $match_score ?>%
                            </div>
                        </div>
                        
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <?= $matching_jobs ?> offres correspondent à votre spécialité sur <?= $total_jobs ?> au total
                            </small>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <a href="jobs.php?q=<?= urlencode($user['specialite']) ?>" class="btn-gradient btn">
                            <i class="fas fa-briefcase me-2"></i>Voir les offres correspondantes
                        </a>
                    </div>
                </div>

                <!-- Compétences -->
                <?php if(!empty($user_skills)): ?>
                <div class="stat-card p-4 mb-4">
                    <h3 class="section-title">
                        <i class="fas fa-code me-2"></i>Mes Compétences
                    </h3>
                    
                    <div class="d-flex flex-wrap justify-content-center">
                        <?php foreach($user_skills as $skill): ?>
                        <span class="skill-tag">
                            <i class="fas fa-check-circle"></i><?= htmlspecialchars($skill) ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php if(empty($user['competences'])): ?>
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Ajoutez plus de compétences pour améliorer vos recommandations
                        </small>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Offres récentes correspondantes -->
                <?php if(!empty($recent_matching_jobs)): ?>
                <div class="stat-card p-4">
                    <h3 class="section-title">
                        <i class="fas fa-clock me-2"></i>Offres Récentes Correspondantes</i>
                    </h3>
                    
                    <?php foreach($recent_matching_jobs as $job): ?>
                    <div class="job-card p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-1"><?= htmlspecialchars($job['titre']) ?></h6>
                                <small class="text-muted"><?= htmlspecialchars($job['entreprise']) ?></small>
                            </div>
                            <a href="<?= htmlspecialchars($job['lien'] ?? '#') ?>" target="_blank" class="btn btn-sm btn-primary">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <div class="text-center mt-3">
                        <a href="jobs.php" class="btn btn-outline-primary">
                            <i class="fas fa-eye me-2"></i>Voir toutes les offres
                        </a>
                    </div>

                <?php endif; ?>
            </div>
        </div>
    </div>
</div>



<script>

document.addEventListener('DOMContentLoaded', function() {
    const elements = document.querySelectorAll('.stat-card, .profile-header');
    elements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        setTimeout(() => {
            element.style.transition = 'all 0.6s ease';
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, index * 200);
    });
    
    
    const progressBar = document.querySelector('.progress-bar');
    if (progressBar) {
        const width = progressBar.style.width;
        progressBar.style.width = '0%';
        setTimeout(() => {
            progressBar.style.transition = 'width 1.5s ease-in-out';
            progressBar.style.width = width;
        }, 1000);
    }
});

</script>
</body>
</html>
