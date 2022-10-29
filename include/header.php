<!--                                * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *             Fichier PHP             *
                                    *   <head> à inclure + menu + logo    *
                                    *                                     *
                                    * * * * * * * * * * * * * * * * * * * *    
-->

<!DOCTYPE html>
<html>

<head>
  <title>Planificateur | Festival Les Briques Rouges</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <link rel="icon" type="image/x-icon" href="../images/initiales.ico">

  <!-- regroupement de tous les includes de nos fichiers css -->
  <link rel="stylesheet" href="../css/toutesLesPages.css">
  <link rel="stylesheet" href="../css/accueil_menu.css">
  <link rel="stylesheet" href="../css/menuCarrousel.css">
  <link rel="stylesheet" href="../css/planning_tableau.css">
  <link rel="stylesheet" href="../css/edition_modif.css">

  <link rel="stylesheet" href="../css/pageRectangleJaune.css">
  <link rel="stylesheet" href="../css/pageCarrousel.css">
  <link rel="stylesheet" href="../css/planning.css">
  <link rel="stylesheet" href="../css/pageInitialisationEdition.css">
  <link rel="stylesheet" href="../css/admin.css">
  <link rel="stylesheet" href="../css/lieu.css">
  <link rel="stylesheet" href="../css/ajouterLieu.css">
  <link rel="stylesheet" href="../css/telechargement.css">
</head>

<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include('connexionBDD.php');
include('variablesGlobales.php');
include('alertes.php');

if(isset($_POST['logoRedirection'])) {
  unset($_SESSION['edition']);
  header('location:../edition/menuEdition.php');
  die();
}

class TableauGeneral
{
  public $aJour = false;
  public $planningGeneral = array();
}
?>

<!-- initialisation du fond sombre et de la couleur d'écriture claire -->

<body style="background-color: <?php echo $couleur7 ?>; color: <?php echo $couleur4 ?>;">

  <!-- Logo LBR qui redirige vers le site des briques rouges -->
  <div class="pageHaut pageGauche" style="width:10%; background-color: <?php echo $couleur7 ?>;">
    <form action="../include/header.php" method="post">
      <button type="submit" class="boutonSansFond" name="logoRedirection">
        <img src="../images/LogoFullBlanc.png" style="width:100%" alt="Les Briques Rouges Festival">
      </button>
    </form>
  </div>