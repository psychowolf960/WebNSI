<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer l'identifiant et le mot de passe du formulaire
    $identifiant = $_POST['identifiant'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Vérifier si le professeur existe dans la base de données avec l'identifiant
    $stmt = $conn->prepare("SELECT id, identifiant, mot_de_passe, email FROM professeurs WHERE identifiant = ?");
    $stmt->bind_param("s", $identifiant);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $identifiant_bd, $mot_de_passe_bd, $email);
        $stmt->fetch();

        // Vérifier le mot de passe
        if (password_verify($mot_de_passe, $mot_de_passe_bd)) {
            // Stocker l'information du professeur dans la session
            $_SESSION['prof_id'] = $id;
            $_SESSION['prof_identifiant'] = $identifiant_bd;
            $_SESSION['prof_email'] = $email;

            header("Location: prof_dashboard.php"); // Rediriger vers le tableau de bord
            exit();
        } else {
            $message = "Mot de passe incorrect.";
        }
    } else {
        $message = "Identifiant non trouvé.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="prof_login.css">
    <title>Connexion Professeur</title>
</head>
<body>
    
    <h2>Connexion Professeur</h2>
    
    <form method="post">
        <label>Identifiant :</label>
        <input type="text" name="identifiant" required>

        <label>Mot de passe :</label>
        <input type="password" name="mot_de_passe" required>

        <button type="submit">Se connecter</button>
    </form>

    <?php if (isset($message)) { echo "<p style='color: red;'>$message</p>"; } ?>
</body>
</html>
