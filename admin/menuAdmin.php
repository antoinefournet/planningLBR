<!--                                * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *             Fichier PHP             *
                                    *       Menu des comptes Admin        *
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
unset($_SESSION['compteAModifier']);

if (isset($_POST['modifier'])) {
    /* si détection du bouton modifier : récupération du nom du compte à modifier */
    $_SESSION['compteAModifier'] = $_POST['modifier'];
    header('location:modifierAdmin.php');
    die();
}

if (isset($_POST['supprimer'])) {
    /* si détection du bouton supprimer : récupération du nom du compte à supprimer */
    $_SESSION['compteASupprimer'] = $_POST['supprimer'];

    $requete1 = "SELECT idAdmin FROM admin";
    $result1 = mysqli_query($link, $requete1);
    /* s'il ne reste qu'un seul compte */
    if (mysqli_num_rows($result1) == 1) {
        /* modal pour la confirmation de la suppression du compte */ ?>
        <form action='menuAdmin.php' method='post'>
            <div id='alertSuppression' style='background-color: <?php echo $couleur7 ?>;'>
                <p style='text-align:center'>Impossible de supprimer le compte <span style='color: <?php echo $couleur2 ?>'><?php echo $_POST['supprimer']  ?></span> : c'est le dernier !</p>
                <input id='ok' name='ok' type='submit' value='Okay' class='boutonConfirmSuppr' style='margin: 4% 40%; color: <?php echo $couleur4 ?>; border: 2px solid <?php echo $couleur2 ?>;'>
            </div>
        </form>
    <?php } else if (mysqli_num_rows($result1) > 1) {
        /* modal pour la confirmation de la suppression du compte */ ?>
        <form action='menuAdmin.php' method='post'>
            <div id='alertSuppression' style='background-color: <?php echo $couleur7 ?>;'>
                <p style='text-align:center'>Veux-tu vraiment supprimer le compte <span style='color: <?php echo $couleur2 ?>'><?php echo $_POST['supprimer']  ?></span> ?</p>
                <input id='oui' name='oui' type='submit' value='Oui' class='boutonConfirmSuppr' style='margin: 4% 20%; color: <?php echo $couleur4 ?>; border: 2px solid <?php echo $couleur2 ?>;'>
                <input id='non' name='non' type='submit' value='Non' class='boutonConfirmSuppr' style='color: <?php echo $couleur4 ?>;border: 2px solid <?php echo $couleur2 ?>;'>
            </div>
        </form>
<?php } else {
    }
}

if (isset($_POST['oui'])) {
    /* si l'utilisateur souhaite supprimer un compte Admin : modification dans la BDD */
    $requete = "DELETE FROM admin WHERE idAdmin='" . $_SESSION['compteASupprimer'] . "'";
    $result = mysqli_query($link, $requete);

    if ($result == true) {
        /* si BDD mise à jour : redirection vers le menu des comptes Admin + alerte */
        $_SESSION['success'] = "Le compte " . $_SESSION['compteASupprimer'] . " a bien été supprimé.";
        if ($_SESSION['compteASupprimer'] == $_SESSION['username']) {
            header('location:../accueil.php');
            die();
        }
    } else {
        /* si erreur de mise à jour de la BDD : redirection vers le menu des comptes Admin + alerte */
        $_SESSION['error'] = "Le compte n'a pas pu être supprimé.";
    }
    unset($_SESSION['compteASupprimer']);
    header('location:menuAdmin.php');
    die();
}
if (isset($_POST['non']) || isset($_POST['ok'])) {
    unset($_SESSION['compteASupprimer']);
}

/* gestion des boutons se connecter et se déconnecter : redirection vers l'accueil */
if (isset($_POST['connecter'])) {
    if ($_POST['connecter'] == $_SESSION['username']) {
        header('location:../accueil.php');
        die();
    } else {
        $_SESSION['uname'] = $_POST['connecter'];
        header('location:../accueil.php');
        die();
    }
}

/* gestion du bouton + : redirection vers la page Création Admin */
if (isset($_POST['nouveauCompte'])) {
    unset($_SESSION['uname']);
    header('location:creerAdmin.php');
    die();
} ?>

