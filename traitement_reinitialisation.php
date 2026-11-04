<?php
include("conf.php");
require_once("mailer.php");
$connexion = mysqli_connect($serveur, $user, $mdp, $nomBdd);

$token = $_POST["token"];

// On revérifie le token : le formulaire peut être falsifié
$sql = "select * from {$prefixe}reset_password
        where token = ? and utilise = 0 and expiration > NOW()";
$stmt = mysqli_prepare($connexion, $sql);
mysqli_stmt_bind_param($stmt, "s", $token);
mysqli_stmt_execute($stmt);
$reset = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$reset)
{
    header("Location: oubli.php");
    exit;
}

$hash = password_hash($_POST["mdp"], PASSWORD_DEFAULT);

$sql = "update {$prefixe}joueur set mdp = ? where id = ?";
$stmt = mysqli_prepare($connexion, $sql);
mysqli_stmt_bind_param($stmt, "si", $hash, $reset["joueur_id"]);
mysqli_stmt_execute($stmt);

$sql = "update {$prefixe}reset_password set utilise = 1 where id = ?";
$stmt = mysqli_prepare($connexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $reset["id"]);
mysqli_stmt_execute($stmt);

// Email de confirmation du changement
$sql = "select email, prenom from {$prefixe}joueur where id = ?";
$stmt = mysqli_prepare($connexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $reset["joueur_id"]);
mysqli_stmt_execute($stmt);
$joueur = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$objet = "Votre mot de passe a été modifié";
$message = "Bonjour " . $joueur['prenom'] . ",\n\n";
$message .= "Le mot de passe de votre compte GameZone vient d'être modifié.\n\n";
$message .= "Si vous n'êtes pas à l'origine de ce changement, contactez l'administrateur.";
envoiMail($joueur['email'], $objet, $message);

header("Location: index.php?reset=1");
exit;
?>