<?php require_once __DIR__ . '/../controllers/authcontroller.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <h2>Connexion</h2>
    <form method="POST" action="">
        <label>Email:</label>
        <input type="email" name="email" required><br>

        <label>Mot de passe:</label>
        <input type="password" name="mot_de_passe" required><br>

        <button type="submit">Se connecter</button>
    </form>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    AuthController::connecter();
}
?>
