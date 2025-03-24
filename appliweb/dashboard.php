<link rel="stylesheet" href="style.css">
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];
$role = $user['role'];

echo "<h2>Bienvenue, " . htmlspecialchars($user['email']) . " [" . $role . "]</h2>";

if ($role === 'admin') {
    echo '<a href="liste_fiches.php">Voir toutes les fiches</a><br>';
    echo '<a href="gestion_utilisateur.php">Gérer les utilisateurs</a><br>';
    echo '<a href="stats.php">Voir les statistiques</a>';
} elseif ($role === 'comptable') {
    echo '<a href="valider_fiches.php">Valider les fiches</a><br>';
} else {
    echo '<a href="creer_fiche.php">➕ Créer une fiche de frais</a><br><br>';

    // 🔎 Afficher les fiches récentes de ce visiteur
    $pdo = new PDO('mysql:host=localhost;dbname=frais_app;charset=utf8', 'root', '');
    $stmt = $pdo->prepare("SELECT * FROM fiches_frais WHERE user_id = :id ORDER BY date DESC LIMIT 5");
    $stmt->execute([':id' => $user['id']]);
    $fiches = $stmt->fetchAll();

    if ($fiches) {
        echo "<h3>📄 Vos 5 fiches les plus récentes</h3>";
        echo "<table border='1'>
                <tr>
                    <th>Date</th>
                    <th>Montant Total (€)</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>";
        foreach ($fiches as $fiche) {
            echo "<tr>
                    <td>" . htmlspecialchars($fiche['date']) . "</td>
                    <td>" . number_format($fiche['montant'], 2) . "</td>
                    <td>" . htmlspecialchars($fiche['statut']) . "</td>
                    <td><a href='voir_fiche.php?id=" . $fiche['id'] . "'>Voir</a></td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Vous n'avez encore créé aucune fiche.</p>";
    }
}
?>
