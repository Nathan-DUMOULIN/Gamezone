<?php
session_start();
if (!isset($_SESSION["id"])) {
    header("Location: index.php");
    exit;
}
include("conf.php");
$connexion = mysqli_connect($serveur, $user, $mdp, $nomBdd);

$joueur_id = (int) $_SESSION["id"];
$tournoi_id = (int) ($_GET["tournoi_id"] ?? 0);

// 1. Le tournoi existe-t-il ?
$stmt = mysqli_prepare($connexion, "SELECT id FROM {$prefixe}tournoi WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $tournoi_id);
mysqli_stmt_execute($stmt);
if (!mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))) {
    header("Location: tournois.php?msg=introuvable");
    exit;
}

// 2. Déjà inscrit ?
$stmt = mysqli_prepare($connexion, "SELECT * FROM {$prefixe}inscrire WHERE joueur_id = ? AND tournoi_id = ?");
mysqli_stmt_bind_param($stmt, "ii", $joueur_id, $tournoi_id);
mysqli_stmt_execute($stmt);
if (mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))) {
    header("Location: tournois.php?msg=deja");
    exit;
}

// 3. Inscription
$stmt = mysqli_prepare($connexion, "INSERT INTO {$prefixe}inscrire (joueur_id, tournoi_id) VALUES (?, ?)");
mysqli_stmt_bind_param($stmt, "ii", $joueur_id, $tournoi_id);
mysqli_stmt_execute($stmt);

header("Location: tournois.php?msg=inscrit");
exit;
?>