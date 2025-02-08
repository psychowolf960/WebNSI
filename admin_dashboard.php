<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifiant = $_POST['identifiant'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Vérifier si l'admin existe
    $stmt = $conn->prepare("SELECT * FROM admins WHERE identifiant = ? AND mot_de_passe = ?");
    $stmt->bind_param("ss", $identifiant, $mot_de_passe);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['admin'] = $identifiant; // Stocker la session de l'admin
        header("Location: admin_panel.php"); // Rediriger vers le tableau de bord
        exit();
    } else {
        echo "❌ Identifiant ou mot de passe incorrect.";
    }
}
?>
