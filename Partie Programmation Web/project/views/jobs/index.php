<?php include "views/layout/header.php"; ?>

<h1 class="mb-4">Offres d'emploi</h1>

<form method="GET" class="mb-4">
    <input type="hidden" name="controller" value="jobs">
    <input type="hidden" name="action" value="index">
    <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Rechercher par titre ou entreprise" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        <button class="btn btn-primary" type="submit">Rechercher</button>
    </div>
</form>

<div class="row">
    <?php if(empty($jobs)): ?>
        <p>Aucune offre trouvée.</p>
    <?php else: ?>
        <?php foreach($jobs as $job): ?>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($job['titre']) ?></h5>
                        <p class="card-text"><?= substr(htmlspecialchars($job['description']), 0, 100) ?>...</p>
                        <a href="index.php?controller=jobs&action=show&id=<?= $job['id'] ?>" class="btn btn-primary">Voir détails</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include "views/layout/footer.php"; ?>
