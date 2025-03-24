<link rel="stylesheet" href="style.css">
<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$pdo = new PDO('mysql:host=localhost;dbname=frais_app;charset=utf8', 'root', '');

// Requête 1 : Total des fiches
$totalFiches = $pdo->query("SELECT COUNT(*) AS total FROM fiches_frais")->fetch(PDO::FETCH_ASSOC)['total'];

// Requête 2 : Répartition par statut
$statuts = $pdo->query("SELECT statut, COUNT(*) AS count FROM fiches_frais GROUP BY statut")->fetchAll(PDO::FETCH_ASSOC);

// Requête 3 : Nombre de fiches par utilisateur
$utilisateurs = $pdo->query("
    SELECT u.email, COUNT(f.id) AS nb_fiches 
    FROM users u 
    LEFT JOIN fiches_frais f ON u.id = f.user_id 
    GROUP BY u.id
")->fetchAll(PDO::FETCH_ASSOC);
?>
<?php
// Préparer les données pour le graphique
$labels = [];
$data = [];

foreach ($statuts as $s) {
    $labels[] = $s['statut'];
    $data[] = $s['count'];
}
?>


<h2>Dashboard Admin</h2>
<p>Total de fiches de frais : <strong><?= $totalFiches ?></strong></p>

<h3>Répartition par statut :</h3>
<ul>
    <?php foreach($statuts as $s): ?>
        <li><?= htmlspecialchars($s['statut']) ?> : <?= $s['count'] ?></li>
    <?php endforeach; ?>
</ul>
<canvas id="statutChart" width="400" height="400"></canvas>
<script>
const ctx = document.getElementById('statutChart').getContext('2d');
const statutChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Répartition des fiches',
            data: <?= json_encode($data) ?>,
            backgroundColor: ['#36A2EB', '#4CAF50', '#FF6384'], // Bleu, Vert, Rouge
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            },
        },
    }
});
</script>

<h3>Fiches par utilisateur :</h3>
<table border="1">
<tr><th>Email</th><th>Nombre de fiches</th></tr>
<?php foreach($utilisateurs as $u): ?>
    <tr>
        <td><?= htmlspecialchars($u['email']) ?></td>
        <td><?= $u['nb_fiches'] ?></td>
    </tr>
<?php endforeach; ?>
</table>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

