<link rel="stylesheet" href="style.css">
<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
echo "<h2>Page Gestion des Utilisateurs</h2>";
?>
