<?php
include("conf.php");
$connexion = mysqli_connect($serveur, $user, $mdp, $nomBdd);
require_once("mailer.php");

$email = $_POST['email'];

$sql = "select id, prenom from {$prefixe}joueur where email = ?";
$stmt = mysqli_prepare($connexion, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$joueur = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if ($joueur)
{
    $token = bin2hex(random_bytes(32));

    $sql = "insert into {$prefixe}reset_password (joueur_id, token, expiration)
            values (?, ?, DATE_ADD(NOW(), INTERVAL 30 MINUTE))";
    $stmt = mysqli_prepare($connexion, $sql);
    mysqli_stmt_bind_param($stmt, "is", $joueur['id'], $token);
    mysqli_stmt_execute($stmt);

    // adapter le nom du dossier :https://www.siolapie.com/27_DUMOU/gamezone/  $lien = "http://localhost/COURS%2001%20GRAVOUIL/gamezone/reinitialisation.php?token=" . urlencode($token);
    $lien = "https://www.siolapie.com/27_DUMOU/gamezone/reinitialisation.php?token=" . urlencode($token);

    $objet = "Réinitialisation de votre mot de passe";
    $message = "Bonjour " . $joueur['prenom'] . ",\n\n";
    $message .= "Pour choisir un nouveau mot de passe, cliquez sur ce lien (valable 30 minutes) :\n";
    $message .= $lien . "\n\n";
    $message .= "Si vous n'êtes pas à l'origine de cette demande, ignorez ce message.";
        $ok = envoiMail($email, $objet, $message);
    die($ok ? "Mail envoyé" : "Échec d'envoi du mail");
}

// Même réponse que l'email existe ou non
header("Location: oubli.php?envoye=1");
exit;
?>