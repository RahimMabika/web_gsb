<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=frais_app;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion réussie à la base de données.";
} catch (Exception $e) {
    die('❌ Erreur : ' . $e->getMessage());
}
?>
