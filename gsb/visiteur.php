<?php
session_start();
// Remplacez par vos informations de session
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des frais</title>
    <link rel="stylesheet" href="style.css"> <!-- Ajoutez un fichier CSS pour le style -->
</head>
<body>
    <header>
        <h1>Gestion des frais</h1>
        <p>Visiteur : <?php echo $_SESSION['users']; ?></p>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="creer_fiche.php">Renseigner la fiche de frais</a>
            <a href="afficher_fiches.php">Afficher mes fiches de frais</a>
            <a href="deconnexion.php">Déconnexion</a>
        </nav>
    </header>
    <main>
        <div class="navigation">
            <a href="creer_fiche.php" class="btn green">Renseigner la fiche de frais</a>
            <a href="afficher_fiches.php" class="btn blue">Afficher mes fiches de frais</a>
        </div>
    </main>
</body>
</html>
