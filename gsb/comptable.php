<?php
session_start();
echo "Bienvenue Comptable";
$dsn = 'mysql:host=localhost;dbname=gsb;charset=utf8mb4';
$user = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $user, $password);
    echo "Connexion réussie !";
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());}
?>

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Interface Comptable</title>
</head>
<body>
    <header>
        <h1>Interface Comptable - Validation des Fiches de Frais</h1>
    </header>
    <div class="container">
        <?php if (!empty($fiches)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Utilisateur</th>
                        <th>Montant Total</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($fiches as $fiche): ?>
                        <tr>
                            <td><?= htmlspecialchars($fiche['date']) ?></td>
                            <td><?= htmlspecialchars($fiche['utilisateur']) ?></td>
                            <td><?= number_format($fiche['montant'], 2, ',', ' ') ?> €</td>
                            <td><?= htmlspecialchars($fiche['description']) ?></td>
                            <td>
                                <form method="POST" style="display: flex; gap: 5px;">
                                    <input type="hidden" name="fiche_id" value="<?= $fiche['id'] ?>">
                                    <button type="submit" name="action" value="valider" class="btn green">Valider</button>
                                    <button type="submit" name="action" value="refuser" class="btn red">Refuser</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune fiche de frais en attente de validation.</p>
        <?php endif; ?>
    </div>
</body>
</html>