<!-- STYLES AU HOVER DE LA PAGE -->
<style>
    /* Au passage de la souris, changement de style des boutons */
    .prev:hover,
    .next:hover {
        background-color: <?php echo $couleur7 ?>;
    }
</style>

<!-- PARTIE VISUELLE DE LA PAGE -->

<div class="arrierePlanCarrousel">
    <h1 class='titrePrincipal'>Gérer les admins</h1>

    <div class="carrouselInterieur">

        <!-- Formulaire contenant les boutons de suppression, modification, ajout et redirection -->
        <?php $requete = "SELECT idAdmin FROM admin ORDER BY idAdmin ASC";
        $result = mysqli_query($link, $requete);
        echo "<form action='menuAdmin.php' method='post'>";

        if (mysqli_num_rows($result) > 0) {
            /* si au moins un compte Admin a été détecté */
            echo "<div class='slide slideAdmin'>"; ?>
            <!-- le bouton + qui s'ajoute à la suite -->
            <div style='margin:auto'>
                <button type='submit' name='nouveauCompte' class='boutonSansFond boutonSelectAdmin decalageBoutonSelectAdmin' style='background: <?php echo $couleur2 ?>;'>
                    <i class='fa fa-plus iconePlusAdmin' style='color: <?php echo $couleur1 ?>;'></i>
                </button>
            </div>
            <?php $i = 1;
            while ($row = mysqli_fetch_assoc($result)) { ?>
                <?php if ($i == 3) {
                    $i = 1; ?>
    </div>
    <div class='slide slideAdmin'>
    <?php } else {
                    $i++;
                } ?>
    <!-- tant qu'il y a des comptes : création des boutons -->
    <div style='margin:auto'>
        <div class='boutonSelectAdmin boutonSansFond' style='text-align:center;background: <?php echo $couleur2 ?>;'>
            <div class='containerBoutonAdmin'>
                <button type='submit' name='modifier' value='<?php echo $row['idAdmin'] ?>' class='boutonSansFond' style='color:<?php echo $couleur1 ?>;'>
                    <i class='fa fa-edit'></i>
                </button>
                <button type='submit' name='supprimer' value='<?php echo $row['idAdmin'] ?>' class='supprimer boutonSansFond' style='color:<?php echo $couleur1 ?>;'>
                    <i class='fa fa-trash'></i>
                </button>
            </div>
            <div class='ecritureAdmin aSansFond positionDansBoutonAdmin' style='font-size:22px; color:<?php echo $couleur4 ?>;'>
                <?php
                $nomAdmin = explode('@', $row['idAdmin']);
                echo $nomAdmin['0'] . " @" . $nomAdmin['1'];
                ?>
            </div>
            <?php if ($_SESSION['username'] == $row['idAdmin']) {
                    $connecter = "Me déconnecter";
                } else {
                    $connecter = "Me connecter";
                } ?>
            <button type='submit' name='connecter' class='boutonSansFond aSansFond positionDansBoutonAdmin' style='color:<?php echo $couleur4 ?>;' value='<?php echo $row['idAdmin'] ?>'> <?php echo $connecter ?></button>
        </div>
    </div>
<?php }
        } else { ?>
<!-- si aucun compte Admin : affichage du bouton + seul -->
<div style='margin:auto'>
    <button type='submit' name='nouveauCompte' class='boutonSansFond boutonSelectAdmin decalageBoutonSelectAdmin' style='background: <?php echo $couleur2 ?>;'>
        <i class='fa fa-plus iconePlusAdmin' style='color: <?php echo $couleur1 ?>;'></i>
    </button>
</div>
<?php } ?>
</form>
    </div>

    <!-- boutons de déplacement au sein du carrousel -->
    <a class="prev" onclick="plusSlides(-1)" style="color: <?php echo $couleur4 ?>;">❮</a>
    <a class="next" onclick="plusSlides(1)" style="color: <?php echo $couleur4 ?>;">❯</a>
</div>
</div>

<!-- insertion du script pour les fonctions du carrousel -->
<script type="text/javascript" src="../js/adminCarrousel.js"></script>

<!-- insertion du footer qui comprend la question des cookies -->
<?php include("../include/footer.php"); ?>