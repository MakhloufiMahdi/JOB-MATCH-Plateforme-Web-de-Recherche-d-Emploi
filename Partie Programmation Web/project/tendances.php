<?php
session_start();
require_once "config/database.php";

if(!isset($_SESSION['user_email'])) {
    header("Location: connexion.php");
    exit();
}

// Top 5 métiers - FILTRER LES VALEURS NULL ET VIDES
$stmt = $pdo->query("SELECT titre, COUNT(*) as total FROM jobs WHERE titre IS NOT NULL AND titre != '' AND titre != ' ' GROUP BY titre ORDER BY total DESC LIMIT 5");
$top_jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Top 5 entreprises - FILTRER LES VALEURS NULL ET VIDES
$stmt = $pdo->query("SELECT entreprise, COUNT(*) as total FROM jobs WHERE entreprise IS NOT NULL AND entreprise != '' AND entreprise != ' ' GROUP BY entreprise ORDER BY total DESC LIMIT 5");
$top_companies = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Compétences tendances
$stmt = $pdo->query("SELECT description FROM jobs WHERE description IS NOT NULL");
$all_desc = $stmt->fetchAll(PDO::FETCH_COLUMN);
$keywords = ["Python", "Java", "SQL", "PHP", "HTML", "CSS", "JavaScript", "Excel", "Management", "React", "Vue", "Angular", "Node.js", "Laravel", "Symfony"];
$skills_count = [];
foreach ($all_desc as $desc) {
    foreach ($keywords as $kw) {
        if (stripos($desc, $kw) !== false) {
            $skills_count[$kw] = ($skills_count[$kw] ?? 0) + 1;
        }
    }
}
arsort($skills_count);
$top_skills = array_slice($skills_count, 0, 10, true);

