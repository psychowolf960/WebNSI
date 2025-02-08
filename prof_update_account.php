<?php
session_start();

// Activer l'affichage des erreurs (pour le debug)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    die("Erreur : Vous devez être connecté !");
}

$userId = $_SESSION['user_id'];

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecole_musique_era";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Vérifier que la requête est bien envoyée en POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $currentUsername = trim($_POST['current_username']);
    $currentPassword = trim($_POST['current_password']);
    $newUsername = trim($_POST['new_username']);
    $newPassword = trim($_POST['new_password']);

    // Vérifier si les champs ne sont pas vides
    if (empty($currentUsername) || empty($currentPassword) || empty($newUsername) || empty($newPassword)) {
        die("Tous les champs doivent être remplis !");
    }

    // Vérifier l'identifiant et le mot de passe actuels
    $stmt = $conn->prepare("SELECT identifiant, mot_de_passe FROM users WHERE id=? AND identifiant=?");
    $stmt->bind_param("is", $userId, $currentUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Vérifier le mot de passe
        if (password_verify($currentPassword, $row['mot_de_passe'])) {
            // Hasher le nouveau mot de passe
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

            // Mettre à jour l'identifiant et le mot de passe
            $updateStmt = $conn->prepare("UPDATE users SET identifiant=?, mot_de_passe=? WHERE id=?");
            $updateStmt->bind_param("ssi", $newUsername, $hashedPassword, $userId);

            if ($updateStmt->execute()) {
                echo "Compte mis à jour avec succès.";
                $_SESSION['username'] = $newUsername; // Mettre à jour la session
            } else {
                echo "Erreur lors de la mise à jour : " . $updateStmt->error;
            }
        } else {
            echo "Mot de passe actuel incorrect.";
        }
    } else {
        echo "Identifiant actuel incorrect.";
    }

    $stmt->close();
    $conn->close();
} else {
    die("Méthode non autorisée !");
}
?>
