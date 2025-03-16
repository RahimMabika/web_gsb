<?php
require_once __DIR__ . '/../config/database.php';

class FicheFraisController {
    public static function afficherFichesUtilisateur($id_utilisateur) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM fiches_frais WHERE id_utilisateur = ?");
        $stmt->execute([$id_utilisateur]);
        $fiches = $stmt->fetchAll();

        if (!$fiches) {
            echo "<p>Aucune fiche de frais trouvée.</p>";
            return;
        }

        echo "<table border='1'>";
        echo "<tr><th>Mois</th><th>État</th><th>Date Soumission</th></tr>";
        foreach ($fiches as $fiche) {
            echo "<tr>";
            echo "<td>" . $fiche['mois'] . "</td>";
            echo "<td>" . ucfirst($fiche['etat']) . "</td>";
            echo "<td>" . $fiche['date_soumission'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    public static function afficherFichesAValider() {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM fiches_frais WHERE etat = 'en_attente'");
        $stmt->execute();
        $fiches = $stmt->fetchAll();

        if (!$fiches) {
            echo "<p>Aucune fiche de frais en attente.</p>";
            return;
        }

        echo "<table border='1'>";
        echo "<tr><th>Utilisateur</th><th>Mois</th><th>Action</th></tr>";
        foreach ($fiches as $fiche) {
            echo "<tr>";
            echo "<td>" . $fiche['id_utilisateur'] . "</td>";
            echo "<td>" . $fiche['mois'] . "</td>";
            echo "<td>
                    <a href='valider_fiche.php?id=" . $fiche['id'] . "'>Valider</a> |
                    <a href='refuser_fiche.php?id=" . $fiche['id'] . "'>Refuser</a>
                  </td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    public static function ajouterFiche($id_utilisateur, $mois) {
        global $pdo;

        // Vérifier si une fiche pour ce mois existe déjà
        $stmt = $pdo->prepare("SELECT id FROM fiches_frais WHERE id_utilisateur = ? AND mois = ?");
        $stmt->execute([$id_utilisateur, $mois]);
        if ($stmt->fetch()) {
            echo "<p>Une fiche de frais existe déjà pour ce mois.</p>";
            return;
        }

        // Insérer une nouvelle fiche de frais
        $stmt = $pdo->prepare("INSERT INTO fiches_frais (id_utilisateur, mois) VALUES (?, ?)");
        if ($stmt->execute([$id_utilisateur, $mois])) {
            echo "<p>Fiche de frais ajoutée avec succès.</p>";
        } else {
            echo "<p>Erreur lors de l'ajout.</p>";
        }
    }
    public static function getFichesByUser($id_utilisateur) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM fiches_frais WHERE id_utilisateur = ?");
        $stmt->execute([$id_utilisateur]);
        return $stmt->fetchAll();
    }
    
    public static function ajouterFraisForfaitaire($id_fiche, $type, $quantite) {
        global $pdo;
    
        // Définition des montants unitaires
        $montants = [
            'nuitée' => 80.00,
            'repas' => 29.00,
            'kilométrage' => 0.67
        ];
        $montant_unitaire = $montants[$type];
    
        $stmt = $pdo->prepare("INSERT INTO frais_forfaitaires (id_fiche, type, quantite, montant_unitaire) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$id_fiche, $type, $quantite, $montant_unitaire])) {
            echo "<p>Frais forfaitaire ajouté avec succès.</p>";
        } else {
            echo "<p>Erreur lors de l'ajout.</p>";
        }
    }
    
    public static function ajouterFraisHorsForfait($id_fiche, $date, $libelle, $montant) {
        global $pdo;
    
        $stmt = $pdo->prepare("INSERT INTO frais_hors_forfait (id_fiche, date, libelle, montant) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$id_fiche, $date, $libelle, $montant])) {
            echo "<p>Frais hors forfait ajouté avec succès.</p>";
        } else {
            echo "<p>Erreur lors de l'ajout.</p>";
        }
    }   
    public static function getFicheById($id_fiche) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM fiches_frais WHERE id = ?");
        $stmt->execute([$id_fiche]);
        return $stmt->fetch();
    }
    
    public static function getFraisForfaitaires($id_fiche) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM frais_forfaitaires WHERE id_fiche = ?");
        $stmt->execute([$id_fiche]);
        return $stmt->fetchAll();
    }
    
    public static function getFraisHorsForfait($id_fiche) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM frais_hors_forfait WHERE id_fiche = ?");
        $stmt->execute([$id_fiche]);
        return $stmt->fetchAll();
    }
    
    public static function changerEtatFiche($id_fiche, $nouvel_etat) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE fiches_frais SET etat = ? WHERE id = ?");
        if ($stmt->execute([$nouvel_etat, $id_fiche])) {
            echo "<p>Fiche mise à jour avec succès.</p>";
            header("Refresh:2; url=dashboard.php");
        } else {
            echo "<p>Erreur lors de la mise à jour.</p>";
        }
    }     
}
?>
