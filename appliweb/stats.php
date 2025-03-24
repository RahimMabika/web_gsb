<link rel="stylesheet" href="style.css">
<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
echo "<h2>Page des Statistiques</h2>";
?>
