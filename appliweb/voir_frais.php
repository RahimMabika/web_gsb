<link rel="stylesheet" href="style.css">
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$pdo = new PDO('mysql:host=localhost;dbname=frais_app;charset=utf8', 'root', '');
$fiche_id = $_GET['fiche_id'];

$frais = $pdo->prepare("SELECT * FROM details_frais WHERE fiche_id = :fid");
$frais->execute([':fid' => $fiche_id]);
$frais = $frais->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Frais pour la fiche #<?= htmlspecialchars($fiche_id) ?></h2>
<table border="1">
<tr><th>Catégorie</th><th>Description</th><th>Montant (€)</th></tr>
<?php foreach ($frais as $f): ?>
<tr>
    <td><?= htmlspecialchars($f['categorie']) ?></td>
    <td><?= htmlspecialchars($f['description']) ?></td>
    <td><?= number_format($f['montant'], 2) ?></td>
</tr>
<?php endforeach; ?>
</table>
<a href="ajouter_frais.php?fiche_id=<?= $fiche_id ?>">➕ Ajouter un frais</a>
