<?php
session_start();
include 'config.php';

// Vérifier si l'admin est connecté
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Récupérer les professeurs
$sql_profs = "SELECT id, identifiant, email FROM professeurs"; // Ajout de l'email dans la sélection
$result_profs = $conn->query($sql_profs);

// Récupérer les élèves
$sql_eleves = "SELECT id, nom, prenom, adresse_email FROM inscriptions";
$result_eleves = $conn->query($sql_eleves);

// Afficher les messages de session si disponibles
if (isset($_SESSION['message'])) {
    echo "<div style='color: green; font-weight: bold;'>" . $_SESSION['message'] . "</div>";
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin_panel.css">
    <title>Panel Admin</title>
</head>
<body>
    <h2>Bienvenue, Admin</h2>
    <div class="logo">
        <img src="logo.png" alt="Logo Admin">
    </div>
    <div class="container">

        <!-- Section Professeurs -->
        <div class="section">
            <h3>Ajouter un Professeur</h3>
            <form action="admin_actions.php" method="post">
                <input type="hidden" name="action" value="add_prof">
                <label>Identifiant :</label>
                <input type="text" name="identifiant" required>
                <label>Mot de passe :</label>
                <input type="password" name="mot_de_passe" required>
                <label>Email :</label>
                <input type="email" name="email" required>
                <button type="submit">Ajouter</button>
            </form>

            <h3>Liste des Professeurs</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Identifiant</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $result_profs->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row["id"]; ?></td>
                        <td><?php echo $row["identifiant"]; ?></td>
                        <td><?php echo $row["email"]; ?></td>
                        <td>
                            <form action="admin_actions.php" method="post" class="delete-form">
                                <input type="hidden" name="action" value="delete_prof">
                                <input type="hidden" name="id" value="<?php echo $row["id"]; ?>">
                                <button type="submit">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <!-- Section Élèves -->
        <div class="section">
            <h3>Ajouter un Élève</h3>
            <form action="admin_actions.php" method="post">
                <input type="hidden" name="action" value="add_eleve">
                <label>Nom :</label>
                <input type="text" name="nom" required>
                <label>Prénom :</label>
                <input type="text" name="prenom" required>
                <label>Email :</label>
                <input type="email" name="adresse_email" required>
                <button type="submit">Ajouter</button>
            </form>

            <h3>Liste des Élèves</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $result_eleves->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row["id"]; ?></td>
                        <td><?php echo $row["nom"]; ?></td>
                        <td><?php echo $row["prenom"]; ?></td>
                        <td><?php echo $row["adresse_email"]; ?></td>
                        <td>
                            <form action="admin_actions.php" method="post" class="delete-form">
                                <input type="hidden" name="action" value="delete_eleve">
                                <input type="hidden" name="id" value="<?php echo $row["id"]; ?>">
                                <button type="submit">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>

    <a href="admin_logout.php">Déconnexion</a>
    <script src="admin_script.js"></script>

</body>

</html>

<?php
$conn->close();
?>
