<?php
session_start();
include("conf.php");
$connexion = mysqli_connect($serveur, $user, $mdp, $nomBdd);

$token = $_GET["token"] ?? "";

$sql = "select * from {$prefixe}reset_password
        where token = ? and utilise = 0 and expiration > NOW()";
$stmt = mysqli_prepare($connexion, $sql);
mysqli_stmt_bind_param($stmt, "s", $token);
mysqli_stmt_execute($stmt);
$reset = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include("menu.php"); ?>

    <h2>Nouveau mot de passe</h2>

    <?php
    if (!$reset) {
        echo "<p>Lien invalide, expiré ou déjà utilisé.</p>";
        echo "<a href='oubli.php'>Faire une nouvelle demande</a>";
    } else {
    ?>
        <form action="traitement_reinitialisation.php" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

            Nouveau mot de passe : <br>
            <input type="password" name="mdp" required><br><br>

            <input type="submit" value="Valider">
        </form>
    <?php
    }
    ?>

</body>
</html>