<link rel="stylesheet" href="style.css">
<?php
$pdo = new PDO('mysql:host=localhost;dbname=frais_app;charset=utf8', 'root', '');
$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM details_frais WHERE id = :id");
$stmt->execute([':id' => $id]);

header('Location: liste_frais.php');
exit;
?>
