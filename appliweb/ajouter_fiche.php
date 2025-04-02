<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$pdo = new PDO('mysql:host=localhost;dbname=frais_app;charset=utf8', 'root', '');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO fiches_frais (user_id, date, statut) VALUES (:user_id, :date, :statut)");
    $stmt->execute([
        ':user_id' => $_SESSION['user']['id'],
        ':date' => $_POST['date'],
        ':statut' => 'en attente'
    ]);
    $fiche_id = $pdo->lastInsertId();
    header("Location: ajouter_frais.php?fiche_id=$fiche_id");
    exit;
}
?>

<h2>Créer une nouvelle fiche de frais</h2>
<form method="POST">
Date de la fiche : <input type="date" name="date" required><br>
<input type="submit" value="Créer la fiche">
</form>
