<?php
session_start();
require_once "config/database.php";
include "views/layout/header.php";


$stmt = $pdo->query("SELECT titre, COUNT(*) as total FROM jobs GROUP BY titre ORDER BY total DESC LIMIT 5");
$top_jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);


$stmt = $pdo->query("SELECT entreprise, COUNT(*) as total FROM jobs GROUP BY entreprise ORDER BY total DESC LIMIT 5");
$top_companies = $stmt->fetchAll(PDO::FETCH_ASSOC);


$stmt = $pdo->query("SELECT description FROM jobs");
$all_desc = $stmt->fetchAll(PDO::FETCH_COLUMN);
$keywords = ["Python", "Java", "SQL", "PHP", "HTML", "CSS", "JavaScript", "Excel", "Management"];
$skills_count = [];
foreach ($all_desc as $desc) {
    foreach ($keywords as $kw) {
        if (stripos($desc, $kw) !== false) {
            $skills_count[$kw] = ($skills_count[$kw] ?? 0) + 1;
        }
    }
}
arsort($skills_count);
?>

<div class="container mt-5">

    <h2 class="mb-4 text-center">Offres Tendance</h2>

    <div class="row mb-5">
        <div class="col-md-6 mb-4">
            <h4 class="mb-3">Top 5 Métiers</h4>
            <div class="card shadow-sm border-0">
                <ul class="list-group list-group-flush">
                    <?php foreach($top_jobs as $job): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><?= htmlspecialchars($job['titre']) ?></span>
                        <span class="badge rounded-pill text-white" style="background: linear-gradient(90deg,#ec4899,#3b82f6)">
                            <?= $job['total'] ?> offres
                        </span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <h4 class="mb-3">🏢 Entreprises qui recrutent le plus</h4>
            <div class="card shadow-sm border-0">
                <ul class="list-group list-group-flush">
                    <?php foreach($top_companies as $company): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><?= htmlspecialchars($company['entreprise']) ?></span>
                        <span class="badge rounded-pill text-white" style="background: linear-gradient(90deg,#22c55e,#3b82f6)">
                            <?= $company['total'] ?> offres
                        </span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 p-4">
        <h4 class="mb-3">📊 Compétences tendances</h4>
        <canvas id="skillsChart" height="150"></canvas>
    </div>

</div>

<style>
    h2, h4 {
        font-weight: 600;
        color: #3b82f6;
    }
    .list-group-item {
        font-weight: 500;
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: default;
    }
    .list-group-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .badge {
        font-size: 0.9rem;
        padding: 0.5em 0.8em;
    }
    .card {
        border-radius: 12px;
    }
</style>

<script>
const ctx = document.getElementById('skillsChart').getContext('2d');
const skillsChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_keys($skills_count)) ?>,
        datasets: [{
            label: 'Nombre d’offres',
            data: <?= json_encode(array_values($skills_count)) ?>,
            backgroundColor: [
                'rgba(107,33,168,0.7)',
                'rgba(236,72,153,0.7)',
                'rgba(59,130,246,0.7)',
                'rgba(34,197,94,0.7)',
                'rgba(234,179,8,0.7)',
                'rgba(249,115,22,0.7)',
                'rgba(139,92,246,0.7)',
                'rgba(6,182,212,0.7)',
                'rgba(251,191,36,0.7)'
            ],
            borderColor: 'rgba(107,33,168,1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true },
            x: { ticks: { color: '#333', font: { weight: 500 } } }
        }
    }
});
</script>

<?php include "views/layout/footer.php"; ?>
