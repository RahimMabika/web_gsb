<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'comptable') {
    header('Location: login.php');
    exit;
}

$pdo = new PDO('mysql:host=localhost;dbname=frais_app;charset=utf8', 'root', '');

$fiche_id = $_GET['id'] ?? null;
if (!$fiche_id) die("Fiche non spécifiée");

$fiche_stmt = $pdo->prepare("SELECT f.*, u.email FROM fiches_frais f JOIN users u ON f.user_id = u.id WHERE f.id = :id");
$fiche_stmt->execute([':id' => $fiche_id]);
$fiche = $fiche_stmt->fetch(PDO::FETCH_ASSOC);
if (!$fiche) die("Fiche introuvable");

$frais_stmt = $pdo->prepare("SELECT * FROM details_frais WHERE fiche_id = :id ORDER BY date");
$frais_stmt->execute([':id' => $fiche_id]);
$frais = $frais_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="style.css">
<div class="container">
<h2> Fiche #<?= $fiche['id'] ?> - <?= htmlspecialchars($fiche['email']) ?></h2>
<p><strong>Date :</strong> <?= $fiche['date'] ?> | <strong>Montant :</strong> <?= $fiche['montant'] ?>€ | <strong>Statut :</strong> <?= $fiche['statut'] ?></p>

<h3>Détails des frais</h3>
<table>
<tr><th>Date</th><th>Catégorie</th><th>Description</th><th>Montant (€)</th></tr>
<?php foreach ($frais as $f): ?>
<tr>
<td><?= $f['date'] ?></td>
<td><?= $f['categorie'] ?></td>
<td><?= $f['description'] ?></td>
<td><?= $f['montant'] ?></td>
</tr>
<?php endforeach; ?>
</table>

<?php if ($fiche['statut'] === 'en attente'): ?>
<a class="button" href="changer_statut.php?id=<?= $fiche['id'] ?>&statut=accepté">✅ Accepter</a>
<a class="button" href="changer_statut.php?id=<?= $fiche['id'] ?>&statut=refusé">❌ Refuser</a>
<?php endif; ?>
<br><a href="dashboard_comptable.php">⬅ Retour</a>
</div>
