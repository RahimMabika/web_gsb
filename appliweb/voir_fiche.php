<link rel="stylesheet" href="style.css">
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    die("❌ Fiche non spécifiée.");
}

$pdo = new PDO('mysql:host=localhost;dbname=frais_app;charset=utf8', 'root', '');
$fiche_id = $_GET['id'];

// 🔍 Vérif fiche + info
$fiche_stmt = $pdo->prepare("SELECT * FROM fiches_frais WHERE id = :id");
$fiche_stmt->execute([':id' => $fiche_id]);
$fiche = $fiche_stmt->fetch(PDO::FETCH_ASSOC);

if (!$fiche) {
    die("❌ Fiche introuvable.");
}

// 🔍 Récupération frais par catégorie
$frais_stmt = $pdo->prepare("SELECT * FROM details_frais WHERE fiche_id = :fiche_id ORDER BY date ASC");
$frais_stmt->execute([':fiche_id' => $fiche_id]);
$frais = $frais_stmt->fetchAll(PDO::FETCH_ASSOC);

// Totaux par catégorie
$totaux = ['repas' => 0, 'hébergement' => 0, 'transport' => 0, 'autres' => 0];
$total_global = 0;
?>

<h2>📄 Détail de la fiche - Date : <?= htmlspecialchars($fiche['date']) ?></h2>
<p>Statut : <strong><?= htmlspecialchars($fiche['statut']) ?></strong></p>

<table border="1" cellpadding="5">
    <tr>
        <th>Date</th>
        <th>Description</th>
        <th>Transport (€)</th>
        <th>Repas (€)</th>
        <th>Hébergement (€)</th>
        <th>Autres (€)</th>
        <th>Sous-total (€)</th>
    </tr>

    <?php foreach ($frais as $f) :
        $transport = $f['categorie'] === 'transport' ? $f['montant'] : 0;
        $repas = $f['categorie'] === 'repas' ? $f['montant'] : 0;
        $hebergement = $f['categorie'] === 'hébergement' ? $f['montant'] : 0;
        $autres = $f['categorie'] === 'autres' ? $f['montant'] : 0;
        $sous_total = $transport + $repas + $hebergement + $autres;

        $totaux['transport'] += $transport;
        $totaux['repas'] += $repas;
        $totaux['hébergement'] += $hebergement;
        $totaux['autres'] += $autres;
        $total_global += $sous_total;
    ?>
    <tr>
        <td><?= htmlspecialchars($f['date']) ?></td>
        <td><?= htmlspecialchars($f['description']) ?></td>
        <td><?= number_format($transport, 2) ?></td>
        <td><?= number_format($repas, 2) ?></td>
        <td><?= number_format($hebergement, 2) ?></td>
        <td><?= number_format($autres, 2) ?></td>
        <td><?= number_format($sous_total, 2) ?></td>
    </tr>
    <?php endforeach; ?>

    <!-- Lignes Totaux -->
    <tr>
        <th colspan="2">TOTAUX</th>
        <th><?= number_format($totaux['transport'], 2) ?></th>
        <th><?= number_format($totaux['repas'], 2) ?></th>
        <th><?= number_format($totaux['hébergement'], 2) ?></th>
        <th><?= number_format($totaux['autres'], 2) ?></th>
        <th><?= number_format($total_global, 2) ?></th>
    </tr>
</table>

<br>
<a href="dashboard.php">⬅️ Retour au tableau de bord</a>
