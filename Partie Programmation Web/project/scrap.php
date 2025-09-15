<?php
require_once __DIR__ . "config/database.php";


$BASE_URL = "https://www.emploitunisie.com/recherche-jobs-tunisie";


function extract_skills($description) {
    $skills_keywords = ["python", "java", "c++", "javascript", "sql", "html", "css",
                        "management", "marketing", "communication", "excel"];
    $found_skills = [];
    foreach ($skills_keywords as $skill) {
        if (stripos($description, $skill) !== false) {
            $found_skills[] = $skill;
        }
    }
    return implode(", ", $found_skills);
}


function scrape_jobs_list($page_url, $BASE_URL) {
    $jobs = [];
    $html = file_get_contents($page_url);
    if (!$html) return $jobs;

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    $job_cards = $xpath->query("//div[contains(@class,'card-job')]");
    foreach ($job_cards as $card) {
        $job = [];

        // Titre et lien
        $titleNode = $xpath->query(".//h3/a", $card)->item(0);
        $job['titre'] = $titleNode ? trim($titleNode->nodeValue) : null;
        $job['lien'] = $titleNode ? $BASE_URL . $titleNode->getAttribute('href') : null;

        // Entreprise
        $companyNode = $xpath->query(".//a[contains(@class,'card-job-company')]", $card)->item(0);
        $job['entreprise'] = $companyNode ? trim($companyNode->nodeValue) : null;

        // Description
        $descNode = $xpath->query(".//div[contains(@class,'card-job-description')]/p", $card)->item(0);
        $job['description'] = $descNode ? trim($descNode->nodeValue) : null;

        // Lieu et type contrat
        $liNodes = $xpath->query(".//ul/li", $card);
        $job['lieu'] = null;
        $job['type_contrat'] = null;
        foreach ($liNodes as $li) {
            $parts = explode(":", $li->nodeValue);
            if (count($parts) >= 2) {
                $key = strtolower(trim($parts[0]));
                $value = trim($parts[1]);
                if (strpos($key, "lieu") !== false) $job['lieu'] = $value;
                if (strpos($key, "contrat") !== false) $job['type_contrat'] = $value;
            }
        }

        // Date publication
        $timeNode = $xpath->query(".//time", $card)->item(0);
        $job['date_publication'] = $timeNode ? $timeNode->getAttribute('datetime') : null;

        $jobs[] = $job;
    }

    return $jobs;
}

// ----------------------------
// SCRAPER + INSERTION
// ----------------------------
$total_inserted = 0;
$max_pages = 3;

for ($page = 1; $page <= $max_pages; $page++) {
    $url = ($page > 1) ? $BASE_URL . "?page=" . ($page-1) : $BASE_URL;
    echo "Scraping page $page...\n";

    $jobs = scrape_jobs_list($url, $BASE_URL);
    foreach ($jobs as $job) {
        try {
            $stmt = $pdo->prepare("INSERT INTO jobs 
                (titre, entreprise, lieu, type_contrat, lien, description, date_publication) 
                VALUES (:titre, :entreprise, :lieu, :type_contrat, :lien, :description, :date_publication)");

            $stmt->execute([
                ':titre' => $job['titre'],
                ':entreprise' => $job['entreprise'],
                ':lieu' => $job['lieu'],
                ':type_contrat' => $job['type_contrat'],
                ':lien' => $job['lien'],
                ':description' => $job['description'],
                ':date_publication' => $job['date_publication']
            ]);
            $total_inserted++;
        } catch (Exception $e) {
            echo "Erreur insertion: " . $e->getMessage() . "\n";
        }
    }
}

echo "[SUCCESS] $total_inserted offres insérées dans MySQL ✅";
?>
