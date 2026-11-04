<?php
session_start();
include("admin_only.php");
include("conf.php");
$connexion = mysqli_connect($serveur, $user, $mdp, $nomBdd);

// Cette page n'accepte que le bouton "Supprimer" (méthode POST)
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: tournois.php");
    exit;
}

$id = (int) $_POST["id"];

mysqli_begin_transaction($connexion);

try {
    // 1. D'abord les inscriptions de ce tournoi (clé étrangère)
    $stmt = mysqli_prepare($connexion, "DELETE FROM {$prefixe}inscrire WHERE tournoi_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    // 2. Puis le tournoi
    $stmt = mysqli_prepare($connexion, "DELETE FROM {$prefixe}tournoi WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    mysqli_commit($connexion);
    header("Location: tournois.php?msg=supprime");
} catch (Exception $e) {
    // En cas de problème, on annule tout : ni tournoi ni inscriptions supprimés à moitié
    mysqli_rollback($connexion);
    header("Location: tournois.php?msg=introuvable");
}
exit;
?>