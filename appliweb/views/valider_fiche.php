<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/fichefraiscontroller.php';

// Vérifier si l'utilisateur est connecté et est un comptable
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'comptable') {
    header("Location: login.php");
    exit;
}

// Vérifier si une fiche est sélectionnée
if (!isset($_GET['id'])) {
    echo "<p>Aucune fiche sélectionnée.</p>";
    exit;
}

$id_fiche = $_GET['id'];

// Gérer la validation ou le refus
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'valider') {
          fichesfraiscontroller::changerEtatFiche($id_fiche, 'validée');
        } elseif ($_POST['action'] === 'refuser') {
          fichesfraiscontroller::changerEtatFiche($id_fiche, 'refusée');
        }
    }
}

// Récupérer les détails de la fiche
$fiche = fichesfraiscontroller::getFicheById($id_fiche);
if (!$fiche) {
    echo "<p>Fiche non trouvée.</p>";
    exit;
}

// Récupérer les frais associés
$frais_forfaitaires = fichesfraiscontroller::getFraisForfaitaires($id_fiche);
$frais_hors_forfait = fichesfraiscontroller::getFraisHorsForfait($id_fiche);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation de la Fiche</title>
</head>
<body>
    <h2>Validation de la Fiche</h2>
    <p>Mois : <?= $fiche['mois'] ?></p>
    <p>État actuel : <?= ucfirst($fiche['etat']) ?></p>

    <h3>Frais Forfaitaires</h3>
    <table border="1">
        <tr><th>Type</th><th>Quantité</th><th>Montant Unitaire</th><th>Total</th></tr>
        <?php foreach ($frais_forfaitaires as $frais): ?>
            <tr>
                <td><?= ucfirst($frais['type']) ?></td>
                <td><?= $frais['quantite'] ?></td>
                <td><?= $frais['montant_unitaire'] ?> €</td>
                <td><?= $frais['quantite'] * $frais['montant_unitaire'] ?> €</td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h3>Frais Hors Forfait</h3>
    <table border="1">
        <tr><th>Date</th><th>Libellé</th><th>Montant</th></tr>
        <?php foreach ($frais_hors_forfait as $frais): ?>
            <tr>
                <td><?= $frais['date'] ?></td>
                <td><?= $frais['libelle'] ?></td>
                <td><?= $frais['montant'] ?> €</td>
            </tr>
        <?php endforeach; ?>
    </table>

    <form method="POST" action="">
        <button type="submit" name="action" value="valider">✅ Valider la fiche</button>
        <button type="submit" name="action" value="refuser">❌ Refuser la fiche</button>
    </form>

    <br>
    <a href="dashboard.php">Retour au tableau de bord</a>
</body>
</html>
