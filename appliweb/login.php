<link rel="stylesheet" href="style.css">
<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = new PDO('mysql:host=localhost;dbname=frais_app;charset=utf8', 'root', '');
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $_POST['email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && hash('sha256', $_POST['password']) === $user['password_hash']) {
        $_SESSION['user'] = $user;
        header('Location: dashboard.php');
        exit;
    } else {
        echo "❌ Email ou mot de passe incorrect";
    }
}
?>

<h2>Connexion</h2>
<form method="POST">
Email: <input name="email" type="email"><br>
Mot de passe: <input name="password" type="password"><br>
<input type="submit" value="Connexion">
</form>
