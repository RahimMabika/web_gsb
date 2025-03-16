<?php require_once __DIR__ . '/../controllers/authcontroller.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    <h2>Inscription</h2>
    <form method="POST" action="">
        <label>Nom:</label>
        <input type="text" name="nom" required><br>

        <label>Email:</label>
        <input type="email" name="email" required><br>

        <label>Mot de passe:</label>
        <input type="password" name="mot_de_passe" required><br>

        <label>Rôle:</label>
        <select name="role" required>
            <option value="visiteur">Visiteur</option>
            <option value="comptable">Comptable</option>
            <option value="admin">Admin</option>
        </select><br>

        <button type="submit">S'inscrire</button>
    </form>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    AuthController::inscrire();
}
?>
