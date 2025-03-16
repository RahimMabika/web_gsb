<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/fichefraiscontroller.php';

// Vérifier si l'utilisateur est connecté et est un visiteur
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'visiteur') {
    header("Location: login.php");
    exit;
}

// Récupérer les fiches du visiteur
$fiches = fichesfraiscontroller::getFichesByUser($_SESSION['user_id']);

// Gérer l'ajout de frais
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['type_frais']) && $_POST['type_frais'] === 'forfait') {
      fichesfraiscontroller::ajouterFraisForfaitaire($_POST['id_fiche'], $_POST['type'], $_POST['quantite']);
    } elseif (isset($_POST['type_frais']) && $_POST['type_frais'] === 'hors_forfait') {
      fichesfraiscontroller::ajouterFraisHorsForfait($_POST['id_fiche'], $_POST['date'], $_POST['libelle'], $_POST['montant']);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter des Frais</title>
</head>
<body>
    <h2>Ajouter des Frais</h2>
    
    <form method="POST" action="">
        <label>Fiche de frais :</label>
        <select name="id_fiche" required>
            <?php foreach ($fiches as $fiche): ?>
                <option value="<?= $fiche['id'] ?>">Mois : <?= $fiche['mois'] ?> (<?= ucfirst($fiche['etat']) ?>)</option>
            <?php endforeach; ?>
        </select><br>

        <h3>Frais Forfaitaires</h3>
        <input type="hidden" name="type_frais" value="forfait">
        <label>Type :</label>
        <select name="type">
            <option value="nuitée">Nuitée</option>
            <option value="repas">Repas</option>
            <option value="kilométrage">Kilométrage</option>
        </select><br>
        <label>Quantité :</label>
        <input type="number" name="quantite" required><br>
        <button type="submit">Ajouter</button>
    </form>

    <form method="POST" action="">
        <h3>Frais Hors Forfait</h3>
        <input type="hidden" name="type_frais" value="hors_forfait">
        <label>Date :</label>
        <input type="date" name="date" required><br>
        <label>Libellé :</label>
        <input type="text" name="libelle" required><br>
        <label>Montant :</label>
        <input type="number" step="0.01" name="montant" required><br>
        <button type="submit">Ajouter</button>
    </form>

    <br>
    <a href="dashboard.php">Retour au tableau de bord</a>
</body>
</html>
