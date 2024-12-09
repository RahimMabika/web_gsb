<?php
session_start();
require_once("db.php");

// Vérifier si le formulaire a été soumis via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if ($email != "" && $password != "") {
        // Préparer la requête SQL avec des paramètres pour éviter les injections SQL
        $stmt = $db->prepare("SELECT * FROM `users` WHERE email = :email AND password = :password");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        
        // Exécuter la requête
        $stmt->execute();
        
        // Récupérer le résultat
        $rep = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si l'utilisateur existe et obtenir le rôle
        if ($rep && $rep['id'] != false) {
            // L'utilisateur est trouvé, récupérer le rôle
            $role = $rep['role'];
            $_SESSION['utilisateur'] = $rep['id'];
            $_SESSION['users'] = $rep['email'];


            // Rediriger en fonction du rôle
            if ($role == 'visiteur') {
                header("Location: visiteur.php");  // Redirection vers la page visiteur
                exit();
            } elseif ($role == 'comptable') {
                header("Location: comptable.php");  // Redirection vers la page comptable
                exit();
            }
        } else {
            $error_message = "Email ou mot de passe incorrect !";
        }
    }
}
?>

<!-- Formulaire de connexion HTML -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - GSB</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Connexion - GSB</h2>
        
        <!-- Afficher un message d'erreur s'il existe -->
        <?php if (!empty($error_message)) : ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire de connexion -->
        <form action="login.php" method="POST" class="shadow p-4 rounded">
            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email :</label>
                <input type="email" id="email" name="email" class="form-control" required placeholder="Entrez votre email">
            </div>
            
            <!-- Mot de passe -->
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe :</label>
                <input type="password" id="password" name="password" class="form-control" required placeholder="Entrez votre mot de passe">
            </div>
            
            <!-- Bouton de connexion -->
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>
    </div>
</body>
</html>
