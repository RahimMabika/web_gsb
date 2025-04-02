<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=frais_app;charset=utf8', 'root', '');


$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && isset($user['password_hash']) && password_verify($password, $user['password_hash'])) {
        $_SESSION['user'] = $user;
        header("Location: dashboard.php"); 
        exit;
    } else {
        $error = "❌ Identifiants invalides.";
    }
}
?>

<link rel="stylesheet" href="style.css">
<div class="container">
    <h2>Connexion</h2>
    <?php if ($error): ?><p style="color:red;"><?= $error ?></p><?php endif; ?>
    <form method="POST">
        <label>Email :</label>
        <input type="text" name="email" required><br>
        <label>Mot de passe :</label>
        <input type="password" name="password" required><br>
        <input type="submit" value="Se connecter">
    </form>
</div>
