<?php
require_once __DIR__ . '/../models/utilisateur.php';
session_start();

class AuthController {
    public static function inscrire() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nom = $_POST['nom'];
            $email = $_POST['email'];
            $mot_de_passe = $_POST['mot_de_passe'];
            $role = $_POST['role'];

            if (!empty($nom) && !empty($email) && !empty($mot_de_passe) && !empty($role)) {
                if (Utilisateur::inscrire($nom, $email, $mot_de_passe, $role)) {
                    header("Location: login.php");
                    exit;
                } else {
                    echo "Erreur lors de l'inscription.";
                }
            } else {
                echo "Veuillez remplir tous les champs.";
            }
        }
    }

    public static function connecter() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = $_POST['email'];
            $mot_de_passe = $_POST['mot_de_passe'];

            $utilisateur = Utilisateur::getUserByEmail($email);
            if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
                $_SESSION['user_id'] = $utilisateur['id'];
                $_SESSION['user_role'] = $utilisateur['role'];
                header("Location: dashboard.php");
                exit;
            } else {
                echo "Email ou mot de passe incorrect.";
            }
        }
    }

    public static function deconnecter() {
        session_destroy();
        header("Location: login.php");
        exit;
    }
}
?>
