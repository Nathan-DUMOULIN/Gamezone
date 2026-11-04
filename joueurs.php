<?php
session_start();
if (!isset($_SESSION["id"]) || $_SESSION["type"] != 1) {
    header("Location: index.php");
    exit;
}
include("conf.php");
$connexion = mysqli_connect($serveur, $user, $mdp, $nomBdd);
$resultat = mysqli_query($connexion, "SELECT id, nom, prenom, email FROM {$prefixe}joueur");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Joueurs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include("menu.php"); ?>

    <h2>Liste des joueurs</h2>
    <table border=1>
        <tr><th>id</th><th>nom</th><th>prenom</th><th>email</th></tr>
        <?php
        while ($d = mysqli_fetch_assoc($resultat)) {
            echo "<tr><td>" . $d['id'] . "</td>";
            echo "<td>" . htmlspecialchars($d['nom']) . "</td>";
            echo "<td>" . htmlspecialchars($d['prenom']) . "</td>";
            echo "<td>" . htmlspecialchars($d['email']) . "</td></tr>";
        }
        ?>
    </table>
</body>
</html>