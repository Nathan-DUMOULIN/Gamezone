<?php
session_start();
if (!isset($_SESSION["id"])) {
    header("Location: index.php");
    exit;
}
?>

<?php
include("conf.php");
$connexion = mysqli_connect($serveur, $user, $mdp, $nomBdd);
?>

<?php
$requete="Select * from ap3_jeu";
$resultat = mysqli_query($connexion, $requete);
echo "<table border=1><tr>
		<th>id</th>
		<th>nom</th>
		<th>description</th>
		<th>logo</th></tr>";
		
	while($donnees = mysqli_fetch_assoc($resultat))
	{
		echo "<tr><td>".$donnees['id']."</td>" ;
		echo "<td>".$donnees['nom']."</td>" ;
		echo "<td>".htmlspecialchars($donnees['description'])."</td>" ;
		echo "<td><img src='images/jeux/" . htmlspecialchars($donnees['logo']) . "' alt='" . htmlspecialchars($donnees['nom']) . "'></td>" ;

		echo "</tr>" ;
	
	}
    echo "</table>";
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
</body>
</html>