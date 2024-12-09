<?php
session_start();
$dsn = 'mysql:host=localhost;dbname=gsb;charset=utf8mb4';
$user = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $utilisateur = $_SESSION['utilisateur'] ?? 'Anonyme'; // Par défaut
    $date = date('Y-m-d');
    $montant = $_POST['montant'];
    $description = $_POST['description'];

    // Insérer dans la table fiches
    $stmt = $pdo->prepare("INSERT INTO fiches (utilisateur, date, montant, description) VALUES (:utilisateur, :date, :montant, :description)");
    $stmt->execute([
        ':utilisateur' => $utilisateur,
        ':date' => $date,
        ':montant' => $montant,
        ':description' => $description
    ]);

    // Récupérer l'ID de la fiche créée
    $fiche_id = $pdo->lastInsertId();

    // Insérer les frais forfaitaires
    if (!empty($_POST['frais_forfaitaire'])) {
        foreach ($_POST['frais_forfaitaire'] as $forfait) {
            $stmt = $pdo->prepare("INSERT INTO frais_forfaitaire (fiche_id, libelle, montant, quantite) VALUES (:fiche_id, :libelle, :montant, :quantite)");
            $stmt->execute([
                ':fiche_id' => $fiche_id,
                ':libelle' => $forfait['libelle'],
                ':montant' => $forfait['montant'],
                ':quantite' => $forfait['quantite']
            ]);
        }
    }

    // Insérer les frais hors forfaitaires
    if (!empty($_POST['frais_hors_forfaitaire'])) {
        foreach ($_POST['frais_hors_forfaitaire'] as $hors_forfait) {
            $stmt = $pdo->prepare("INSERT INTO frais_hors_forfaitaire (fiche_id, libelle, montant, description) VALUES (:fiche_id, :libelle, :montant, :description)");
            $stmt->execute([
                ':fiche_id' => $fiche_id,
                ':libelle' => $hors_forfait['libelle'],
                ':montant' => $hors_forfait['montant'],
                ':description' => $hors_forfait['description']
            ]);
        }
    }

    echo "Fiche de frais créée avec succès !";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Créer une fiche de frais</title>
</head>
<body>
    <div class="container">
        <h1>Créer une fiche de frais</h1>
        <form action="fiches.php" method="POST">
            <label for="montant">Montant total :</label>
            <input type="number" name="montant" step="0.01" required>

            <label for="description">Description :</label>
            <textarea name="description" required></textarea>

            <h2>Frais Forfaitaires</h2>
            <div id="forfait-container">
                <div>
                    <input type="text" name="frais_forfaitaire[0][libelle]" placeholder="Libellé" required>
                    <input type="number" name="frais_forfaitaire[0][montant]" step="0.01" placeholder="Montant" required>
                    <input type="number" name="frais_forfaitaire[0][quantite]" placeholder="Quantité" required>
                </div>
            </div>
            <button type="button" onclick="ajouterForfait()">Ajouter un forfait</button>

            <h2>Frais Hors Forfait</h2>
            <div id="hors-forfait-container">
                <div>
                    <input type="text" name="frais_hors_forfaitaire[0][libelle]" placeholder="Libellé" required>
                    <input type="number" name="frais_hors_forfaitaire[0][montant]" step="0.01" placeholder="Montant" required>
                    <input type="text" name="frais_hors_forfaitaire[0][description]" placeholder="Description" required>
                </div>
            </div>
            <button type="button" onclick="ajouterHorsForfait()">Ajouter un hors forfait</button>

            <button type="submit">Créer la fiche</button>
        </form>
    </div>

    <script>
        function ajouterForfait() {
            const container = document.getElementById('forfait-container');
            const index = container.children.length;
            container.innerHTML += `
                <div>
                    <input type="text" name="frais_forfaitaire[${index}][libelle]" placeholder="Libellé" required>
                    <input type="number" name="frais_forfaitaire[${index}][montant]" step="0.01" placeholder="Montant" required>
                    <input type="number" name="frais_forfaitaire[${index}][quantite]" placeholder="Quantité" required>
                </div>
            `;
        }

        function ajouterHorsForfait() {
            const container = document.getElementById('hors-forfait-container');
            const index = container.children.length;
            container.innerHTML += `
                <div>
                    <input type="text" name="frais_hors_forfaitaire[${index}][libelle]" placeholder="Libellé" required>
                    <input type="number" name="frais_hors_forfaitaire[${index}][montant]" step="0.01" placeholder="Montant" required>
                    <input type="text" name="frais_hors_forfaitaire[${index}][description]" placeholder="Description" required>
                </div>
            `;
        }
    </script>
</body>
</html>
