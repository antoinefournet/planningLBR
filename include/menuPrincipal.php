<!--                                * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *             Fichier PHP             *
                                    *   menu principal include partout    *
                                    *                                     *
                                    * * * * * * * * * * * * * * * * * * * *    
-->

<?php
if (isset($_POST['planningLieu'])) {
  if (isset($_SESSION['edition'])) {
    $requete1 = "SELECT * FROM exister WHERE idEdition='" . $_SESSION['edition'] . "'";
    $result1 = mysqli_query($link, $requete1);
    if (mysqli_num_rows($result1) > 0) {
      $row1 = mysqli_fetch_assoc($result1);
      $_SESSION['versLieu'] = $row1['idLieu'];
      header('location:../planning/planningLieu.php');
      die();
    } else {
      $_SESSION['error'] = "Vous ne pouvez pas accéder à cette page car vous n'avez pas encore créer de lieu pour cette édition.";
      header('location:../planning/planningGeneral.php');
      die();
    }
  } else {
    $_SESSION['error'] = "Un problème est survenu.";
    header('location:../edition/menuEdition.php');
    die();
  }
}
if (isset($_POST['modifierEdition'])) {
  $_SESSION['editionAModifier'] = $_SESSION['edition'];
  header('location:../edition/modifierEditionInfo.php');
  die();
}
if (isset($_POST['quitterEdition'])) {
  unset($_SESSION['edition']);
  header('location:../edition/menuEdition.php');
  die();
}
if (isset($_SESSION["edition"])) { ?>

  <!-- STYLE AU SURVOL -->

  <style>
    /* quand on survole une proposition du menu */
    .overlayMenu a:hover,
    .overlayMenu a:focus {
      color: <?php echo $couleur4 ?>;
    }

    .overlayMenu button:hover,
    .overlayMenu button:focus {
      color: <?php echo $couleur4 ?>;
    }
  </style>

  <!-- PARTIE VISUELLE DE L'INTERFACE -->

  <!-- menu qui recouvre la page -->
  <div id="menuNav" class="overlayMenu">
    <a href="javascript:void(0)" class="closebtn aSansFond" onclick="closeNav()">&times;</a>

    <div class="overlayMenu-content">

      <div class="decoMenu">Menu</div>
      <form action="../include/actionMenu.php" method="post">

        <ol class="choixMenu">
          <a href="../planning/planningGeneral.php" class="propositionMenu" style="border-bottom: 1px solid <?php echo $couleur4 ?>;">
            <span class="spanMenu">I</span>
            Planning Général
          </a>
          <button type="submit" name="planningLieu" class="boutonSansFond propositionMenu" style="display: flex;border-style: none none solid none;">
            <span class="spanMenu">II</span>
            Planning par Lieu
          </button>
          <a href="../personnes/listePersonnes.php" class="propositionMenu" style="border-bottom: 1px solid <?php echo $couleur4 ?>;">
            <span class="spanMenu">III</span>
            Liste des Personnes
          </a>
          <a href="../admin/menuAdmin.php" class="propositionMenu" style="border-bottom: 1px solid <?php echo $couleur4 ?>;">
            <span class="spanMenu">IV</span>
            Gérer les admins
          </a>
          <button type="submit" name="modifierEdition" class="boutonSansFond propositionMenu" style="display: flex;border-style: none none solid none;">
            <span class="spanMenu">V</span>
            Modifier l'édition
          </button>
          <button type="submit" name="quitterEdition" class="boutonSansFond propositionMenu" style="display: flex;border-style: none none solid none;">
            <span class="spanMenu">VI</span>
            Quitter l'édition
          </button>
        </ol>

      </form>

      <div class="imageMenu">
        <img src="../images/affiche.jpg" style="max-width:98%;"></img>
      </div>

    </div>
  </div>

  <!-- Accès au menu -->
  <div class="pageHaut pageDroite" style="background-color: <?php echo $couleur7 ?>;">
    <span class="accesMenu" style="color: <?php echo $couleur4 ?>;" onclick="openNav()">&#9776; MENU</span>
  </div>
<?php } ?>

<!-- JS DE LA PAGE -->

<script>
  function openNav() { // ouvrir le menu de navigation
    document.getElementById("menuNav").style.width = "100%";
  }

  function closeNav() { // fermer le menu de navigation
    document.getElementById("menuNav").style.width = "0%";
  }
</script>