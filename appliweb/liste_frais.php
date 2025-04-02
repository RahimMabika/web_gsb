<link rel="stylesheet" href="style.css">
<?php
$pdo = new PDO('mysql:host=localhost;dbname=frais_app;charset=utf8', 'root', '');
$frais = $pdo->query("SELECT * FROM details_frais")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Liste des frais</h2>
<table border="1">
<tr><th>ID</th><th>Catégorie</th><th>Montant</th><th>Description</th><th>Action</th></tr>
<?php foreach($frais as $f): ?>
<tr>
<td><?= $f['id'] ?></td>
<td><?= htmlspecialchars($f['categorie']) ?></td>
<td><?= htmlspecialchars($f['montant']) ?> €</td>
<td><?= htmlspecialchars($f['description']) ?></td>
<td>
    <a href="supprimer_frais.php?id=<?= $f['id'] ?>">Supprimer</a>
    <a href="modifier_frais.php?id=<?= $f['id'] ?>">Modifier</a> | 
    <a href="supprimer_frais.php?id=<?= $f['id'] ?>">Supprimer</a>

</td>
</tr>
<?php endforeach; ?>
</table>
