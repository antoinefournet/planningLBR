<!--                                * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *             Fichier PHP             *
                                    *    Formulaire de connexion Admin    *
                                    *                                     *
                                    * * * * * * * * * * * * * * * * * * * *    
-->

<!-- insertion du header avec le logo en haut à gauche -->
<?php include("include/header.php");

if (isset($_POST['close'])) {
    header('location:planning/planningGeneral.php');
    die();
}

/* mise à false du booléen erreur pour détecter s'il y a ou non une erreur */
$erreur = false;

/* si les variables du form existent bien : enregistrement */
if (isset($_POST['uname']) && isset($_POST['psw'])) {
    $uname = $_POST['uname'];
    $psw = $_POST['psw'];

    if ($uname == "" || $psw == "") {
        if (isset($_SESSION['btnMdpOublie'])) { ?>
            <form action="admin/actionConnexionAdmin.php" method="post">
                <!-- The Modal -->
                <div id="formMdpOublie" class="modalMdpOublie">

                    <!-- Modal content -->
                    <div class="modalMdpOublie-content">
                        <span id='close' class="fermerMdpOublie">&times;</span>
                        <p>Voici la procédure pour un mot de passe oublié :</p>
                        <form>
                            <input type="text" placeholder="Saisir mon adresse mail" name="uname">
                            <p>Checker mes mails et attendre de recevoir mon nouveau mot de passe.</p>
                            <input type="submit" name="submit" class="boutonRouge boutonSansFond" style='background-color: <?php echo $couleur2 ?>' value="Let's go !">
                        </form>
                    </div>

                </div>
            </form>
<?php } else {
            /* erreur si au moins 1 des champs est vide */
            $_SESSION['error'] = "Tous les champs doivent être remplis.";
            $erreur = true;
        }
    } else {
        /* récupération de l'identifiant et s'il existe vérification de la cohérence du mot de passe */
        $requete = "SELECT mdp FROM admin WHERE idAdmin='" . $uname . "'";
        $result = mysqli_query($link, $requete);

        /* si le mot de passe est correct */
        if (mysqli_num_rows($result) > 0) {
            $valid = false;
            if(isset($_COOKIE['mdp']))
            {
                $valid = true;  
            }
            else
            {
                while ($row = mysqli_fetch_assoc($result)) {
                    if (password_verify($psw, $row['mdp'])) {
                        $valid = true;
                    }
                }
            }

            if ($valid == true) {
                /* connexion au compte et mise à non des cookies par défaut */
                $_SESSION['success'] = "Vous êtes connecté.";

                /* changement du cookie si champs rempli */

                if (isset($_POST['remember'])) {
                    if (isset($_COOKIE['login'])) {
                        $_COOKIE['login'] = $uname;
                    }
                    if (isset($_COOKIE['mdp'])) {
                        $hash = password_hash($psw,PASSWORD_DEFAULT);
                        $_COOKIE['mdp'] = $hash;
                    }
                    if (!isset($_COOKIE['login']) && !isset($_COOKIE['mdp'])) {
                        /* conservation de l'id et du mdp pendant 1 mois */
                        setcookie("login", $uname, time() + 30 * 24 * 3600);
                        $hash = password_hash($psw,PASSWORD_DEFAULT);
                        setcookie("mdp", $hash, time() + 30 * 24 * 3600);
                    }
                } else {
                    /* suppression des cookies si on ne veux pas rester connecté */
                    setcookie("login");
                    unset($_COOKIE['login']);
                    setcookie("mdp");
                    unset($_COOKIE['mdp']);
                }
            } else {
                /* si le mot de passe est incorrect */
                $erreur = true;
                $_SESSION['error'] = "Le mot de passe est incorrect.";
            }
        } else {
            /* si l'identifiant ne correspond à aucun compte de la BDD */
            $erreur = true;
            $_SESSION['error'] = "L'identifiant n'existe pas.";
        }
    }
} else {
    /* erreur si au moins 1 des champs est vide */
    $_SESSION['error'] = "Tous les champs doivent être remplis.";
    $erreur = true;
}

if ($erreur == true) {
    /* si au moins une erreur a été détectée : redirection vers la page d'accueil */
    $_SESSION['uname'] = $uname;
    header('location:accueil.php');
    die();
} else {
    /* si aucune erreur n'est détectée : redirection vers le menu des éditions avec mise en mémoire de l'id du compte */
    $_SESSION['username'] = $uname;
    header('location:edition/menuEdition.php');
    die();
}

/* insertion du footer qui comprend la question des cookies */
include("include/footer.php");
