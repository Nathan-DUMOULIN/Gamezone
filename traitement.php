<?php
include("conf.php");
$connexion = mysqli_connect($serveur, $user, $mdp, $nomBdd);
require_once("mailer.php");

$email = $_POST['email'];
$Nom = $_POST['Nom'];
$prenom = $_POST['prenom'];
;


$hash = password_hash($_POST["mdp"], PASSWORD_DEFAULT);

$sql = "insert into {$prefixe}joueur (nom, prenom, email, jeu, mdp) values ('".$Nom."', '".$prenom."', '".$email."',  '".$hash."')";
mysqli_query($connexion, $sql);


$objet = "Confirmation d'inscription";
$message = "Bonjour $prenom $Nom,\n\n";
$message .= "Votre inscription au tournoi GameZone a bien été enregistrée.\n\n";
$message .= "Merci";
envoiMail($email, $objet, $message);

header("Location: index.php");
exit;
?>