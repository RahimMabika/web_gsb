<?php
session_start();
$dsn = 'mysql:host=localhost;dbname=gsb;charset=utf8mb4';
$user = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

// Récupération de l'utilisateur connecté (pour l'exemple)
$utilisateur = $_SESSION['utilisateur'] ?? 'Anonyme';

// Récupérer les fiches de frais du visiteur
$stmt = $pdo->prepare("SELECT * FROM fiches WHERE utilisateur = :utilisateur ORDER BY date DESC");
$stmt->execute([':utilisateur' => $utilisateur]);
$fiches = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Mes fiches de frais</title>
</head>
<body>
    <div class="container">
        <h1>Mes Fiches de Frais</h1>
        <?php if (count($fiches) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Montant Total</th>
                        <th>Description</th>
                        <th>Détails</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($fiches as $fiche): ?>
                        <tr>
                            <td><?= htmlspecialchars($fiche['date']) ?></td>
                            <td><?= htmlspecialchars($fiche['montant']) ?> €</td>
                            <td><?= htmlspecialchars($fiche['description']) ?></td>
                            <td><a href="fiche_detail.php?id=<?= $fiche['id'] ?>">Voir Détails</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune fiche de frais trouvée.</p>
        <?php endif; ?>
    </div>
</body>
</html>
