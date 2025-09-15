<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- Favicon -->
<link rel="icon" href="/assets/images/favicon.png" type="image/x-icon">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons & Font Awesome -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Custom CSS -->
<link rel="stylesheet" href="/assets/css/style.css">

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #ec4899 0%, #6b21a8 50%, #3b82f6 100%);
        --accent-color: #22c55e;
    }

    body {
        padding-top: 120px;
        font-family: 'Segoe UI', 'Poppins', sans-serif;
        line-height: 1.7;
        min-height: 100vh;
        margin: 0;
    }

    /* Navbar */
    .navbar {
        background: rgba(107, 33, 168, 0.92);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        padding: 0.8rem 1rem;
        transition: all 0.4s ease;
    }

    .navbar.scrolled {
        padding: 0.5rem 1rem;
        background: rgba(107, 33, 168, 0.98);
    }

    .navbar-brand {
        font-weight: 700;
        font-size: 1.8rem;
        color: #fff !important;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        position: relative;
    }

    .navbar-brand img {
        height: 40px;
        margin-right: 10px;
    }

    .navbar-brand::after {
        content: "";
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 30px;
        height: 3px;
        background: var(--accent-color);
        border-radius: 2px;
        transition: width 0.3s ease;
    }

    .navbar-brand:hover::after {
        width: 100%;
    }

    .nav-link {
        color: #fff !important;
        font-weight: 500;
        margin: 0 0.8rem;
        transition: all 0.3s ease;
        position: relative;
        padding: 0.5rem 0.8rem !important;
        border-radius: 8px;
    }

    .nav-link:hover {
        color: #fff !important;
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-2px);
    }

    .nav-link::before {
        content: "";
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 2px;
        background: var(--accent-color);
        transition: width 0.3s ease;
    }

    .nav-link:hover::before {
        width: 80%;
    }

    @media (max-width: 738px) {
        body {
            padding-top: 40px;
        }
        .navbar-brand {
            font-size: 1.5rem;
        }
    }
</style>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container-fluid">
      <a class="navbar-brand" href="/index.php">
         <i class="bi bi-briefcase-fill me-2"></i> JobMatching
      </a>


    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
            data-bs-target="#navbarNav" aria-controls="navbarNav" 
            aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link" href="/index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="/jobs.php">Offres</a></li>
            <li class="nav-item"><a class="nav-link" href="/tendances.php">Tendances</a></li>
            <li class="nav-item"><a class="nav-link" href="/recommandations.php">Recommandations</a></li>
            <li class="nav-item"><a class="nav-link" href="/profil.php">Profil</a></li>
        </ul>

        
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/logout.php" class="btn btn-outline-light ms-2 rounded-pill px-3"> Se déconnecter</a>
        <?php else: ?>
            <a href="/connexion.php" class="btn btn-outline-light me-2 rounded-pill px-3">Se connecter</a>
            <a href="/inscription.php" class="btn btn-success rounded-pill px-3">S'inscrire</a>
        <?php endif; ?>
    </div>
  </div>
</nav>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Navbar change au scroll
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
</script>
