<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h2>Bienvenue sur votre tableau de bord</h2>
    
    <p>Vous êtes connecté en tant que <strong><?php echo ucfirst($_SESSION['user_role']); ?></strong></p>
    
    <nav>
        <a href="dashboard.php">Accueil</a> |
        <a href="logout.php">Déconnexion</a>
    </nav>

    <?php
    if ($_SESSION['user_role'] === 'visiteur'): ?>
        <h3>Vos fiches de frais</h3>
        <?php require_once __DIR__ . '/../controllers/fichesfraiscontroller.php'; ?>
        <?php FicheFraisController::afficherFichesUtilisateur($_SESSION['user_id']); ?>
        <a href="ajouter_fiche.php">+ Ajouter une fiche de frais</a>

    <?php elseif ($_SESSION['user_role'] === 'comptable'): ?>
        <h3>Fiches de frais à valider</h3>
        <?php require_once __DIR__ . '/../controllers/fichesfraiscontroller.php'; ?>
        <?php
        $fiches = FicheFraisController::afficherFichesAValider();
        foreach ($fiches as $fiche) {
            echo "<p>Mois : {$fiche['mois']} - <a href='valider_fiche.php?id={$fiche['id']}'>Gérer</a></p>";
        }
        ?>

    <?php elseif ($_SESSION['user_role'] === 'admin'): ?>
        <h3>Liste des utilisateurs</h3>
        <?php require_once __DIR__ . '/../controllers/admincontroller.php'; ?>
        <?php AdminController::afficherUtilisateurs(); ?>

        <h3>Statistiques</h3>
        <a href="stats.php">📊 Voir les statistiques</a>
    <?php endif; ?>

</body>
</html>
