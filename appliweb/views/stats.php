<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/admincontroller.php';

// Vérifier si l'utilisateur est connecté et est un admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Récupérer les statistiques
$statsUtilisateurs = admincontroller::getStatsUtilisateurs();
$statsFiches = admincontroller::getStatsFiches();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>Statistiques</h2>

    <h3>Nombre d'utilisateurs par rôle</h3>
    <canvas id="utilisateursChart"></canvas>

    <h3>Nombre de fiches de frais par état</h3>
    <canvas id="fichesChart"></canvas>

    <br>
    <a href="dashboard.php">Retour au tableau de bord</a>

    <h3>Liste des utilisateurs</h3>
    <?php admincontroller::afficherUtilisateurs(); ?>

    <script>
        // Statistiques des utilisateurs
        const ctx1 = document.getElementById('utilisateursChart').getContext('2d');
        new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: ['Visiteurs', 'Comptables', 'Admins'],
                datasets: [{
                    data: [<?= $statsUtilisateurs['visiteurs'] ?>, <?= $statsUtilisateurs['comptables'] ?>, <?= $statsUtilisateurs['admins'] ?>],
                    backgroundColor: ['blue', 'green', 'red']
                }]
            }
        });

        // Statistiques des fiches de frais
        const ctx2 = document.getElementById('fichesChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['En attente', 'Validées', 'Refusées'],
                datasets: [{
                    data: [<?= $statsFiches['en_attente'] ?>, <?= $statsFiches['validées'] ?>, <?= $statsFiches['refusées'] ?>],
                    backgroundColor: ['orange', 'green', 'red']
                }]
            }
        });
    </script>
</body>
</html>
