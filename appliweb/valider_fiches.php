<link rel="stylesheet" href="style.css">
<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !=='comptable'){
  header('Location: login.php');
  exit;
}

$pdo = new PDO('mysql:host=localhost;dbname=frais_app;charset=utf8', 'root', '');
$fiches = $pdo->query("SELECT f.*, u.email FROM fiches_frais f JOIN users u ON f.user_id = u.id")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Validation des fiches</h2>
<table border="1">
<tr>
    <th>ID</th><th>Type</th><th>Montant</th><th>Date</th><th>Statut</th><th>Utilisateur</th><th>Actions</th>
</tr>
<?php foreach ($fiches as $f): ?>
<tr>
    <td><?= $f['id'] ?></td>
    <td><?= htmlspecialchars($f['type']) ?></td>
    <td><?= htmlspecialchars($f['montant']) ?> €</td>
    <td><?= htmlspecialchars($f['date']) ?></td>
    <td><?= htmlspecialchars($f['statut']) ?></td>
    <td><?= htmlspecialchars($f['email']) ?></td>
    <td>
        <a href="changer_statut.php?id=<?= $f['id'] ?>&statut=accepté">✅ Accepter</a> | 
        <a href="changer_statut.php?id=<?= $f['id'] ?>&statut=refusé">❌ Refuser</a>
    </td>
</tr>
<?php endforeach; ?>
