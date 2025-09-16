<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>JobMatch - Trouvez Votre Emploi Idéal</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="JobMatch vous connecte avec les meilleures opportunités professionnelles. Inscrivez-vous gratuitement et trouvez un emploi qui correspond à vos compétences.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include "views/layout/header.php"; ?>
<!-- Hero Section Optimisée Mobile -->
<section class="hero-section bg-gradient-primary py-4 py-lg-5">
    <div class="container py-3 py-lg-5">
        <div class="row align-items-center">
            
            
            <div class="col-12 col-lg-6 order-2 order-lg-1 mt-4 mt-lg-0">
                <h1 class="hero-title display-5 display-lg-4 fw-bold text-white mb-3 mb-lg-4">
                    Trouvez l'emploi qui <span class="text-warning">vous correspond</span>
                </h1>
                <p class="hero-text lead text-white mb-3 mb-lg-4 fs-6 fs-lg-5">
                    Notre algorithme intelligent analyse votre profil et vous connecte avec les offres d'emploi les plus pertinentes. 
                    Plus de recherche fastidieuse, seulement des matches de qualité.
                </p>

              
                <div class="d-flex flex-column flex-sm-row gap-2 gap-sm-3 mb-3 mb-lg-4">
                    <a href="inscription.php" class="btn btn-success btn-lg px-4 py-3 fw-bold flex-fill text-center">
                        <i class="fas fa-user-plus me-2"></i>Commencer gratuitement
                    </a>
                    <a href="connexion.php" class="btn btn-light btn-lg px-4 py-3 fw-bold flex-fill text-center">
                        <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                    </a>
                </div>

               
                <div class="d-flex flex-column flex-sm-row gap-2 gap-sm-3 text-white-50">
                    <small class="text-center text-sm-start">
                        <i class="fas fa-check-circle text-success me-1"></i> Gratuit pour les candidats
                    </small>
                    <small class="text-center text-sm-start">
                        <i class="fas fa-check-circle text-success me-1"></i> +100 offres disponibles
                    </small>
                </div>
            </div>

            
            <div class="col-12 col-lg-6 order-1 order-lg-2">
                <div class="position-relative text-center">
                    <img 
                        src="https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80" 
                        alt="Personne heureuse au travail" 
                        class="img-fluid rounded-3 shadow-lg hero-image"
                        style="max-height: 300px; width: auto;"
                    >
                    <div class="position-absolute top-0 start-0 mt-2 mt-lg-3 ms-2 ms-lg-3">
                        <span class="badge bg-success fs-6 py-2 px-2 px-lg-3">
                            <i class="fas fa-bolt me-1"></i>Matches instantanés
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
/* Styles responsive pour la hero section */
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.hero-title {
    font-size: 2.2rem !important;
    line-height: 1.2;
    text-align: center;
}

.hero-text {
    font-size: 1.1rem !important;
    line-height: 1.6;
    text-align: center;
}

.hero-image {
    max-width: 100%;
    height: auto;
}

/* Media Queries pour mobile */
@media (max-width: 576px) {
    .hero-title {
        font-size: 1.8rem !important;
        margin-bottom: 1rem !important;
    }
    
    .hero-text {
        font-size: 1rem !important;
        margin-bottom: 1.5rem !important;
    }
    
    .btn-lg {
        padding: 0.8rem 1.5rem !important;
        font-size: 1rem !important;
    }
    
    .hero-section {
        padding: 2rem 0 !important;
    }
    
    .container {
        padding: 0 1rem !important;
    }
}

@media (max-width: 768px) {
    .hero-title {
        text-align: center;
    }
    
    .hero-text {
        text-align: center;
    }
    
    .d-flex.flex-column flex-sm-row {
        justify-content: center;
    }
    
    /* Optimisation de l'image sur mobile */
    .hero-image {
        max-height: 250px !important;
        margin-bottom: 1.5rem;
    }
    
    /* Badge plus petit sur mobile */
    .badge.fs-6 {
        font-size: 0.8rem !important;
        padding: 0.4rem 0.8rem !important;
    }
}

@media (min-width: 992px) {
    .hero-title {
        text-align: left;
    }
    
    .hero-text {
        text-align: left;
    }
    
    .d-flex.flex-column flex-sm-row {
        justify-content: flex-start;
    }
}

