<?php
session_start();
include 'config.php';

// Vérifier si le professeur est connecté
if (!isset($_SESSION['prof_id'])) {
    header("Location: prof_login.php");
    exit();
}

$prof_id = $_SESSION['prof_id'];

// Récupérer les informations actuelles du professeur
$sql = "SELECT identifiant FROM professeurs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $prof_id);
$stmt->execute();
$result = $stmt->get_result();
$professeur = $result->fetch_assoc();
$stmt->close();

// Traitement du formulaire de mise à jour
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["new_identifiant"])) {
        $new_identifiant = $_POST["new_identifiant"];

        // Mise à jour de l'identifiant
        $sql_update = "UPDATE professeurs SET identifiant = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $new_identifiant, $prof_id);
        if ($stmt_update->execute()) {
            echo "<p style='color: green;'>Identifiant mis à jour avec succès !</p>";
            $professeur['identifiant'] = $new_identifiant; // Mise à jour de l'affichage
        } else {
            echo "<p style='color: red;'>Erreur lors de la mise à jour.</p>";
        }
        $stmt_update->close();
    }

    if (isset($_POST["new_password"]) && isset($_POST["confirm_password"])) {
        $new_password = $_POST["new_password"];
        $confirm_password = $_POST["confirm_password"];

        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Mise à jour du mot de passe
            $sql_update_pass = "UPDATE professeurs SET mot_de_passe = ? WHERE id = ?";
            $stmt_update_pass = $conn->prepare($sql_update_pass);
            $stmt_update_pass->bind_param("si", $hashed_password, $prof_id);
            if ($stmt_update_pass->execute()) {
                echo "<p style='color: green;'>Mot de passe mis à jour avec succès !</p>";
            } else {
                echo "<p style='color: red;'>Erreur lors de la mise à jour.</p>";
            }
            $stmt_update_pass->close();
        } else {
            echo "<p style='color: red;'>Les mots de passe ne correspondent pas.</p>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte</title>
    <link rel="stylesheet" href="prof_dashboard.css">
</head>
<body>
    <nav>
        <a href="prof_dashboard.php">Retour</a>
    </nav>

    <h2>Informations du Professeur</h2>
    
    <div class="info-container">
        <p><strong>Identifiant :</strong> <?php echo htmlspecialchars($professeur['identifiant']); ?></p>
    </div>

    <h3>Modifier Identifiant</h3>
    <form method="post">
        <label for="new_identifiant">Nouveau Identifiant :</label>
        <input type="text" name="new_identifiant" required>
        <button type="submit">Mettre à jour</button>
    </form>

    <h3>Changer de Mot de Passe</h3>
    <form method="post">
        <label for="new_password">Nouveau Mot de Passe :</label>
        <input type="password" name="new_password" required>
        
        <label for="confirm_password">Confirmer le Mot de Passe :</label>
        <input type="password" name="confirm_password" required>

        <button type="submit">Mettre à jour</button>
    </form>

    <br>
    <a href="prof_logout.php">Déconnexion</a>
</body>
</html>
