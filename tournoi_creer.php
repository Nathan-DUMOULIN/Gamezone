<?php
session_start();
include("admin_only.php");
include("conf.php");
$connexion = mysqli_connect($serveur, $user, $mdp, $nomBdd);

$erreur = "";
$nom = "";
$date = "";
$jeu_id = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST["nom"]);
    $date = $_POST["date"];
    $jeu_id = (int) $_POST["jeu_id"];

    if ($nom === "" || $date === "" || $jeu_id <= 0) {
        $erreur = "Tous les champs sont obligatoires.";
    } else {
        $stmt = mysqli_prepare($connexion, "INSERT INTO {$prefixe}tournoi (nom, date, jeu_id) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssi", $nom, $date, $jeu_id);
        mysqli_stmt_execute($stmt);
        header("Location: tournois.php?msg=cree");
        exit;
    }
}

$jeux = mysqli_query($connexion, "SELECT id, nom FROM {$prefixe}jeu ORDER BY nom");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un tournoi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include("menu.php"); ?>

    <h2>Créer un tournoi</h2>

    <?php if ($erreur != "") { echo "<p>" . $erreur . "</p>"; } ?>

    <form action="tournoi_creer.php" method="POST">
        Nom : <br>
        <input name="nom" value="<?php echo htmlspecialchars($nom); ?>" required><br>

        Date : <br>
        <input type="date" name="date" value="<?php echo htmlspecialchars($date); ?>" required><br>

        Jeu : <br>
        <select name="jeu_id" required>
            <?php
            foreach ($jeux as $jeu) {
                $sel = ($jeu['id'] == $jeu_id) ? "selected" : "";
                echo "<option value=\"{$jeu['id']}\" $sel>" . htmlspecialchars($jeu['nom']) . "</option>";
            }
            ?>
        </select><br><br>

        <input type="submit" value="Créer le tournoi">
    </form>

    <p><a href="tournois.php">← Retour aux tournois</a></p>

</body>
</html>