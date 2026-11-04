<?php
session_start();
include("admin_only.php");
include("conf.php");
$connexion = mysqli_connect($serveur, $user, $mdp, $nomBdd);

$tournoi_id = (int) ($_GET["tournoi_id"] ?? 0);

// Nom du tournoi
$stmt = mysqli_prepare($connexion, "SELECT nom FROM {$prefixe}tournoi WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $tournoi_id);
mysqli_stmt_execute($stmt);
$tournoi = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$tournoi) {
    header("Location: tournois.php?msg=introuvable");
    exit;
}

// Inscrits
$sql = "SELECT j.id, j.nom, j.prenom, j.email
        FROM {$prefixe}inscrire i
        INNER JOIN {$prefixe}joueur j ON i.joueur_id = j.id
        WHERE i.tournoi_id = ?";
$stmt = mysqli_prepare($connexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $tournoi_id);
mysqli_stmt_execute($stmt);
$resultat = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscrits</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include("menu.php"); ?>

    <h2>Inscrits : <?php echo htmlspecialchars($tournoi["nom"]); ?></h2>

    <?php
    if (isset($_GET["msg"]) && $_GET["msg"] == "supprime") {
        echo "<p>Inscription supprimée.</p>";
    }
    ?>

    <table border=1>
        <tr><th>id</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Action</th></tr>
        <?php
        $nb = 0;
        while ($j = mysqli_fetch_assoc($resultat)) {
            $nb++;
            echo "<tr>";
            echo "<td>" . $j['id'] . "</td>";
            echo "<td>" . htmlspecialchars($j['nom']) . "</td>";
            echo "<td>" . htmlspecialchars($j['prenom']) . "</td>";
            echo "<td>" . htmlspecialchars($j['email']) . "</td>";
            echo "<td>
                  <form action='supprimer_inscription.php' method='POST' style='display:inline'
                        onsubmit=\"return confirm('Supprimer cette inscription ?');\">
                      <input type='hidden' name='joueur_id' value='" . $j['id'] . "'>
                      <input type='hidden' name='tournoi_id' value='" . $tournoi_id . "'>
                      <input type='submit' value='Supprimer' style='width:auto'>
                  </form>
                  </td>";
            echo "</tr>";
        }
        if ($nb == 0) {
            echo "<tr><td colspan='5'>Aucun inscrit pour le moment.</td></tr>";
        }
        ?>
    </table>

    <p><a href="tournois.php">← Retour aux tournois</a></p>

</body>
</html>