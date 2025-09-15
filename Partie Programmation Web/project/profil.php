<?php
session_start();
require_once "config/database.php";
if(!isset($_SESSION['user_id'])) header("Location: connexion.php");

$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();


$stmt = $pdo->prepare("SELECT COUNT(*) FROM jobs WHERE description LIKE ?");
$stmt->execute(['%'.$user['specialite'].'%']);
$matching = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM jobs");
$total_jobs = $stmt->fetchColumn();
$score = ($total_jobs>0)? round(($matching/$total_jobs)*100) : 0;
?>

<?php include "views/layout/header.php"; ?>

<div class="container mt-5">
    <div class="card shadow-sm border-0 profile-card p-4">
        <h2 class="mb-3 text-primary">Profil de <?= htmlspecialchars($user['prenom']) ?></h2>

        <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Spécialité :</strong> <?= htmlspecialchars($user['specialite']) ?></p>

        <p><strong>Matching avec les offres :</strong></p>
        <div class="progress mb-3" style="height:25px; border-radius:12px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
                 style="width: <?= $score ?>%; background: linear-gradient(90deg,#ec4899,#3b82f6);" 
                 aria-valuenow="<?= $score ?>" aria-valuemin="0" aria-valuemax="100">
                <?= $score ?>%
            </div>
        </div>

        <a href="jobs.php" class="btn btn-gradient btn-lg mt-3">Voir offres correspondantes</a>
    </div>
</div>

<style>
.profile-card {
    border-radius: 12px;
    transition: transform 0.2s, box-shadow 0.2s;
}
.profile-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.btn-gradient {
    background: linear-gradient(90deg, #ec4899, #3b82f6);
    color: #fff;
    border: none;
    font-weight: 500;
    transition: transform 0.2s, box-shadow 0.2s;
}
.btn-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    color: #fff;
}
</style>

<?php include "views/layout/footer.php"; ?>
