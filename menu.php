<nav>
   
    <a class="bouton" href="jeux.php">Jeux</a>
    <a class="bouton" href="tournois.php">Tournois</a>
    <?php if (isset($_SESSION["id"])) { ?>
        <?php if ($_SESSION["type"] == 1) { ?>
            <a class="bouton" href="joueurs.php">Joueurs inscrits</a>
            <a class="bouton" href="tournois.php">ADMIN</a>
            <a class="bouton" href="ajouter_jeu.php">Ajouter un jeu</a>
        <?php } ?>
       
        <a class="bouton" href="deconnexion.php">Déconnexion</a>
    <?php } ?>
</nav>