<!--                                * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *             Fichier PHP             *
                                    *   Form pour modifier une édition    *
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
unset($_SESSION['idLieuCree']); ?>

<!-- PARTIE VISUELLE DE LA PAGE -->

<form action='actionModifierEdition.php' method='post'>
  <div class="arrierePlanRectangle">
    <h1 class="titrePrincipal">MODIFIER MON éDITION</h1>

    <!-- Bouttons flèches-->
    <button class="next boutonSansFond" style="text-decoration:none;" type="submit" name="modifierEdition"><i class="fa fa-chevron-right" style="color: <?php echo $couleur4 ?>"></i></button>
    <!-- Première slide titre de l'édition -------------------------------------------------------------------------------->
    <div class="rectangleJaune " style="background-color: <?php echo $couleur4 ?>; color: <?php echo $couleur7 ?>;">
      <h4 class="sousTitre">NOM DE L'éDITON</h4>
      <input class="inputForm grandInputForm" name="nomEdition" type="text" placeholder="Nom de l'édition" <?php if (isset($_SESSION['editionAModifier'])) {
                                                                                                              echo "value='" . $_SESSION['editionAModifier'] . "'";
                                                                                                            } else if (isset($_SESSION['nomEdition'])) {
                                                                                                              echo "value='" . $_SESSION['nomEdition'] . "'";
                                                                                                            }  ?>>

      <div class="positionFormInitialisationSlide1 ">
        <label class="labelForm  ">Durée de l'édition :</label>
        <input class="inputForm " type="number" name="dureeEdition" placeholder="Saisir le nombre en jours" <?php if (isset($_SESSION['editionAModifier'])) {
                                                                                                              $requete1 = "SELECT nbJour FROM edition WHERE idEdition='" . $_SESSION['editionAModifier'] . "'";
                                                                                                              $result1 = mysqli_query($link, $requete1);
                                                                                                              if (mysqli_num_rows($result1) == 1) {
                                                                                                                $row1 = mysqli_fetch_assoc($result1);
                                                                                                                echo "value='" . $row1['nbJour'] . "'";
                                                                                                              }
                                                                                                            } else if (isset($_SESSION['dureeEdition'])) {
                                                                                                              echo "value='" . $_SESSION['dureeEdition'] . "'";
                                                                                                            } ?>>

        <label class="labelForm ">Date du premier jour de l'édition :</label>
        <input class="inputForm " type="date" name="dateEdition" <?php if (isset($_SESSION['editionAModifier'])) {
                                                                    $requete2 = "SELECT dateJ1 FROM edition WHERE idEdition='" . $_SESSION['editionAModifier'] . "'";
                                                                    $result2 = mysqli_query($link, $requete2);
                                                                    if (mysqli_num_rows($result2) == 1) {
                                                                      $row2 = mysqli_fetch_assoc($result2);
                                                                      echo "value='" . $row2['dateJ1'] . "'";
                                                                    }
                                                                  } else if (isset($_SESSION['dateEdition'])) {
                                                                    echo "value='" . $_SESSION['dateEdition'] . "'";
                                                                  } ?>>
        <?php unset($_SESSION['editionAModifier']); ?>
      </div>
    </div>
</form>

<!-- insertion du footer qui comprend la question des cookies -->
<?php include("../include/footer.php"); ?>