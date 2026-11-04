<?php
session_start();
include("conf.php");
$connexion = mysqli_connect($serveur, $user, $mdp, $nomBdd);

$email = $_POST['email'];

$sql = "select * from {$prefixe}joueur where email = '".$email."'";
$resultat = mysqli_query($connexion, $sql);
$joueur = mysqli_fetch_assoc($resultat);

if ($joueur && password_verify($_POST['mdp'], $joueur['mdp']))
{
    
    $_SESSION['id'] = $joueur['id'];
    $_SESSION['type'] = $joueur['type'];
    $_SESSION['nom'] = $joueur['nom'];
    header("Location: jeux.php");
    exit;
}
else
{
    
    header("Location: index.php?erreur=1");
    exit;
}
?>