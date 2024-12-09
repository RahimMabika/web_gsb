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

if (!isset($_GET['id'])) {
    die('ID de la fiche non spécifié.');
}

$fiche_id = (int)$_GET['id'];

// Récupérer les informations de la fiche
$stmt = $pdo->prepare("SELECT * FROM fiches WHERE id = :id");
$stmt->execute([':id' => $fiche_id]);
$fiche = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fiche) {
    die('Fiche introuvable.');
}

// Récupérer les frais forfaitaires
$stmt = $pdo->prepare("SELECT * FROM frais_forfaitaire WHERE fiche_id = :fiche_id");
$stmt->execute([':fiche_id' => $fiche_id]);
$frais_forfaitaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les frais hors forfaitaires
$stmt = $pdo->prepare("SELECT * FROM frais_hors_forfaitaire WHERE fiche_id = :fiche_id");
$stmt->execute([':fiche_id' => $fiche_id]);
$frais_hors_forfaitaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Détails de la fiche de frais</title>
</head>
<body>
    <div class="container">
        <h1>Détails de la Fiche de Frais</h1>
        <h2>Informations Générales</h2>
        <p><strong>Date :</strong> <?= htmlspecialchars($fiche['date']) ?></p>
        <p><strong>Montant Total :</strong> <?= htmlspecialchars($fiche['montant']) ?> €</p>
        <p><strong>Description :</strong> <?= htmlspecialchars($fiche['description']) ?></p>

        <h2>Frais Forfaitaires</h2>
        <?php if (count($frais_forfaitaires) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Libellé</th>
                        <th>Montant</th>
                        <th>Quantité</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($frais_forfaitaires as $forfait): ?>
                        <tr>
                            <td><?= htmlspecialchars($forfait['libelle']) ?></td>
                            <td><?= htmlspecialchars($forfait['montant']) ?> €</td>
                            <td><?= htmlspecialchars($forfait['quantite']) ?></td>
                            <td><?= htmlspecialchars($forfait['montant'] * $forfait['quantite']) ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun frais forfaitaire enregistré.</p>
        <?php endif; ?>

        <h2>Frais Hors Forfaitaires</h2>
        <?php if (count($frais_hors_forfaitaires) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Libellé</th>
                        <th>Montant</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($frais_hors_forfaitaires as $hors_forfait): ?>
                        <tr>
                            <td><?= htmlspecialchars($hors_forfait['libelle']) ?></td>
                            <td><?= htmlspecialchars($hors_forfait['montant']) ?> €</td>
                            <td><?= htmlspecialchars($hors_forfait['description']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun frais hors forfaitaire enregistré.</p>
        <?php endif; ?>
    </div>
</body>
</html>
