<?php
$host = 'localhost';         
$user = 'root';             
$password = ''; // Votre mot de passe MySQL (ici vide pour XAMPP ou WAMP)

// Essayer de se connecter à la base de données
try {
    // Création d'une instance PDO pour la connexion
    $db = new PDO("mysql:host=$host;dbname=gsb", $user, $password); // Pas de guillemets autour du nom de la base
    // Définir le mode d'erreur pour afficher les erreurs SQL
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Si la connexion échoue, afficher une erreur et arrêter l'exécution
    die("Erreur de connexion : " . $e->getMessage());
}