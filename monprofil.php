
<?php
session_start();
require_once("conf.php");
$connexion = mysqli_connect($serveur, $user, $mdp, $nomBdd);

if (!isset($_SESSION['id'])) {
    header('Location: connexion.php');
    exit;
}

$idJoueur = (int)$_SESSION['id'];

// Désinscription d'un tournoi
if (isset($_POST['desinscrire'])) {
    $idTournoi = (int)$_POST['tournoi_id'];

    mysqli_query($connexion,
        "DELETE FROM {$prefixe}inscrire
         WHERE joueur_id = $idJoueur
         AND tournoi_id = $idTournoi");

    header("Location: profil.php");
    exit;
}

if (isset($_POST['modifier_profil'])) {
    $nom = mysqli_real_escape_string($connexion, $_POST['nom']);
    $prenom = mysqli_real_escape_string($connexion, $_POST['prenom']);
    $email = mysqli_real_escape_string($connexion, $_POST['email']);

    mysqli_query($connexion,
        "UPDATE {$prefixe}joueur
         SET nom = '$nom', prenom = '$prenom', email = '$email'
         WHERE id = $idJoueur");

    if (!empty($_POST['nouveau_mdp'])) {
        $hash = password_hash($_POST['nouveau_mdp'], PASSWORD_DEFAULT);
        mysqli_query($connexion,
            "UPDATE {$prefixe}joueur
             SET mdp = '$hash'
             WHERE id = $idJoueur");
    }
}

$requete = mysqli_query($connexion,
    "SELECT id, nom, prenom, email
     FROM {$prefixe}joueur
     WHERE id = $idJoueur");
$joueur = mysqli_fetch_assoc($requete);

$requete = mysqli_query($connexion,
    "SELECT t.id, t.nom, t.date, j.nom AS jeu
     FROM {$prefixe}inscrire i
     INNER JOIN {$prefixe}tournoi t ON i.tournoi_id = t.id
     INNER JOIN {$prefixe}jeu j ON t.jeu_id = j.id
     WHERE i.joueur_id = $idJoueur
     ORDER BY t.date");
$tournois = mysqli_fetch_all($requete, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include("menu.php"); ?>

<h1>Mon profil</h1>

<form method="post">
    <input type="text" name="nom" value="<?= htmlspecialchars($joueur['nom']) ?>">
    <input type="text" name="prenom" value="<?= htmlspecialchars($joueur['prenom']) ?>">
    <input type="email" name="email" value="<?= htmlspecialchars($joueur['email']) ?>">
    <input type="password" name="nouveau_mdp" placeholder="Nouveau mot de passe (optionnel)">
    <button type="submit" name="modifier_profil">Enregistrer</button>
</form>

<h2>Mes tournois</h2>

<?php foreach ($tournois as $tournoi): ?>
    <p>
        <?= htmlspecialchars($tournoi['nom']) ?>
        (<?= htmlspecialchars($tournoi['jeu']) ?>)
        le <?= htmlspecialchars($tournoi['date']) ?>
    </p>

    <form action="monprofil.php" method="post">
        <input type="hidden" name="tournoi_id" value="<?= (int)$tournoi['id'] ?>">
        <button type="submit" name="desinscrire">Se désinscrire</button>
    </form>

<?php endforeach; ?>

</body>
</html>
