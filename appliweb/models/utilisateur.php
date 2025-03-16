<?php
require_once __DIR__ . '/../config/database.php';

class Utilisateur {
    public static function getUserByEmail($email) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
}
?>
