<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion fiches de Frais</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        h1 {
            color: #0056a4;
            margin-top: 20px;
        }
        .container {
            margin-top: 50px;
        }
        .logo {
            margin-bottom: 20px;
        }
        .btn-container {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 30px;
        }
        .btn {
            width: 200px;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            color: white;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        .btn.visiteur {
            background-color: #4091e9;
        }
        .btn.visiteur:hover {
            background-color: #4091e9;
        }
        .btn.comptable {
            background-color: #28a745;
        }
        .btn.comptable:hover {
            background-color: #28a745;
        }
        .footer {
            margin-top: 50px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="image/gsbgood.jpg" >
        </div>
        <h1>Gestion des Fiches de Frais</h1>
    </header>
    
    <div class="container">
        <div class="btn-container">
            <a href="login.php" class="btn visiteur">Visiteur Médical</a>
            <a href="login.php" class="btn comptable">Comptable</a>
        </div>
    </div>
</body>
</html>
