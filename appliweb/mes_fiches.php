<link rel="stylesheet" href="style.css">
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$pdo = new PDO('mysql:host=localhost;dbname=frais_app;charset=utf8', 'root', '');
$user_id = $_SESSION['user']['id'];

$fiches = $pdo->prepare("SELECT * FROM fiches_frais WHERE user_id = :uid ORDER BY date DESC");
$fiches->execute([':uid' => $user_id]);
$fiches = $fiches->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Mes fiches de frais</h2>
<a href="ajouter_fiche.php">➕ Créer une nouvelle fiche</a><br><br>
<table border="1">
<tr><th>Date</th><th>Statut</th><th>Total</th><th>Action</th></tr>
<?php foreach ($fiches as $fiche): 
    $stmt = $pdo->prepare("SELECT SUM(montant) AS total FROM details_frais WHERE fiche_id = :fid");
    $stmt->execute([':fid' => $fiche['id']]);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
?>
<tr>
    <td><?= htmlspecialchars($fiche['date']) ?></td>
    <td><?= htmlspecialchars($fiche['statut']) ?></td>
    <td><?= number_format($total, 2) ?> €</td>
    <td><a href="voir_frais.php?fiche_id=<?= $fiche['id'] ?>">Voir frais</a></td>
</tr>
<?php endforeach; ?>
</table>
