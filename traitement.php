<?php
session_start();
include("conf.php");
require_once("mailer.php");

$nom = $_POST['Nom'];
$prenom = $_POST['prenom'];
$email = $_POST['email'];
$mdpHash = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

$connexion = mysqli_connect($serveur, $user, $mdp, $nomBdd);

if (!$connexion) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}

// Vérifier si l'email existe déjà
$verification = "SELECT email FROM {$prefixe}joueur WHERE email = ?";
$stmt = mysqli_prepare($connexion, $verification);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$resultat = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($resultat) > 0) {
    $texte = "Cette adresse email existe déjà. Veuillez utiliser une autre adresse.";
} else {

    $requete = "INSERT INTO {$prefixe}joueur (nom, prenom, email, mdp) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($connexion, $requete);
    mysqli_stmt_bind_param($stmt, "ssss", $nom, $prenom, $email, $mdpHash);
    $ok = mysqli_stmt_execute($stmt);

    if ($ok) {
        $objet = "Confirmation d'inscription";
        $message = "Bonjour $prenom $nom,\n\n";
        $message .= "Votre compte GameZone a bien été créé. Vous pouvez maintenant vous inscrire aux tournois.\n\n";
        $message .= "Merci";
        envoiMail($email, $objet, $message);
        $texte = "Votre compte a bien été créé, vous pouvez vous connecter.";
    } else {
        $texte = "Erreur lors de l'enregistrement.";
    }

    mysqli_stmt_close($stmt);
}

mysqli_stmt_close($stmt);
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