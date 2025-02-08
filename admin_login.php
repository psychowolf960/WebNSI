<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin</title>
    <link rel="stylesheet" href="admin_login.css"> <!-- Assurez-vous que le CSS est bien lié -->
</head>
<body>
    <div class="container">
        <h2>Connexion Admin</h2>
        <form action="admin_dashboard.php" method="post">
            <label for="identifiant">Identifiant :</label><br>
            <input type="text" id="identifiant" name="identifiant" required><br><br>

            <label for="mot_de_passe">Mot de passe :</label><br>
            <input type="password" id="mot_de_passe" name="mot_de_passe" required><br><br>


            <input type="submit" value="Se connecter">
        </form>
    </div>
</body>
</html>
