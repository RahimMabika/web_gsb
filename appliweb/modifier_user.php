<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$pdo = new PDO('mysql:host=localhost;dbname=frais_app;charset=utf8', 'root', '');
$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID manquant");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE users SET email = :email, password = :pass WHERE id = :id");
    $stmt->execute([':email' => $email, ':pass' => $password, ':id' => $id]);

    header('Location: dashboard_admin.php');
    exit;
}

// User à modifier
$stmt = $pdo->prepare("SELECT email FROM users WHERE id = :id");
$stmt->execute([':id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="style.css">
<div class="container">
<h2>Modifier Utilisateur</h2>
<form method="POST">
Email : <input type="text" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br>
Nouveau mot de passe : <input type="password" name="password" required><br>
<input type="submit" value="Enregistrer">
</form>
</div>
