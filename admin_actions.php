<?php
session_start();
include 'config.php';

// Vérifier si l'admin est connecté
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == "add_prof") {
        // Ajouter un professeur
        $identifiant = $_POST['identifiant'];
        $mot_de_passe = $_POST['mot_de_passe'];
        $email = $_POST['email']; // Ajout du champ email pour le professeur

        $stmt = $conn->prepare("INSERT INTO professeurs (identifiant, mot_de_passe, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $identifiant, $mot_de_passe, $email);

        if ($stmt->execute()) {
            $_SESSION['message'] = "✅ Professeur ajouté avec succès.";
        } else {
            $_SESSION['message'] = "❌ Erreur lors de l'ajout du professeur.";
        }

        $stmt->close();
    } elseif ($action == "delete_prof") {
        // Supprimer un professeur
        $id = $_POST['id'];

        $stmt = $conn->prepare("DELETE FROM professeurs WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Vérifier si la table est vide
            $result = $conn->query("SELECT MAX(id) AS max_id FROM professeurs");
            $row = $result->fetch_assoc();
            $new_auto_increment = ($row['max_id'] === NULL) ? 1 : $row['max_id'] + 1;

            // Réinitialiser l'AUTO_INCREMENT
            $conn->query("ALTER TABLE professeurs AUTO_INCREMENT = $new_auto_increment;");
            $_SESSION['message'] = "✅ Professeur supprimé.";
        } else {
            $_SESSION['message'] = "❌ Erreur lors de la suppression.";
        }

        $stmt->close();
    } elseif ($action == "add_eleve") {
        // Ajouter un élève
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $adresse_email = $_POST['adresse_email'];
        $professeur_id = $_POST['professeur_id'];  // Récupérer l'id du professeur sélectionné

        $stmt = $conn->prepare("INSERT INTO inscriptions (nom, prenom, adresse_email, professeur_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nom, $prenom, $adresse_email, $professeur_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "✅ Élève ajouté avec succès.";
        } else {
            $_SESSION['message'] = "❌ Erreur lors de l'ajout de l'élève.";
        }

        $stmt->close();
    } elseif ($action == "delete_eleve") {
        // Supprimer un élève
        $id = $_POST['id'];

        $stmt = $conn->prepare("DELETE FROM inscriptions WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Trouver le plus grand ID actuel après suppression
            $result = $conn->query("SELECT MAX(id) AS max_id FROM inscriptions");
            $row = $result->fetch_assoc();
            $new_auto_increment = ($row['max_id'] === NULL) ? 1 : $row['max_id'] + 1;

            // Réinitialiser l'AUTO_INCREMENT avec la nouvelle valeur
            $conn->query("ALTER TABLE inscriptions AUTO_INCREMENT = $new_auto_increment;");

            $_SESSION['message'] = "✅ Élève supprimé.";
        } else {
            $_SESSION['message'] = "❌ Erreur lors de la suppression de l'élève.";
        }

        $stmt->close();
    }

    // Rediriger vers le panneau d'administration
    header("Location: admin_panel.php");
    exit();
}

$conn->close();
?>
