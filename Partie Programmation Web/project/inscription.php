<?php
session_start();
require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $telephone = $_POST['telephone'];
    $adresse = $_POST['adresse'];
    $date_naissance = $_POST['date_naissance'];
    $email = $_POST['email'];
    $classe = $_POST['classe'];
    $specialite = $_POST['specialite'];
    $statut_actuel = $_POST['statut_actuel'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $confirm_password = $_POST['confirm_password'];

    
    if ($mot_de_passe !== $confirm_password) {
        $error = "⚠️ Les mots de passe ne correspondent pas.";
    } else {
        $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, telephone, adresse, date_naissance, email, classe, specialite, statut_actuel, mot_de_passe) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        try {
            $stmt->execute([$nom, $prenom, $telephone, $adresse, $date_naissance, $email, $classe, $specialite, $statut_actuel, $hashed_password]);
            $_SESSION['user_id'] = $pdo->lastInsertId();
            header("Location: jobs.php");
            exit();
        } catch (PDOException $e) {
            $error = "Erreur: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include "views/layout/header.php"; ?>

<div class="container">
    <h2 class="my-4">Inscription</h2>
    <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST" class="row g-3">

        <div class="col-md-6"><input type="text" name="nom" class="form-control" placeholder="Nom" required></div>
        <div class="col-md-6"><input type="text" name="prenom" class="form-control" placeholder="Prénom" required></div>
        <div class="col-md-6"><input type="text" name="telephone" class="form-control" placeholder="Téléphone" required></div>
        <div class="col-md-6"><input type="text" name="adresse" class="form-control" placeholder="Adresse" required></div>
        <div class="col-md-6"><input type="date" name="date_naissance" class="form-control" required></div>
        <div class="col-md-6"><input type="email" name="email" class="form-control" placeholder="Email" required></div>

        <!-- Classe -->
        <div class="col-md-6">
            <select name="classe" class="form-select" required>
                <option value="">-- Classe --</option>
                <option value="secondaire">Secondaire</option>
                <option value="licence">Licence</option>
                <option value="master">Master</option>
                <option value="ingenieur">Ingénieur</option>
                <option value="employe">Employé</option>
                <option value="enseignant">Enseignant</option>
                <option value="doctorat">Doctorat</option>
                <option value="autre">Autre</option>
            </select>
        </div>

        <!-- Spécialité -->
        <div class="col-md-6"><input type="text" name="specialite" class="form-control" placeholder="Spécialité" required></div>

        <!-- Statut actuel -->
        <div class="col-md-6">
            <select name="statut_actuel" class="form-select" required>
                <option value="">-- Statut actuel --</option>
                <option value="etudiant">Étudiant</option>
                <option value="employe">Employé</option>
                <option value="demandeur">Demandeur d’emploi</option>
                <option value="freelance">Freelance</option>
                <option value="formateur">Formateur</option>
                <option value="enseignant">Enseignant</option>
                <option value="autre">Autre</option>
            </select>
        </div>

        <!-- Mot de passe + confirmation -->
        <div class="col-md-6"><input type="password" name="mot_de_passe" class="form-control" placeholder="Mot de passe" required></div>
        <div class="col-md-6"><input type="password" name="confirm_password" class="form-control" placeholder="Confirmer le mot de passe" required></div>

        <div class="col-12"><button type="submit" class="btn btn-success w-100">S'inscrire</button></div>
    </form>
</div>
</body>
</html>
