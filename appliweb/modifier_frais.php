<link rel="stylesheet" href="style.css">
<?php
$pdo = new PDO('mysql:host=localhost;dbname=frais_app;charset=utf8', 'root', '');

if (!isset($_GET['id'])) {
    die("❌ ID manquant dans l'URL.");
}
$id = intval($_GET['id']);  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $categorie = $_POST['categorie'];
  $montant = $_POST['montant'];
  $description = $_POST['description'];

  $stmt = $pdo->prepare("UPDATE details_frais SET categorie = :cat, montant = :mont, description = :desc WHERE id = :id");
  $stmt->execute([
    ':cat' => $categorie,
    ':mont' => $montant,
    ':desc' => $description,
    ':id' => $id,
  ]);
  header('Location: liste_frais.php');
  exit;
}

$frais = $pdo->prepare("SELECT * FROM details_frais WHERE id = :id");
$frais->execute([':id' => $id]);
$data = $frais->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die("❌ Aucun frais trouvé avec l'ID $id.");
}
?>
<form method="POST">
Catégorie: <input name="categorie" value="<?= htmlspecialchars($data['categorie']) ?>"><br>
Montant: <input name="montant" value="<?= htmlspecialchars($data['montant']) ?>"><br>
Description: <input name="description" value="<?= htmlspecialchars($data['description']) ?>"><br>
<input type="submit" value="Modifier">
</form>
