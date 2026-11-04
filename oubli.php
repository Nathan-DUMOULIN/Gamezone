<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include("menu.php"); ?>

    <h2>Mot de passe oublié</h2>

    <?php
    if (isset($_GET["envoye"])) {
        echo "<p>Si cette adresse correspond à un compte, un email contenant un lien de réinitialisation vient d'être envoyé.</p>";
    }
    ?>

    <form action="traitement_oubli.php" method="POST">
        Email : <br>
        <input type="email" name="email" required><br><br>
        <input type="submit" value="Envoyer le lien">
    </form>

</body>
</html>