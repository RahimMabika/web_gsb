<?php
session_start();
$dsn = 'mysql:host=localhost;dbname=gsb;charset=utf8mb4';
$user = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fiche_id']) && isset($_POST['action'])) {
    $fiche_id = $_POST['fiche_id'];
    $action = $_POST['action'];

    switch ($action) {
        case 'valider':
            $new_status = 'validée';
            break;
        case 'refuser':
            $new_status = 'refusée';
            break;
        case 'en_attente':
            $new_status = 'en attente';
            break;
        default:
            $new_status = 'en attente';
            break;
    }

    $sql = "UPDATE fiches SET status = :status WHERE id = :fiche_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':status' => $new_status,
        ':fiche_id' => $fiche_id
    ]);

    header('Location: comptable.php');
    exit();
}

$sql = "SELECT * FROM fiches";
$stmt = $pdo->query($sql);
$fiches = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Validation fiches de frais</title>
</head>
<body>
    <header>
        <h1>Validation des Fiches de Frais</h1>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="login.php">Déconnexion</a>
        </nav>
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
                                    <button type="submit" name="action" value="valider" class="btnc">Valider</button>
                                    <button type="submit" name="action" value="refuser" class="btnc">Refuser</button>
                                    <button  type="submit" name="action" value="refuser" class="btnc">En attente</button>
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
    <style>
        .container{
            justify-content: center;
            align-items: center;

        }
        .btnc {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btnc:hover{
            background-color: #007BFF;
        }
    </style>
</body>
</html>
