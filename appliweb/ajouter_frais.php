<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Vérifie si fiche_id est passé en GET
if (!isset($_GET['fiche_id'])) {
    die("❌ Aucun ID de fiche fourni.");
}

$fiche_id = $_GET['fiche_id'];
?>

<h2>Ajouter un frais à la fiche #<?= htmlspecialchars($fiche_id) ?></h2>

<form method="POST" action="inserer_frais.php">
    <input type="hidden" name="fiche_id" value="<?= htmlspecialchars($fiche_id) ?>">
    
    Catégorie : 
    <select name="categorie" required>
        <option value="repas">Repas</option>
        <option value="hébergement">Hébergement</option>
        <option value="transport">Transport</option>
    </select><br>

    Montant : <input type="number" name="montant" required><br>
    Description : <input type="text" name="description" required><br>
    Date : <input type="date" name="date" required><br>

    <input type="submit" value="Ajouter le frais">
</form>
