<?php
session_start();
include("admin_only.php");
include("conf.php");
$connexion = mysqli_connect($serveur, $user, $mdp, $nomBdd);

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: tournois.php");
    exit;
}

$joueur_id = (int) $_POST["joueur_id"];
$tournoi_id = (int) $_POST["tournoi_id"];

$stmt = mysqli_prepare($connexion, "DELETE FROM {$prefixe}inscrire WHERE joueur_id = ? AND tournoi_id = ?");
mysqli_stmt_bind_param($stmt, "ii", $joueur_id, $tournoi_id);
mysqli_stmt_execute($stmt);

header("Location: inscrits.php?tournoi_id=" . $tournoi_id . "&msg=supprime");
exit;
?>