<!--                                * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *             Fichier PHP             *
                                    *  Form pour initialiser une édition  *
                                    *                                     *
                                    * * * * * * * * * * * * * * * * * * * *    
-->

<!-- insertion du header avec le logo en haut à gauche -->
<?php include("../include/header.php");

if (!isset($_SESSION['username'])) {
    $_SESSION['error'] = "Vous avez été déconnecté.";
    header('location:../accueil.php');
    die();
}

/* suppression des variables de session */
unset($_SESSION['nomLieu']);
unset($_SESSION['choixCouleur']);
unset($_SESSION['creneauCree']);
unset($_SESSION['idLieuCree']); ?>

<!-- PARTIE VISUELLE DE LA PAGE -->

<form action='actionInitialiserEdition.php' method='post'>
    <div class="arrierePlanRectangle">
        <h1 class="titrePrincipal">INITIALISER MON éDITION</h1>

        <!-- Bouttons flèches-->
        <button class="next boutonSansFond" style="text-decoration:none;" type="submit" name="creerEdition"><i class="fa fa-chevron-right" style="color: <?php echo $couleur4 ?>"></i></button>
        <!-- Première slide titre de l'édition -------------------------------------------------------------------------------->
        <div class="rectangleJaune " style="background-color: <?php echo $couleur4 ?>; color: <?php echo $couleur7 ?>;">
            <h4 class="sousTitre">NOM DE L'éDITON</h4>
            <input class="inputForm grandInputForm" name="nomEdition" type="text" placeholder="Saisir le nom de l'édition" <?php if (isset($_SESSION['nomEdition'])) {
                                                                                                                                echo "value='" . $_SESSION['nomEdition'] . "'";
                                                                                                                            } ?>>

            <div class="positionFormInitialisationSlide1 ">
                <label class="labelForm  ">Durée de l'édition :</label>
                <input class="inputForm " type="number" name="dureeEdition" placeholder="Saisir le nombre en jours" <?php if (isset($_SESSION['dureeEdition'])) {
                                                                                                                        echo "value='" . $_SESSION['dureeEdition'] . "'";
                                                                                                                    } ?>>

                <label class="labelForm ">Date du premier jour de l'édition :</label>
                <input class="inputForm " type="date" name="dateEdition" <?php if (isset($_SESSION['dateEdition'])) {
                                                                                echo "value='" . $_SESSION['dateEdition'] . "'";
                                                                            } ?>>
            </div>
        </div>
        <button type='submit' name='annulerCreation' class="boutonAnnulerInitialisation boutonSansFond" style='color: <?php echo $couleur4 ?>;' type="submit">Annuler la création d'édition</button>
    </div>
</form>

<!-- insertion du footer qui comprend la question des cookies -->
<?php include("../include/footer.php"); ?>