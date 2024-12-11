<?php
session_start();
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $montant = $_POST['montant'];
    $description = $_POST['description'];

    try {
        // Connexion à la base de données avec PDO
        // $pdo = new PDO("mysql:host=localhost;dbname=gsb;charset=utf8", "root", "");
        // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO fiches (utilisateur, date, montant, description, status) VALUES (:user, :date, :montant, :description, :status)";
        $stmt = $db->prepare($sql);
        $stmt->execute(params: [
            ':user' => $_SESSION['utilisateur'],
            ':date' => $date,
            ':montant' => $montant,
            ':description' => $description,
            ':status' => 'en attente'
        ]);

        echo "Fiche de frais enregistrée avec succès.";
    } catch (PDOException $e) {
        echo "Erreur lors de l'enregistrement : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer une fiche de frais</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Créer une fiche de frais</h1>
    <form action="creer_fiche.php" method="post">
        <label for="date">Date :</label>
        <input type="date" name="date" id="date" required>
        <br>
        <label for="montant">Montant :</label>
        <input type="number" name="montant" id="montant" step="0.01" required>
        <br>
        <label for="description">Description :</label>
        <textarea name="description" id="description" required></textarea>
        <br>
        <button class="enre" type="submit">Enregistrer</button>
    </form>
    <a href="visiteur.php">Retour à l'accueil</a>
    <style>
        .enre{
    background-color: #3384da;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
        }
        .enre:hover{
            background-color: #3384da;
        }
    </style>
</body>
</html>
