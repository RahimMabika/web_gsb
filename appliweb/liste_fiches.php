<link rel="stylesheet" href="style.css">
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$pdo = new PDO('mysql:host=localhost;dbname=frais_app;charset=utf8', 'root', '');

$user = $_SESSION['user'];
$role = $user['role'];

// Si ADMIN ou COMPTABLE → voir toutes les fiches
if ($role === 'admin' || $role === 'comptable') {
    $stmt = $pdo->query("SELECT f.*, u.email FROM fiches_frais f JOIN users u ON f.user_id = u.id");
    $fiches = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Si VISITEUR → voir SEULEMENT ses fiches
    $stmt = $pdo->prepare("SELECT * FROM fiches_frais WHERE user_id = :id");
    $stmt->execute([':id' => $user['id']]);
    $fiches = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<h2>Liste des fiches</h2>
<table border="1">
<tr>
    <th>ID</th>
    <th>Type</th>
    <th>Montant</th>
    <th>Date</th>
    <th>Statut</th>
    <?php if ($role === 'admin' || $role === 'comptable'): ?>
        <th>Utilisateur</th>
    <?php endif; ?>
</tr>

<?php foreach ($fiches as $f): ?>
<tr>
    <td><?= $f['id'] ?></td>
    <td><?= htmlspecialchars($f['type']) ?></td>
    <td><?= htmlspecialchars($f['montant']) ?> €</td>
    <td><?= htmlspecialchars($f['date']) ?></td>
    <td><?= htmlspecialchars($f['statut']) ?></td>
    <?php if ($role === 'admin' || $role === 'comptable'): ?>
        <td><?= htmlspecialchars($f['email']) ?></td>
    <?php endif; ?>
</tr>
<?php endforeach; ?>
</table>
