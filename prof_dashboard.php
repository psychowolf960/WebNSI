<?php
session_start();
include 'config.php';

// Vérifier si le professeur est connecté
if (!isset($_SESSION['prof_id'])) {
    header("Location: prof_login.php");
    exit();
}

$prof_id = $_SESSION['prof_id'];

// Récupérer les élèves assignés au professeur
$sql_eleves = "SELECT e.id, e.nom, e.prenom, e.adresse_email 
               FROM inscriptions e 
               JOIN professeurs_pour_eleves p ON e.id = p.eleve_id 
               WHERE p.professeur_id = ?";
$stmt = $conn->prepare($sql_eleves);
$stmt->bind_param("i", $prof_id);
$stmt->execute();
$result_eleves = $stmt->get_result();

// Récupérer l'emploi du temps du professeur
$sql_emploi_du_temps = "SELECT edt.jour, edt.heure, edt.matiere, e.nom AS eleve_nom, e.prenom AS eleve_prenom 
                        FROM emploi_du_temps edt
                        JOIN inscriptions e ON edt.eleve_id = e.id
                        WHERE edt.professeur_id = ?";
$stmt_edt = $conn->prepare($sql_emploi_du_temps);
$stmt_edt->bind_param("i", $prof_id);
$stmt_edt->execute();
$result_emploi_du_temps = $stmt_edt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="prof_dashboard.css">
    <title>Tableau de Bord Professeur</title>
</head>
<body>
    <nav>
        <a href="prof_account.php">Mon compte</a>
        <a href="prof_logout.php">Déconnexion</a>
    </nav>

    <h2>Tableau de Bord du Professeur</h2>

    <!-- Liste des élèves -->
    <h3>Élèves Assignés</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
        </tr>
        <?php while ($row = $result_eleves->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row["id"]); ?></td>
                <td><?php echo htmlspecialchars($row["nom"]); ?></td>
                <td><?php echo htmlspecialchars($row["prenom"]); ?></td>
                <td><?php echo htmlspecialchars($row["adresse_email"]); ?></td>
            </tr>
        <?php } ?>
    </table>

    <!-- Emploi du temps -->
    <h3>Emploi du Temps</h3>
    <table border="1">
        <tr>
            <th>Élève</th>
            <th>Jour</th>
            <th>Heure</th>
            <th>Matière</th>
        </tr>
        <?php while ($row_edt = $result_emploi_du_temps->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row_edt["eleve_nom"] . " " . $row_edt["eleve_prenom"]); ?></td>
                <td><?php echo htmlspecialchars($row_edt["jour"]); ?></td>
                <td><?php echo htmlspecialchars($row_edt["heure"]); ?></td>
                <td><?php echo htmlspecialchars($row_edt["matiere"]); ?></td>
            </tr>
        <?php } ?>
    </table>

    <!-- Formulaire pour ajouter un emploi du temps -->
    <h3>Ajouter un emploi du temps</h3>
    <form method="post">
        <label for="eleve_id">Élève :</label>
        <select name="eleve_id" required>
            <?php
            $stmt->execute(); // Re-exécuter la requête pour récupérer la liste des élèves
            $result_eleves = $stmt->get_result();
            while ($eleve = $result_eleves->fetch_assoc()) {
                echo "<option value='{$eleve['id']}'>{$eleve['nom']} {$eleve['prenom']}</option>";
            }
            ?>
        </select>

        <label for="jour">Jour :</label>
        <input type="text" name="jour" required>

        <label for="heure">Heure :</label>
        <input type="time" name="heure" required>

        <label for="matiere">Matière :</label>
        <input type="text" name="matiere" required>

        <button type="submit" name="ajouter_edt">Ajouter</button>
    </form>

    <?php
    // Traitement du formulaire
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ajouter_edt"])) {
        $eleve_id = $_POST["eleve_id"];
        $jour = $_POST["jour"];
        $heure = $_POST["heure"];
        $matiere = $_POST["matiere"];

        $sql_insert = "INSERT INTO emploi_du_temps (professeur_id, eleve_id, jour, heure, matiere) 
                       VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iisss", $prof_id, $eleve_id, $jour, $heure, $matiere);

        if ($stmt_insert->execute()) {
            echo "<p style='color: green;'>Emploi du temps ajouté avec succès !</p>";
            echo "<meta http-equiv='refresh' content='1'>"; // Rafraîchir la page après 1s
        } else {
            echo "<p style='color: red;'>Erreur lors de l'ajout.</p>";
        }
        $stmt_insert->close();
    }
    ?>

</body>
</html>

<?php
$stmt->close();
$stmt_edt->close();
$conn->close();
?>
