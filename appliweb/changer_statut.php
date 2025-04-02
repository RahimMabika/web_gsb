<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'comptable') {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id']) && isset($_GET['statut'])) {
    $pdo = new PDO('mysql:host=localhost;dbname=frais_app;charset=utf8', 'root', '');
    $id = intval($_GET['id']);
    $statut = $_GET['statut'];

    $stmt = $pdo->prepare("UPDATE fiches_frais SET statut = :statut WHERE id = :id");
    $stmt->execute([':statut' => $statut, ':id' => $id]);

    header('Location: dashboard_comptable.php');
    exit;
} else {
    die("Paramètres invalides");
}
