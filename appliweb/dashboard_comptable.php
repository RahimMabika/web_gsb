<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$pdo = new PDO('mysql:host=localhost;dbname=frais_app;charset=utf8', 'root', '');

// 🔢 STATISTIQUES GLOBALES
$total_fiches = $pdo->query("SELECT COUNT(*) FROM fiches_frais")->fetchColumn();
$total_utilisateurs = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'visiteur'")->fetchColumn();

$statuts = $pdo->query("SELECT statut, COUNT(*) as count FROM fiches_frais GROUP BY statut")->fetchAll(PDO::FETCH_ASSOC);
$labels = []; $data = [];
foreach ($statuts as $s) {
    $labels[] = $s['statut'];
    $data[] = $s['count'];
}

// 👥 Récupérer tous les utilisateurs
$users = $pdo->query("SELECT id, email, role FROM users")->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="style.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container">
<h2>Dashboard Admin</h2>
<p><strong>Total fiches :</strong> <?= $total_fiches ?> | <strong>Visiteurs :</strong> <?= $total_utilisateurs ?></p>

<h3>📊 Répartition des fiches par statut</h3>
<canvas id="chartStatut" width="400" height="400"></canvas>
<script>
const ctx = document.getElementById('chartStatut').getContext('2d');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            data: <?= json_encode($data) ?>,
            backgroundColor: ['#3498db', '#2ecc71', '#e74c3c']
        }]
    },
    options: { responsive: true }
});
</script>

<h3>👥 Gestion des utilisateurs</h3>
<table>
    <tr><th>Email</th><th>Rôle</th><th>Action</th></tr>
    <?php foreach ($users as $u): ?>
    <tr>
        <td><?= htmlspecialchars($u['email']) ?></td>
        <td><?= htmlspecialchars($u['role']) ?></td>
        <td><a class="button" href="modifier_user.php?id=<?= $u['id'] ?>">Modifier</a></td>
    </tr>
    <?php endforeach; ?>
</table>
</div>
