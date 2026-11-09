<?php
session_start();
include("conf.php");
if (!isset($_SESSION['id'])) {
    header('Location: connexion.php');
    exit;
}

$idJoueur = $_SESSION['id'];

$requete = $bdd->prepare(
    "SELECT id, nom, prenom, email
     FROM {$prefixe}joueur
     WHERE id = :id"
);

$requete->execute([':id' => $idJoueur]);
$joueur = $requete->fetch(PDO::FETCH_ASSOC);

echo htmlspecialchars($joueur['nom']);
echo htmlspecialchars($joueur['prenom']);
echo htmlspecialchars($joueur['email']);

$requete = $bdd->prepare(
    "UPDATE {$prefixe}joueur
     SET nom = :nom,
         prenom = :prenom,
         email = :email
     WHERE id = :id"
);

$requete->execute([
    ':nom' => $_POST['nom'],
    ':prenom' => $_POST['prenom'],
    ':email' => $_POST['email'],
    ':id' => $idJoueur
]);

$hash = password_hash($_POST['nouveau_mdp'], PASSWORD_DEFAULT);

$requete = $bdd->prepare(
    "UPDATE {$prefixe}joueur
     SET mdp = :mdp
     WHERE id = :id"
);

$requete->execute([
    ':mdp' => $hash,
    ':id' => $idJoueur
]);

$requete = $bdd->prepare(
    "SELECT t.id, t.nom, t.date, j.nom AS jeu
     FROM {$prefixe}inscrire i
     INNER JOIN {$prefixe}tournoi t ON i.tournoi_id = t.id
     INNER JOIN {$prefixe}jeu j ON t.jeu_id = j.id
     WHERE i.joueur_id = :id
     ORDER BY t.date"
);

$requete->execute([':id' => $idJoueur]);
$tournois = $requete->fetchAll(PDO::FETCH_ASSOC);
$requete = $bdd->prepare(
    "DELETE FROM {$prefixe}inscrire
     WHERE joueur_id = :joueur
       AND tournoi_id = :tournoi"
);

$requete->execute([
    ':joueur' => $idJoueur,
    ':tournoi' => $_POST['tournoi_id']
]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
        <link rel="stylesheet" href="style.css">

</head>
<body>

     <?php include("menu.php"); ?>
<form action="desinscription.php" method="post">
    <input type="hidden" name="tournoi_id" value="<?= (int)$tournoi['id'] ?>">
    <button type="submit">Se désinscrire</button>
</form>

    
</body>
</html>