<?php
$servername = "localhost";
$username = "root";
$password = "";
// Attention a mettre le bon nom de BDD
$dbname = "ecole_musique_era";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
