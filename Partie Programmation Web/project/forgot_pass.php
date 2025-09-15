<?php
session_start();
require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = $_POST['email']; 

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=? OR telephone=?");
    $stmt->execute([$input, $input]);
    $user = $stmt->fetch();

    if ($user) {
        
        $token = bin2hex(random_bytes(16));
        $expire = date("Y-m-d H:i:s", strtotime("+15 minutes"));

        $update = $pdo->prepare("UPDATE users SET reset_token=?, reset_expire=? WHERE id=?");
        $update->execute([$token, $expire, $user['id']]);

        $_SESSION['reset_email'] = $user['email'];
        $_SESSION['reset_token'] = $token;

        
        header("Location: reset_pass.php");
        exit();
    } else {
        $error = "Utilisateur introuvable avec cet email ou téléphone.";
    }
}
include "views/layout/header.php";
?>

<div class="container mt-5">
    <h2>Mot de passe oublié</h2>
    <?php if(!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST">
        <input type="text" name="email" class="form-control mb-3" placeholder="Email ou Téléphone" required>
        <button type="submit" class="btn btn-warning w-100">Envoyer le lien de réinitialisation</button>
    </form>
</div>

<?php include "views/layout/footer.php"; ?>
