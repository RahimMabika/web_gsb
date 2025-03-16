<?php
require_once __DIR__ . '/../config/database.php';

class admincontroller {
    public static function afficherUtilisateurs() {
        global $pdo;
        $stmt = $pdo->query("SELECT id, nom, email, role FROM utilisateurs");
        $utilisateurs = $stmt->fetchAll();

        if (!$utilisateurs) {
            echo "<p>Aucun utilisateur trouvé.</p>";
            return;
        }

        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Nom</th><th>Email</th><th>Rôle</th></tr>";
        foreach ($utilisateurs as $user) {
            echo "<tr>";
            echo "<td>" . $user['id'] . "</td>";
            echo "<td>" . $user['nom'] . "</td>";
            echo "<td>" . $user['email'] . "</td>";
            echo "<td>" . ucfirst($user['role']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    public static function getStatsUtilisateurs() {
        global $pdo;
        $stmt = $pdo->query("SELECT role, COUNT(*) as total FROM utilisateurs GROUP BY role");
        $result = $stmt->fetchAll();
    
        $stats = ['visiteurs' => 0, 'comptables' => 0, 'admins' => 0];
        foreach ($result as $row) {
            $stats[$row['role'] . 's'] = $row['total'];
        }
    
        return $stats;
    }
    
    public static function getStatsFiches() {
        global $pdo;
        $stmt = $pdo->query("SELECT etat, COUNT(*) as total FROM fiches_frais GROUP BY etat");
        $result = $stmt->fetchAll();
    
        $stats = ['en_attente' => 0, 'validées' => 0, 'refusées' => 0];
        foreach ($result as $row) {
            $stats[$row['etat']] = $row['total'];
        }
    
        return $stats;
    }    
}
?>
