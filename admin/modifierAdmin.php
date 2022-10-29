<!--                                * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *             Fichier PHP             *
                                    *   Form pour modif un compte Admin   *
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

if (isset($_SESSION['compteAModifier'])) {
    /* si l'utilisateur veut modifier un compte : requête pour récup les données */
    $uname = $_SESSION['compteAModifier'];
    $_SESSION['uname'] = $uname;
}

if (isset($_POST['enregistrerModifs'])) {

    /* mise à false du booléen erreur pour détecter s'il y a ou non une erreur */
    $erreur = false;

    /* si les variables de tous les champs sont bien définies : enregistrement */
    if (isset($_POST['uname']) && isset($_POST['psw']) && isset($_POST['psw2'])) {
        $uname = $_POST['uname'];
        $psw = $_POST['psw'];
        $psw2 = $_POST['psw2'];

        if ($uname == "" || $psw == "" || $psw2 == "") {
            /* erreur si les champs sont vides */
            $_SESSION['error'] = "Tous les champs doivent être remplis.";
            $erreur = true;
        } else if (!preg_match("/^[a-zA-Z0-9-'éèàïäëöêîôç]*$/", $uname)) {
            /* si le nom n'a pas la forme attendue */
            $_SESSION['error'] = "L'identifiant' ne doit contenir que des lettres et/ou chiffres et/ou des accents et/ou des traits d'unions et/ou des apostrophes.";
            $erreur = true;
        } else if (strlen($psw) < 8) {
            /* si le mot de passe est trop court */
            $_SESSION['error'] = "Le mot de passe doit être composé d'au moins 8 caractères.";
            $erreur = true;
        } else if (!preg_match("/[0-9]/", $psw)) {
            /* si le mot de passe ne contient pas de chiffre */
            $_SESSION['error'] = "Le mot de passe doit être composé d'au moins 1 chiffre.";
            $erreur = true;
        } else if (!preg_match("/[a-z]/", $psw)) {
            /* si le mot de passe ne contient pas de lettre minuscule */
            $_SESSION['error'] = "Le mot de passe doit être composé d'au moins 1 lettre minuscule.";
            $erreur = true;
        } else if (!preg_match("/[A-Z]/", $psw)) {
            /* si le mot de passe ne contient pas de lettre majuscule */
            $_SESSION['error'] = "Le mot de passe doit être composé d'au moins 1 lettre majuscule.";
            $erreur = true;
        } else if ($psw != $psw2) {
            /* si le mot de passe et sa confimation sont différents */
            $_SESSION['error'] = "Les deux mots de passe ne sont pas identiques.";
            $erreur = true;
        }
    } else {
        /* si des champs du form ne sont pas remplis */
        $_SESSION['error'] = "Tous les champs doivent être remplis.";
        $erreur = true;
    }

    if ($erreur == true) {
        /* si au moins une erreur a été détectée : redirection vers la page modifier Admin */
        $_SESSION['uname'] = $uname;
        header('location:modifierAdmin.php');
        die();
    } else {
        /* si aucune erreur a été détectée : insertion des infos dans la BDD */
        $hash = password_hash($psw, PASSWORD_DEFAULT);
        $requete = "UPDATE admin SET idAdmin='" . $uname . "',mdp='" . $hash . "' WHERE idAdmin='" . $_SESSION['compteAModifier'] . "'";
        $result = mysqli_query($link, $requete);

        /* suppression de la variable de session */
        unset($_SESSION['compteAModifier']);

        /* alerte en fonction du résultat */
        if ($result == true) {
            $_SESSION['success'] = "Les informations ont bien été modifiées.";
        } else {
            $_SESSION['error'] = "Les informations n'ont pas pu être modifiés.";
        }

        /* redirection vers le menu des comptes Admin */
        header('location:menuAdmin.php');
        die();
    }
} ?>

<!-- PARTIE VISUELLE DE LA PAGE -->

<div class="arrierePlanRectangle">
    <h1 class="titrePrincipal">MODIFIER MON COMPTE</h1>

    <div class="rectangleJaune" style="background-color: <?php echo $couleur4 ?>; color: <?php echo $couleur7 ?>;">

        <!-- form avec les différents champs à remplir connecté au PHP -->
        <form action="modifierAdmin.php" method="post">
            <h4 class="sousTitreCentre sousTitre">Administrateur</h4>

            <label class="labelForm decalageDu1eLabel">Identifiant :</label>
            <input class="inputForm inputFormCreation" type="text" name="uname" placeholder="Entrer un nouvel identifiant" style="background-color: <?php echo $couleur4 ?>;  " value=<?php if (isset($_SESSION['uname'])) {
                                                                                                                                                                                            echo $_SESSION['uname'];
                                                                                                                                                                                            unset($_SESSION['uname']);
                                                                                                                                                                                        } ?>>

            <label class="labelForm">Mot de passe :</label>
            <input class="inputForm inputFormCreation" type="password" name="psw" placeholder="Modifier le mot de passe" style="background-color: <?php echo $couleur4 ?>;  ">

            <label class="labelForm">Confirmation de mot de passe :</label>
            <input class="inputForm inputFormCreation" type="password" name="psw2" placeholder="Confirmer le mot de passe" style="background-color: <?php echo $couleur4 ?>;  ">

            <a class="aSansFond boutonRouge boutonRougeGaucheModif aSansFond" style="background:<?php echo $couleur2 ?>; color: <?php echo $couleur4 ?>;" href="menuAdmin.php">Annuler</a>

            <input class="boutonSansFond boutonRouge boutonRougeDroitModif" type="submit" name="enregistrerModifs" value="Enregistrer les modifications" style="background:<?php echo $couleur2 ?>; color: <?php echo $couleur4 ?>;">
    </div>
    </form>

</div>

<!-- insertion du footer qui comprend la question des cookies -->
<?php include("../include/footer.php"); ?>