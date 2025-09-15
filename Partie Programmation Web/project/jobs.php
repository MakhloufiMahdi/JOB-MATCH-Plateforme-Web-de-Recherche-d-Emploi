<?php
session_start();
require_once "config/database.php";
if(!isset($_SESSION['user_email'])) header("Location: connexion.php");


$search = $_GET['q'] ?? '';
$type_contrat = $_GET['type_contrat'] ?? '';


$sql = "SELECT * FROM jobs WHERE 1";
$params = [];

if($search) {
    $sql .= " AND (titre LIKE :search OR description LIKE :search)";
    $params[':search'] = "%$search%";
}
if($type_contrat) {
    $sql .= " AND type_contrat = :type_contrat";
    $params[':type_contrat'] = $type_contrat;
}
$sql .= " ORDER BY date_publication DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$jobs = $stmt->fetchAll();
?>

<?php include "views/layout/header.php"; ?>

<h2 class="mb-4">Offres d'emploi</h2>

<form method="GET" class="mb-4">
    <div class="row g-2">
        <div class="col-md-6">
            <input type="text" class="form-control" name="q" placeholder="Mots-clés..." value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-md-4">
            <select name="type_contrat" class="form-select">
                <option value="">-- Type de contrat --</option>
                <?php
                $types = ['CDI','CDD','Freelance','Stage','Temps partiel'];
                foreach($types as $t) {
                    $selected = ($type_contrat==$t) ? "selected" : "";
                    echo "<option value='$t' $selected>$t</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filtrer</button>
        </div>
    </div>
</form>

<div class="row">
<?php if(empty($jobs)): ?>
    <p>Aucune offre trouvée.</p>
<?php else: ?>
    <?php foreach($jobs as $job): ?>
        <div class="col-md-4 mb-3">
            <div class="card card-job h-100 p-3">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($job['titre']) ?></h5>
                    <p class="text-muted"><?= htmlspecialchars($job['entreprise']) ?> | <?= htmlspecialchars($job['type_contrat']) ?></p>
                    <p class="card-text"><?= substr(htmlspecialchars($job['description']),0,120) ?>...</p>
                    <a href="show.php?id=<?= $job['id'] ?>" class="btn btn-primary">Voir détails</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
</div>

<?php include "views/layout/footer.php"; ?>
