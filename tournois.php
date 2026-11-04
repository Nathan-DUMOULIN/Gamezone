<?php
session_start();
include("conf.php");
$connexion = mysqli_connect($serveur, $user, $mdp, $nomBdd);

$estConnecte = isset($_SESSION["id"]);
$estAdmin = $estConnecte && $_SESSION["type"] == 1;

$requete = "SELECT t.id, t.nom, t.date, j.nom AS jeu
            FROM {$prefixe}tournoi t
            INNER JOIN {$prefixe}jeu j ON t.jeu_id = j.id
            ORDER BY t.date";
$resultat = mysqli_query($connexion, $requete);

$messages = [
    "inscrit"     => "Inscription enregistrée.",
    "deja"        => "Vous êtes déjà inscrit à ce tournoi.",
    "introuvable" => "Tournoi introuvable.",
    "cree"        => "Tournoi créé.",
    "modifie"     => "Tournoi modifié.",
    "supprime"    => "Tournoi supprimé."
];
$msg = $_GET["msg"] ?? "";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tournois</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include("menu.php"); ?>

    <h2>Les tournois</h2>

    <?php
    if (isset($messages[$msg])) {
        echo "<p>" . $messages[$msg] . "</p>";
    }
    if ($estAdmin) {
        echo "<p><a href='tournoi_creer.php'>+ Créer un tournoi</a></p>";
    }
    ?>

    <table border=1>
        <tr>
            <th>id</th>
            <th>Tournoi</th>
            <th>Date</th>
            <th>Jeu</th>
            <?php if ($estConnecte) { echo "<th>Actions</th>"; } ?>
        </tr>
        <?php
        while ($t = mysqli_fetch_assoc($resultat)) {
            echo "<tr>";
            echo "<td>" . $t['id'] . "</td>";
            echo "<td>" . htmlspecialchars($t['nom']) . "</td>";
            echo "<td>" . date("d/m/Y", strtotime($t['date'])) . "</td>";
            echo "<td>" . htmlspecialchars($t['jeu']) . "</td>";

            if ($estConnecte) {
                echo "<td>";
                echo '<a href="inscrire.php?tournoi_id=' . $t["id"] . '">S’inscrire</a>';

                if ($estAdmin) {
                    echo ' | <a href="inscrits.php?tournoi_id=' . $t["id"] . '">Inscrits</a>';
                    echo ' | <a href="tournoi_modifier.php?id=' . $t["id"] . '">Modifier</a> ';
                    echo "<form action='tournoi_supprimer.php' method='POST' style='display:inline'
                          onsubmit=\"return confirm('Supprimer ce tournoi et ses inscriptions ?');\">
                          <input type='hidden' name='id' value='" . $t['id'] . "'>
                          <input type='submit' value='Supprimer' style='width:auto'>
                          </form>";
                }
                echo "</td>";
            }
            echo "</tr>";
        }
        ?>
    </table>

</body>
</html>