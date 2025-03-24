<link rel="stylesheet" href="style.css">
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $pdo = new PDO('mysql:host=localhost;dbname=frais_app;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des données POST
    $fiche_id = $_POST['fiche_id'];
    $categorie = $_POST['categorie'];
    $montant = $_POST['montant'];
    $description = $_POST['description'];
    $date = $_POST['date'];

    // Insertion dans details_frais
    $sql = 'INSERT INTO details_frais (fiche_id, categorie, montant, description, date)
            VALUES (:fiche_id, :categorie, :montant, :description, :date)';
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':fiche_id' => $fiche_id,
        ':categorie' => $categorie,
        ':montant' => $montant,
        ':description' => $description,
        ':date' => $date
    ]);

    echo "✅ Frais inséré avec succès ! <a href='voir_fiche.php?id=$fiche_id'>Voir la fiche</a>";

} catch (Exception $e) {
    die('❌ Erreur : ' . $e->getMessage());
}
?>
