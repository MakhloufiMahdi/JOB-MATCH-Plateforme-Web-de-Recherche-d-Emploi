<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once "config/database.php";

if(!isset($_SESSION['user_email'])) {
    header("Location: connexion.php");
    exit();
}


$search = $_GET['q'] ?? '';
$type_contrat = $_GET['type_contrat'] ?? '';
$location = $_GET['location'] ?? '';
$salaire_min = $_GET['salaire_min'] ?? '';
$competence = $_GET['competence'] ?? '';

try {
    $sql = "SELECT j.* FROM jobs j WHERE 1";
    $params = [];

    if($search) {
        $sql .= " AND (j.titre LIKE :search OR j.description LIKE :search OR j.entreprise LIKE :search)";
        $params[':search'] = "%$search%";
    }
    if($type_contrat) {
        $sql .= " AND j.type_contrat = :type_contrat";
        $params[':type_contrat'] = $type_contrat;
    }
    if($location) {
        $sql .= " AND j.localisation LIKE :location";
        $params[':location'] = "%$location%";
    }
    if($salaire_min && is_numeric($salaire_min)) {
        $sql .= " AND j.salaire >= :salaire_min";
        $params[':salaire_min'] = $salaire_min;
    }
    if($competence) {
        $sql .= " AND j.competences_requises LIKE :competence";
        $params[':competence'] = "%$competence%";
    }

    $sql .= " ORDER BY j.date_publication DESC";
    
    // Pagination
    $jobs_per_page = 9;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $jobs_per_page;

    // Requête pour le total
    $sql_count = "SELECT COUNT(*) as total FROM ($sql) as total_query";
    $stmt_count = $pdo->prepare($sql_count);
    $stmt_count->execute($params);
    $total_jobs = $stmt_count->fetch()['total'];
    $total_pages = ceil($total_jobs / $jobs_per_page);

    // Requête paginée
    $sql .= " LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);

    // Bind des paramètres
    foreach($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', $jobs_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    $stmt->execute();
    $jobs = $stmt->fetchAll();

} catch(PDOException $e) {
    die("Erreur database: " . $e->getMessage());
} catch(Exception $e) {
    die("Erreur: " . $e->getMessage());
}

$types_contrat = ['CDI','CDD','Freelance','Stage','Temps partiel','Alternance'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>JobMatch - Recherche d'Emploi</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .job-card {
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }
        .job-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15);
        }
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        /* Styles améliorés pour la pagination */
        .pagination-container {
            margin: 3rem 0;
            padding: 1.5rem 0;
            background: #f8f9fa;
            border-radius: 15px;
        }
        
        .pagination {
            margin: 0;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .page-item {
            margin: 0.3rem;
        }
        
        .page-link {
            min-width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 12px !important;
            border: 2px solid #dee2e6;
            color: #495057;
            transition: all 0.3s ease;
        }
        
        .page-link:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
            transform: scale(1.05);
        }
        
        .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .page-item.disabled .page-link {
            color: #6c757d;
            background: #f8f9fa;
            border-color: #dee2e6;
        }
        
        .pagination-info {
            text-align: center;
            margin-top: 1rem;
            font-size: 1.1rem;
            color: #6c757d;
            font-weight: 500;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .page-link {
                min-width: 40px;
                height: 40px;
                font-size: 1rem;
            }
            
            .pagination-container {
                margin: 2rem 0;
            }
        }
    </style>
</head>
<body>

<?php include "views/layout/header.php"; ?>

