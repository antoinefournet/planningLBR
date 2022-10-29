<!--                                * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *             Fichier CSS             *
                                    *   Redirections du menu principal    *
                                    *                                     *
                                    * * * * * * * * * * * * * * * * * * * *    
-->

<?php include("../include/header.php");

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
?>