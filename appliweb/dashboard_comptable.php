<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'comptable') {
    header('Location: login.php');
    exit;
}

$pdo = new PDO('mysql:host=localhost;dbname=frais_app;charset=utf8', 'root', '');


$filtre = $_GET['filtre'] ?? 'toutes'; // récupere le filtre 

if ($filtre === 'en_attente') {
    $stmt = $pdo->prepare("
        SELECT f.id, f.date, f.montant, f.statut, u.email 
        FROM fiches_frais f
        JOIN users u ON f.user_id = u.id
        WHERE f.statut = 'en attente'
        ORDER BY f.date DESC
    ");
} else {
    $stmt = $pdo->prepare("
        SELECT f.id, f.date, f.montant, f.statut, u.email 
        FROM fiches_frais f
        JOIN users u ON f.user_id = u.id
        ORDER BY f.date DESC
    ");
}

$stmt->execute();
$fiches = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="style.css">
<div class="container">
    <h2> Tableau de bord Comptable</h2>
    <a class="button" href="logout.php"> Déconnexion</a>
    
    <h3> Filtres :</h3>
    <a class="button" href="dashboard_comptable.php?filtre=toutes"> Toutes les fiches</a>
    <a class="button" href="dashboard_comptable.php?filtre=en_attente"> Fiches en attente</a>

    <h3> Liste des fiches</h3>
    <table>
        <tr>
            <th>Utilisateur</th>
            <th>Date</th>
            <th>Montant (€)</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($fiches as $f): ?>
        <tr>
            <td><?= htmlspecialchars($f['email']) ?></td>
            <td><?= htmlspecialchars($f['date']) ?></td>
            <td><?= number_format($f['montant'], 2) ?></td>
            <td><?= htmlspecialchars($f['statut']) ?></td>
            <td>
                <a class="button" href="voir_fiche_comptable.php?id=<?= $f['id'] ?>"> Voir</a>
                <?php if ($f['statut'] === 'en attente'): ?>
                    <a class="button" href="changer_statut.php?id=<?= $f['id'] ?>&statut=accepté">✅ Accepter</a>
                    <a class="button" href="changer_statut.php?id=<?= $f['id'] ?>&statut=refusé">❌ Refuser</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