<div class="container-fluid py-4 bg-light">
    <div class="container">
        <!-- Header Section -->
        <div class="row align-items-center mb-5">
            <div class="col-md-8">
                <h1 class="display-5 fw-bold text-primary">Trouvez Votre Prochain Emploi</h1>
                <p class="lead text-muted"><?= $total_jobs ?> offres disponibles</p>
            </div>
            <div class="col-md-4 text-md-end">
                <span class="badge bg-success">
                    <i class="fas fa-briefcase me-1"></i><?= $total_jobs ?> offres
                </span>
            </div>
        </div>

        <!-- Search and Filters Form -->
        <form method="GET" class="mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Mots-clés</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" name="q" placeholder="Poste, compétence, entreprise..." value="<?= htmlspecialchars($search) ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Type de contrat</label>
                            <select name="type_contrat" class="form-select">
                                <option value="">Tous les types</option>
                                <?php foreach($types_contrat as $t): ?>
                                    <option value="<?= $t ?>" <?= ($type_contrat==$t) ? "selected" : "" ?>><?= $t ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-1"></i>Filtrer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Results Info -->
        <?php if($search || $type_contrat || $location): ?>
        <div class="alert alert-info d-flex align-items-center mb-4">
            <i class="fas fa-info-circle me-2"></i>
            <div>
                <strong>Filtres actifs :</strong>
                <?php
                $active_filters = [];
                if($search) $active_filters[] = "Recherche: \"$search\"";
                if($type_contrat) $active_filters[] = "Contrat: $type_contrat";
                if($location) $active_filters[] = "Localisation: $location";
                echo implode(', ', $active_filters);
                ?>
                - <strong><?= $total_jobs ?></strong> résultat(s)
            </div>
        </div>
        <?php endif; ?>

        <!-- Jobs Grid -->
        <div class="row g-4">
            <?php if(empty($jobs)): ?>
            <div class="col-12 text-center py-5">
                <div class="text-muted">
                    <i class="fas fa-search fa-3x mb-3"></i>
                    <h4>Aucune offre ne correspond à vos critères</h4>
                    <p>Essayez d'élargir votre recherche ou de modifier vos filtres.</p>
                    <a href="jobs.php" class="btn btn-primary">Voir toutes les offres</a>
                </div>
            </div>
            <?php else: ?>
                <?php foreach($jobs as $job): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card job-card h-100 shadow-sm border-0">
                        <div class="card-body p-4">
                            <!-- Job Header -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="flex-grow-1">
                                    <h5 class="card-title fw-bold text-primary mb-1"><?= htmlspecialchars($job['titre']) ?></h5>
                                    <p class="text-muted mb-2"><?= htmlspecialchars($job['entreprise']) ?></p>
                                </div>
                            </div>

                            <!-- Job Details -->
                            <div class="mb-3">
                                <div class="d-flex flex-wrap gap-2 mb-2">
                                    <span class="badge bg-primary"><?= htmlspecialchars($job['type_contrat']) ?></span>
                                    <?php if(!empty($job['salaire'])): ?>
                                        <span class="badge bg-success"><?= number_format($job['salaire'], 0, ',', ' ') ?> €/an</span>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if(!empty($job['localisation'])): ?>
                                <div class="d-flex align-items-center text-muted small mb-2">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    <?= htmlspecialchars($job['localisation']) ?>
                                </div>
                                <?php endif; ?>
                            </div>

                            <!-- Job Description -->
                            <p class="card-text text-muted mb-3 line-clamp-3">
                                <?= nl2br(htmlspecialchars(substr($job['description'], 0, 150))) ?>...
                            </p>

                            <!-- Job Footer -->
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    <?= date('d/m/Y', strtotime($job['date_publication'])) ?>
                                </small>
                                <a href="views/jobs/show.php?id=<?= $job['id'] ?>" class="btn btn-primary btn-sm">
                                    Voir détails <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Pagination améliorée -->
        <?php if($total_pages > 1): ?>
        <div class="pagination-container">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <!-- Premier page -->
                    <?php if($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>" title="Première page">
                            <i class="fas fa-angle-double-left"></i>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <!-- Page précédente -->
                    <?php if($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>" title="Page précédente">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                    <?php endif; ?>

                    <!-- Pages numérotées - Afficher seulement 5 pages autour de la page actuelle -->
                    <?php
                    $start_page = max(1, $page - 2);
                    $end_page = min($total_pages, $page + 2);
                    
                    for($i = $start_page; $i <= $end_page; $i++): 
                    ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                    <?php endfor; ?>

                    <!-- Page suivante -->
                    <?php if($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>" title="Page suivante">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <!-- Dernière page -->
                    <?php if($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $total_pages])) ?>" title="Dernière page">
                            <i class="fas fa-angle-double-right"></i>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
            
            <!-- Information de pagination -->
            <div class="pagination-info">
                Page <?= $page ?> sur <?= $total_pages ?> - 
                Affichage de <?= (($page - 1) * $jobs_per_page) + 1 ?> à <?= min($page * $jobs_per_page, $total_jobs) ?> 
                sur <?= $total_jobs ?> offres
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include "views/layout/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
