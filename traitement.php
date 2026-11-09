
<?php
include("conf.php");
$connexion = mysqli_connect($serveur, $user, $mdp, $nomBdd);
require_once("mailer.php");

$email = trim($_POST['email']);
$Nom = $_POST['Nom'];
$prenom = $_POST['prenom'];
$jeu = $_POST['jeu'];

// Vérifier si l'email existe déjà
$stmt = mysqli_prepare($connexion,
    "SELECT id FROM {$prefixe}joueur WHERE email = ?");

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    echo "Erreur : un joueur avec cet email existe déjà.
    <a href='index.php'>Retour</a>";
    exit;
}

mysqli_stmt_close($stmt);

// Création du mot de passe hashé
$hash = password_hash($_POST["mdp"], PASSWORD_DEFAULT);

// Inscription du joueur
$stmt = mysqli_prepare($connexion,
    "INSERT INTO {$prefixe}joueur (nom, prenom, email, jeu, mdp)
     VALUES (?, ?, ?, ?, ?)");

mysqli_stmt_bind_param($stmt, "sssss",
    $Nom, $prenom, $email, $jeu, $hash);

if (mysqli_stmt_execute($stmt)) {

    // Envoi du mail de confirmation
    $objet = "Confirmation d'inscription";

    $message = "Bonjour $prenom $Nom,\n\n";
    $message .= "Votre inscription au tournoi GameZone a bien été enregistrée.\n\n";
    $message .= "Merci";

    envoiMail($email, $objet, $message);

    header("Location: index.php");
    exit;

} else {
    echo "Erreur lors de l'inscription : " . mysqli_error($connexion);
}

mysqli_stmt_close($stmt);
mysqli_close($connexion);
?>
