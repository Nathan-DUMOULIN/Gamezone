<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include("menu.php"); ?>

    <h2>Créer mon compte</h2>

    <form action="confirmer.php" method="POST">
        Nom : <br>
        <input name="Nom" required><br>

        Prénom : <br>
        <input name="prenom" required><br>

        Email : <br>
        <input type="email" name="email" required><br>

        Mot de passe : <br>
        <input type="password" name="mdp" required><br><br>

        <input type="submit" value="Créer mon compte">
    </form>

</body>
</html>