<?php
session_start();
include 'config.php';

// Vérifie si le professeur est connecté
if (!isset($_SESSION["prof_id"])) {
    die("Erreur : Professeur non connecté.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $eleve_id = $_POST["eleve_id"];
    $jour = $_POST["jour"];
    $heure = $_POST["heure"];
    $matiere = $_POST["matiere"];
    $prof_id = $_SESSION["prof_id"];

    // Vérifier si les valeurs ne sont pas vides
    if (empty($eleve_id) || empty($jour) || empty($heure) || empty($matiere)) {
        die("Erreur : Veuillez remplir tous les champs.");
    }

    // Préparer la requête SQL
    $sql = "INSERT INTO emploi_du_temps_eleves (eleve_id, professeur_id, jour, heure, matiere) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("Erreur de préparation : " . $conn->error);
    }

    // Liaison des paramètres
    $stmt->bind_param("iisss", $eleve_id, $prof_id, $jour, $heure, $matiere);

    // Exécuter la requête et vérifier si elle fonctionne
    if ($stmt->execute()) {
        echo "Cours ajouté avec succès.";
    } else {
        echo "Erreur d'insertion : " . $stmt->error;
    }

    // Fermer la connexion
    $stmt->close();
    $conn->close();
}
?>