// Statistiques générales
$total_jobs = $pdo->query("SELECT COUNT(*) FROM jobs")->fetchColumn();
$avg_jobs_per_company = $pdo->query("SELECT AVG(job_count) FROM (SELECT COUNT(*) as job_count FROM jobs GROUP BY entreprise) as counts")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>JobMatch - Tendances du Marché</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome@6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        }
        
        .hero-section {
            background: var(--gradient-primary);
            color: white;
            border-radius: 20px;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .trend-badge {
            background: var(--gradient-success);
            color: white;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 25px;
        }
        
        .list-group-item {
            border: none;
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
            margin-bottom: 0.5rem;
            border-radius: 10px !important;
            background: #f8f9fa;
        }
        
        .list-group-item:hover {
            border-left-color: #ff6b6b;
            background: linear-gradient(135deg, #ffffff 0%, #f1f3f4 100%);
            transform: translateX(5px);
        }
        
        .job-title {
            color: #2d3748 !important;
            font-weight: 600 !important;
            font-size: 1.1rem;
        }
        
        .company-name {
            color: #2d3748 !important;
            font-weight: 600 !important;
            font-size: 1.1rem;
        }
        
        .rank-number {
            color: #e53e3e !important;
            font-weight: 800 !important;
            font-size: 1.2rem;
            min-width: 30px;
            text-align: center;
        }
        
        .progress {
            height: 12px;
            border-radius: 10px;
            background: #e2e8f0;
        }
        
        .progress-bar {
            border-radius: 10px;
            background: var(--gradient-primary);
        }
        
        .chart-container {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<?php include "views/layout/header.php"; ?>

<div class="container py-5">
    <!-- Hero Section -->
    <div class="hero-section p-5 text-center mb-5">
        <h1 class="display-4 fw-bold mb-3">📊 Tendances du Marché</h1>
        <p class="lead mb-4">Découvrez les métiers, entreprises et compétences les plus demandés</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <span class="trend-badge"><i class="fas fa-briefcase me-2"></i><?= $total_jobs ?> offres analysées</span>
            <span class="trend-badge"><i class="fas fa-building me-2"></i><?= round($avg_jobs_per_company, 1) ?> offres/entreprise</span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-5">
        <div class="col-md-4 mb-4">
            <div class="stat-card text-center p-4">
                <div class="icon-wrapper mb-3">
                    <i class="fas fa-chart-line fa-3x text-primary"></i>
                </div>
                <h3 class="fw-bold"><?= $total_jobs ?></h3>
                <p class="text-muted mb-0">Offres totales analysées</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="stat-card text-center p-4">
                <div class="icon-wrapper mb-3">
                    <i class="fas fa-building fa-3x text-success"></i>
                </div>
                <h3 class="fw-bold"><?= count($top_companies) ?></h3>
                <p class="text-muted mb-0">Entreprises actives</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="stat-card text-center p-4">
                <div class="icon-wrapper mb-3">
                    <i class="fas fa-code fa-3x text-warning"></i>
                </div>
                <h3 class="fw-bold"><?= count($top_skills) ?></h3>
                <p class="text-muted mb-0">Compétences suivies</p>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top Métiers -->
        <div class="col-lg-6 mb-5">
            <div class="stat-card p-4">
                <h3 class="fw-bold mb-4 text-primary">
                    <i class="fas fa-trophy me-2"></i>Top 5 Métiers
                </h3>
                <div class="list-group">
                    <?php if(count($top_jobs) > 0): ?>
                        <?php foreach($top_jobs as $index => $job): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <span class="rank-number me-3">#<?= $index + 1 ?></span>
                                <span class="job-title"><?= htmlspecialchars($job['titre']) ?></span>
                            </div>
                            <span class="badge rounded-pill bg-primary text-white">
                                <?= $job['total'] ?> offres
                            </span>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>Aucun métier trouvé dans la base de données.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Top Entreprises -->
        <div class="col-lg-6 mb-5">
            <div class="stat-card p-4">
                <h3 class="fw-bold mb-4 text-success">
                    <i class="fas fa-crown me-2"></i>Top 5 Entreprises
                </h3>
                <div class="list-group">
                    <?php if(count($top_companies) > 0): ?>
                        <?php foreach($top_companies as $index => $company): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <span class="rank-number me-3">#<?= $index + 1 ?></span>
                                <span class="company-name"><?= htmlspecialchars($company['entreprise']) ?></span>
                            </div>
                            <span class="badge rounded-pill bg-success text-white">
                                <?= $company['total'] ?> offres
                            </span>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>Aucune entreprise trouvée dans la base de données.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Compétences Tendance - Graphique à barres -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="chart-container">
                <h3 class="fw-bold mb-4 text-center text-warning">
                    <i class="fas fa-fire me-2"></i>Top 10 Compétences Demandées
                </h3>
                <canvas id="skillsChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Progress Bars -->
    <div class="row">
        <div class="col-12">
            <div class="stat-card p-4">
                <h3 class="fw-bold mb-4 text-info">
                    <i class="fas fa-signal me-2"></i>Répartition des Compétences
                </h3>
                <?php 
                $max_skill = max($top_skills);
                foreach($top_skills as $skill => $count): 
                    $percentage = ($count / $max_skill) * 100;
                ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-semibold"><?= $skill ?></span>
                        <span class="text-muted"><?= $count ?> offres</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: <?= $percentage ?>%"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>

const skillsData = {
    labels: <?= json_encode(array_keys($top_skills)) ?>,
    datasets: [{
        label: 'Nombre d\'offres',
        data: <?= json_encode(array_values($top_skills)) ?>,
        backgroundColor: 'rgba(59, 130, 246, 0.8)',
        borderColor: 'rgba(59, 130, 246, 1)',
        borderWidth: 2,
        borderRadius: 5,
        hoverBackgroundColor: 'rgba(59, 130, 246, 1)'
    }]
};

const config = {
    type: 'bar',
    data: skillsData,
    options: {
        responsive: true,
        indexAxis: 'y',
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return `${context.dataset.label}: ${context.raw} offres`;
                    }
                }
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.1)'
                }
            },
            y: {
                grid: {
                    color: 'rgba(0, 0, 0, 0.1)'
                },
                ticks: {
                    font: {
                        weight: 'bold',
                        size: 12
                    }
                }
            }
        },
        animation: {
            duration: 2000,
            easing: 'easeOutQuart'
        }
    }
};


window.addEventListener('load', function() {
    const ctx = document.getElementById('skillsChart').getContext('2d');
    new Chart(ctx, config);
    
    
    document.querySelectorAll('.progress-bar').forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.transition = 'width 1.5s ease-in-out';
            bar.style.width = width;
        }, 500);
    });
});
</script>

<?php include "views/layout/footer.php"; ?>
</body>
</html>
