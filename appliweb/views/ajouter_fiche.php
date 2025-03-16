<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/fichefraiscontroller.php';

// Vérifier si l'utilisateur est connecté et est un visiteur
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'visiteur') {
    header("Location: login.php");
    exit;
}

// Gérer l'ajout de la fiche
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    fichesFraiscontroller::ajouterFiche($_SESSION['user_id'], $_POST['mois']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Fiche de Frais</title>
</head>
<body>
    <h2>Ajouter une Fiche de Frais</h2>
    <form method="POST" action="">
        <label>Mois (YYYY-MM) :</label>
        <input type="text" name="mois" pattern="\d{4}-\d{2}" required placeholder="2025-03"><br>

        <button type="submit">Créer la fiche</button>
    </form>

    <br>
    <a href="dashboard.php">Retour au tableau de bord</a>
</body>
</html>
