
<?php
include("conf.php");
$connexion = mysqli_connect($serveur, $user, $mdp, $nomBdd);
require_once("mailer.php");

$email = $_POST['email'];
$Nom = $_POST['Nom'];
$prenom = $_POST['prenom'];

// Vérifier si l'email existe déjà
$stmt = mysqli_prepare($connexion, "SELECT id FROM ap3_joueur WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

if (mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))) {
    echo "Erreur : un joueur avec cet email existe déjà. <a href='index.php'>Retour</a>";
    exit;
}

// Création du mot de passe hashé
$hash = password_hash($_POST["mdp"], PASSWORD_DEFAULT);

// Inscription du joueur
$sql = "INSERT INTO {$prefixe}joueur (nom, prenom, email, jeu, mdp) 
        VALUES ('".$Nom."', '".$prenom."', '".$email."', '".$jeu."', '".$hash."')";

mysqli_query($connexion, $sql);

// Envoi du mail de confirmation
$objet = "Confirmation d'inscription";
$message = "Bonjour $prenom $Nom,\n\n";
$message .= "Votre inscription au tournoi GameZone a bien été enregistrée.\n\n";
$message .= "Merci";

envoiMail($email, $objet, $message);

header("Location: index.php");
exit;
?>