/* Amélioration de la lisibilité */
.text-white {
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.text-warning {
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

/* Animation subtile */
.hero-section {
    animation: fadeInUp 0.8s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>


<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Comment ça marche</h2>
            <p class="lead text-muted">Trouvez votre emploi idéal en 3 étapes simples</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-shadow">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-primary rounded-circle mx-auto mb-4">
                            <i class="fas fa-user-check fa-2x text-white"></i>
                        </div>
                        <h4 class="fw-bold">1. Créez votre profil</h4>
                        <p class="text-muted">Décrivez vos compétences, votre expérience et vos aspirations professionnelles.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-shadow">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-success rounded-circle mx-auto mb-4">
                            <i class="fas fa-brain fa-2x text-white"></i>
                        </div>
                        <h4 class="fw-bold">2. Nous trouvons vos matches</h4>
                        <p class="text-muted">Notre IA analyse des milliers d'offres pour trouver celles qui correspondent à votre profil.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-shadow">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-warning rounded-circle mx-auto mb-4">
                            <i class="fas fa-handshake fa-2x text-white"></i>
                        </div>
                        <h4 class="fw-bold">3. Postulez en confiance</h4>
                        <p class="text-muted">Contactez les recruteurs directement et augmentez vos chances d'embauche.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row text-center">
            <div class="col-6 col-md-3 mb-4 mb-md-0">
                <div class="display-5 fw-bold text-primary">100+</div>
                <p class="text-muted mb-0">Candidats actifs</p>
            </div>
            <div class="col-6 col-md-3 mb-4 mb-md-0">
                <div class="display-5 fw-bold text-success">50+</div>
                <p class="text-muted mb-0">Offres disponibles</p>
            </div>
            <div class="col-6 col-md-3 mb-4 mb-md-0">
                <div class="display-5 fw-bold text-warning">2o+</div>
                <p class="text-muted mb-0">Entreprises partenaires</p>
            </div>
            <div class="col-6 col-md-3">
                <div class="display-5 fw-bold text-info">85%</div>
                <p class="text-muted mb-0">Taux de satisfaction</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-5 bg-dark text-white">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Ils ont trouvé leur emploi idéal</h2>
            <p class="lead">Découvrez les succès de nos utilisateurs</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 bg-secondary border-0">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&h=100&q=80&crop=face&bg=fff&facepad=3&fit=crop" 
                             alt="Thomas" class="rounded-circle me-3" width="60" height="60">
                            <div>
                                <h5 class="fw-bold mb-0">Thomas</h5>
                                <p class="text-warning mb-0">Développeur Web</p>
                            </div>
                        </div>
                        <p class="mb-0">"JobMatch m'a permis de trouver un poste qui correspond parfaitement à mes compétences en seulement 2 semaines. L'interface est intuitive et les matches sont pertinents."</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 bg-secondary border-0">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                        <img src="https://images.unsplash.com/photo-1552058544-f2b08422138a?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&h=100&q=80&crop=face&bg=fff&facepad=3&fit=crop" 
                             alt="Sophie" class="rounded-circle me-3" width="60" height="60">
                            <div>
                                <h5 class="fw-bold mb-0">Sophie</h5>
                                <p class="text-warning mb-0">Chef de Projet</p>
                            </div>
                        </div>
                        <p class="mb-0">"Après des mois de recherche infructueuse, JobMatch a révolutionné ma recherche d'emploi. J'ai reçu des offres ciblées et j'ai été embauchée en un mois !"</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 bg-secondary border-0">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                        <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&h=100&q=80&crop=face&bg=fff&facepad=3&fit=crop" 
                             alt="Mohamed" class="rounded-circle me-3" width="60" height="60">
                            <div>
                                <h5 class="fw-bold mb-0">Mohamed</h5>
                                <p class="text-warning mb-0">Data Analyst</p>
                            </div>
                        </div>
                        <p class="mb-0">"L'algorithme de matching est impressionnant. Les offres proposées correspondaient exactement à mon profil technique et à mes aspirations salariales."</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Final CTA Section -->
<section class="py-5 bg-primary">
    <div class="container py-5 text-center">
        <h2 class="fw-bold text-white mb-3">Prêt à trouver l'emploi de vos rêves ?</h2>
        <p class="lead text-white mb-4">Rejoignez des milliers de candidats qui ont accéléré leur carrière avec JobMatch</p>
        <a href="inscription.php" class="btn btn-light btn-lg px-5 py-3 fw-bold">
            <i class="fas fa-rocket me-2"></i>Commencer maintenant
        </a>
        <p class="text-white-50 mt-3 mb-0">C'est gratuit et ça ne prend que 5 minutes</p>
    </div>
</section>

<?php include "views/layout/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
