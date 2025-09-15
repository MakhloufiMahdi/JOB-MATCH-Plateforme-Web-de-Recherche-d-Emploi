<?php
session_start();
require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([":email" => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['mot_de_passe'])) {
       
        $_SESSION['user_id'] = $user['id'];       
        $_SESSION['user_email'] = $user['email']; 
        $_SESSION['user_nom'] = $user['nom'];     
        
        
        header("Location: jobs.php");
        exit();
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include "views/layout/header.php"; ?>

<div class="container">
    <h2 class="my-4">Connexion</h2>
    <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST" class="row g-3">
        <div class="col-md-6">
            <input type="email" class="form-control" name="email" placeholder="Email" required>
        </div>
        <div class="col-md-6">
            <input type="password" class="form-control" name="password" placeholder="Mot de passe" required>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </div>
        <div class="col-12 text-center mt-2">
            <a href="forgot_pass.php">Mot de passe oublié ?</a>
        </div>
    </form>
</div>
</body>
</html>
