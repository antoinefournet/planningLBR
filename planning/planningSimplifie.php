<!--                                * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *             Fichier PHP             *
                                    *     Planning Général Simplifié      *
                                    *                                     *
                                    * * * * * * * * * * * * * * * * * * * *    
-->

<!-- insertion du header avec le logo en haut à gauche -->
<?php include("../include/header.php");

if(!isset($_SESSION['username']))
{
    $_SESSION['error'] = "Vous avez été déconnecté.";
    header('location:../accueil.php');
    die();
}

/* suppression des variables de session */
unset($_SESSION['personne']);
unset($_SESSION['versLieu']); ?>


<!-- style nécessitant du PHP pour la palette de couleur -->
<style>
    table {
        border: 1px solid <?php echo $couleur7 ?>;
        background-color: <?php echo $couleur4 ?>;
        color: <?php echo $couleur7 ?>;
    }

    th,
    td {
        border: 2px solid <?php echo $couleur7 ?>;
    }

    .close {
        color: <?php echo $couleur7 ?>;
    }

    .tabTitre {
        background-color: <?php echo $couleur2 ?>;
        color: <?php echo $couleur4 ?>;
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
        border: 2px solid <?php echo $couleur7 ?>;
    }
</style>

<div class="arrierePlanRectangle">
    <h1 class="titrePrincipal">PLANNING SIMPLIFIé</h1>


    <!-- bouton de redirection vers le planning général -->
    <a class="prev" href='planningGeneral.php' style='color:<?php echo $couleur4 ?>; text-decoration : none'>❮</a>

    <!-- Curseur -->
    <div class="curseurBox">
        <p style="margin-bottom: 0%; text-align: center;"><span id="demo"></span>%</p>
        <button id="zoom0" class="boutonSansFond" style="color: <?php echo $couleur4 ?>;"><i class="fa fa-minus curseurIcone"></i></button>
        <input class="curseur" type="range" min="0.17" max="2" step="0.01" value="0.8" id="myRange" style="background: <?php echo $couleur4 ?>;">
        <button id="zoom1" class="boutonSansFond" style="color: <?php echo $couleur4 ?>;"> <i class="fa fa-plus curseurIcone"></i></button>
    </div>

    <!-- Zoom bloqué à 100 pour sticky la 1e colonne et les 2 1e lignes -->
    <div class="checkBox">
        <input type="checkbox" id="case" onclick="exemple()" value="Case">
        <label>Zoom bloqué 100%</label>
    </div>

    <!-- Rectangle Principal contenant le planning -->
    <div class="rectanglePlanning">
        <form action="actionPlanningGeneral.php" method="post" style="height:100%;">
            <?php include("tabGeneralSimplifie.php") ?>
        </form>
    </div>
</div>

<!-- insertion du footer qui comprend la question des cookies -->
<?php include("../include/footer.php"); ?>