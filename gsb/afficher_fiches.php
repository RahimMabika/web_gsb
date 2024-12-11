<?php
session_start();
require_once("db.php");


// Connexion à la base de données


// if ($conn->connect_error) {
//     die("Erreur de connexion : " . $conn->connect_error);
// }

$sql = "SELECT date, montant, description FROM fiches WHERE utilisateur = ?";
$stmt = $db->prepare($sql);
// var_dump($_SESSION['utilisateur']);
// $stmt->bind_param("s", $_SESSION['utilisateur']);
$stmt->execute([$_SESSION['utilisateur']]);
// $result = $stmt->fetchAll();
// var_dump($result);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Mes fiches de frais</title>
</head>
<body>
    <h1>Mes fiches de frais</h1>
    <table border="1">
        <tr>
            <th>Date</th>
            <th>Montant</th>
            <th>Description</th>
            <th>status</th>
        </tr>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
            <tr>
                <td><?php echo $row['date']; ?></td>
                <td><?php echo $row['montant']; ?> €</td>
                <td><?php echo $row['description']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="index.php">Retour à l'accueil</a>
</body>
</html>

<?php
//$stmt->close();
$db=null;
?>
