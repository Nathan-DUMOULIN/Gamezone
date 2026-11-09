<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form action="ajouter_jeu.php" method="post" enctype="multipart/form-data">
    <label>Nom du jeu :</label>
    <input type="text" name="nom" required>
    <?php include("menu.php"); ?>
    <label>Description :</label>
    <textarea name="description"></textarea>

    <label>Logo :</label>
    <input type="file" name="logo" accept="image/*" required>

    <button type="submit" name="envoi">Ajouter le jeu</button>
</form>

<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['type'] != 1) {
    header('Location: connexion.php');
    exit;
}

include("conf.php");
include("admin_only.php");





if (isset($_POST['envoi'])) {
    if (empty($_POST['nom']) || empty($_POST['description'])) {
        die("Veuillez remplir tous les champs.");
    }


if (!isset($_FILES['logo']) || $_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
    die("Erreur lors de l'envoi du fichier.");
}

if ($_FILES['logo']['size'] > 2 * 1024 * 1024) {
    die("Image trop volumineuse.");
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$type = finfo_file($finfo, $_FILES['logo']['tmp_name']);
finfo_close($finfo);

$typesAutorises = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp'
];

if (!isset($typesAutorises[$type])) {
    die("Format d'image non autorisé.");
}

$extension = $typesAutorises[$type];
$nomFichier = uniqid('jeu_', true) . '.' . $extension;

$dossier = 'images/jeux/';
$chemin = $dossier . $nomFichier;


if (!move_uploaded_file(
    $_FILES['logo']['tmp_name'],
    $chemin
)) {
    die("Impossible d'enregistrer l'image.");
}

$connexion = mysqli_connect($serveur, $user, $mdp, $nomBdd);
$stmt = mysqli_prepare($connexion, "INSERT INTO {$prefixe}jeu (nom, description, logo) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($stmt, "sss", $_POST['nom'], $_POST['description'], $nomFichier);
mysqli_stmt_execute($stmt); 
}
d
?> 






    
</body>     
</html>