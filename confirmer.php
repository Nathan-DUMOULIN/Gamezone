<?php
include("conf.php");
$connexion = mysqli_connect($serveur, $user, $mdp, $nomBdd);
require_once("mailer.php");

$email = $_POST['email'];
$Nom = $_POST['Nom'];
$prenom = $_POST['prenom'];

$hash = password_hash($_POST["mdp"], PASSWORD_DEFAULT);

// Vérifier si l'email existe déjà
$email = mysqli_real_escape_string($connexion, $email);

$verification = "SELECT * FROM {$prefixe}joueur WHERE email = '".$email."'";
$resultat = mysqli_query($connexion, $verification);

if (mysqli_num_rows($resultat) > 0) {

    $texte = "Cette adresse email existe déjà. Veuillez en utiliser une autre.";

} else {

    $sql = "INSERT INTO {$prefixe}joueur (nom, prenom, email, mdp) VALUES ('".$Nom."', '".$prenom."', '".$email."', '".$hash."')";

    $ok = mysqli_query($connexion, $sql);

    if ($ok) {

        $objet = "Confirmation d'inscription";
        $message = "Bonjour $prenom $Nom,\n\n";
        $message .= "Votre compte GameZone a bien été créé. Vous pouvez maintenant vous connecter.\n\n";
        $message .= "Merci";
        envoiMail($email, $objet, $message);

        $texte = "Votre compte a bien été créé, vous pouvez vous connecter.";

    } else {
        $texte = "Erreur lors de l'enregistrement.";
    }
}

mysqli_close($connexion);
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
    <p><?php echo $texte; ?></p>
</body>
</html>