<?php
include("conf.php");
$connexion = mysqli_connect($serveur, $user, $mdp, $nomBdd);
require_once("mailer.php");

$email = $_POST['email'];
$Nom = $_POST['Nom'];
$prenom = $_POST['prenom'];
$jeu = $_POST['jeu'];

$hash = password_hash($_POST["mdp"], PASSWORD_DEFAULT);

// Vérifier si l'email existe déjà
$verification = "SELECT email FROM {$prefixe}joueur WHERE email = '".$email."'";
$resultat = mysqli_query($connexion, $verification);

if (mysqli_num_rows($resultat) > 0) {
    echo "Cette adresse email existe déjà.";
    exit;
} else {

    $sql = "INSERT INTO {$prefixe}joueur (nom, prenom, email, jeu, mdp) VALUES ('".$Nom."', '".$prenom."', '".$email."', '".$jeu."', '".$hash."')";
    mysqli_query($connexion, $sql);

    $objet = "Confirmation d'inscription";
    $message = "Bonjour $prenom $Nom,\n\n";
    $message .= "Votre inscription au tournoi GameZone a bien été enregistrée.\n\n";
    $message .= "Merci";
    envoiMail($email, $objet, $message);

    header("Location: index.php");
    exit;
}
?>