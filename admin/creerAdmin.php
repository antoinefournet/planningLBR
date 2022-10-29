<!--                                * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *             Fichier PHP             *
                                    *    Formulaire de création Admin     *
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

if (isset($_POST['annulerCreationAdmin'])) {
    unset($_SESSION['uname']);
    header('location:menuAdmin.php');
    die();
}

/* si la variable du form existe bien */
if (isset($_POST['creerCompte'])) {

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
        /* si au moins une erreur a été détectée : redirection vers accueil */
        $_SESSION['uname'] = $uname;
        header('location:creerAdmin.php');
        die();
    } else {
        /* si aucune erreur a été détectée : insertion des infos dans la BDD */
        $hash = password_hash($psw, PASSWORD_DEFAULT);
        $requete = "INSERT INTO admin VALUES ('" . $uname . "', '" . $hash . "')";
        $result = mysqli_query($link, $requete);

        if ($result == true) {
            /* si BDD bien mise à jour : redirection vers le menu des Admins avec alerte */
            $_SESSION['success'] = "Le compte " . $uname . " a bien été créé.";
            header('location:menuAdmin.php');
            die();
        } else {
            /* si erreur dans la mise à jour de la BDD : redirection vers le form création de compte */
            $_SESSION['error'] = "Nous n'avons pas réussi à insérer les informations dans la base de données.";
            header('location:creerAdmin.php');
            die();
        }
    }
} ?>

<!-- PARTIE VISUELLE DE LA PAGE -->

<div class="arrierePlanRectangle">
    <h1 class="titrePrincipal">Créer UN COMPTE</h1>

    <div class="rectangleJaune" style="background-color: <?php echo $couleur4 ?>; color: <?php echo $couleur7 ?>;">

        <!-- form avec les différents champs à remplir connecté au PHP -->
        <form action="creerAdmin.php" method="post">
            <h4 class="sousTitreCentre sousTitre">Administrateur</h4>

            <label class="labelForm decalageDu1eLabel" for="uname">Identifiant :</label>
            <input class="inputForm inputFormCreation" type="text" placeholder="Saisir un identifiant" name="uname" value=<?php if (isset($_SESSION['uname'])) {
                                                                                                                                echo $_SESSION['uname'];
                                                                                                                                unset($_SESSION['uname']);
                                                                                                                            } ?>>

            <label class="labelForm" for="psw">Mot de Passe :</label>
            <input class="inputForm inputFormCreation" type="password" placeholder="Saisir un mot de passe" name="psw">

            <label class="labelForm" for="psw2">Confirmer le mot de passe :</label>
            <input class="inputForm inputFormCreation" type="password" placeholder="Confirmer le mot de passe" name="psw2">

            <button type='submit' name='annulerCreationAdmin' class="boutonSansFond boutonRouge boutonRougeGaucheCreation" style="background:<?php echo $couleur2 ?>; color: <?php echo $couleur4 ?>;">Annuler</button>
            <input type="submit" name="creerCompte" class="boutonSansFond boutonRouge boutonRougeDroitCreation" style="background:<?php echo $couleur2 ?>; color: <?php echo $couleur4 ?>;" value="Créer le compte">
        </form>
    </div>
</div>

<!-- insertion du footer qui comprend la question des cookies -->
<?php include("../include/footer.php"); ?>