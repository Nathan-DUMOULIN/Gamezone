<?php
session_start();
include("conf.php");
$connexion = mysqli_connect($serveur, $user, $mdp, $nomBdd);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAMEZONE</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include("menu.php"); ?>

    <?php
    if (!isset($_SESSION["id"])) {
    ?>

        <h2>Connexion</h2>

                <?php
        if (isset($_GET["reset"])) {
            echo "<p>Mot de passe modifié, vous pouvez vous connecter.</p>";
        }
        ?>

        <?php
        if (isset($_GET["erreur"])) {
            echo "<p>Email ou mot de passe incorrect.</p>";
        }
        ?>

        <form action="traitement_connexion.php" method="POST">
            Email : <br>
            <input type="email" name="email" required><br>

            Mot de passe : <br>
            <input type="password" name="mdp" required><br><br>

            <input type="submit" value="Se connecter">
        </form>

               <p><a href="inscription.php">Pas encore de compte ? S'inscrire</a></p>
        <p><a href="oubli.php">Mot de passe oublié ?</a></p>

    <?php
    }
    ?>

</body>
</html>