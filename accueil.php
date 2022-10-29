<!--                                * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *             Fichier PHP             *
                                    *  page accueil avec connexion Admin  *
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <link rel="icon" type="image/x-icon" href="../images/initiales.ico">

    <!-- regroupement de tous les includes de nos fichiers css -->
    <link rel="stylesheet" href="css/toutesLesPages.css">
    <link rel="stylesheet" href="css/accueil_menu.css">
    <link rel="stylesheet" href="../css/menuCarrousel.css">
    <link rel="stylesheet" href="../css/planning_tableau.css">

    <link rel="stylesheet" href="css/include.css">
    <link rel="stylesheet" href="css/pageRectangleJaune.css">
    <link rel="stylesheet" href="css/pageCarrousel.css">
    <link rel="stylesheet" href="css/planning.css">
    <link rel="stylesheet" href="css/pageInitialisationEdition.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/lieu.css">
</head>

<?php
// pallette de couleur
$couleur1 = "#AF001E";  // pourpre
$couleur2 = "#EA001C";  // rouge
$couleur3 = "#FF890A";  // orange
$couleur4 = "#FFFEE6";  // jaune
$couleur5 = "#BDE4FC";  // bleu clair
$couleur6 = "#3671B3";  // bleu
$couleur7 = "#161920";  // noir
?>

<?php session_start();
include('include/alertes.php');
unset($_SESSION['username']); ?>


<!-- PARTIE VISUELLE DE L'INTERFACE -->

<!-- initialisation du fond sombre et de la couleur d'écriture claire -->

<body style="background-color: <?php echo $couleur7 ?>; color: <?php echo $couleur4 ?>;">

    <!-- Logo LBR qui redirige vers le site des briques rouges -->
    <div class="pageHaut pageGauche" style="width:10%; background-color: <?php echo $couleur7 ?>;">
        <button type="button" class="boutonSansFond" onclick=" window.open('https://www.lesbriquesrouges.fr/', '_blank');">
            <img src="images/LogoFullBlanc.png" style="width:100%" alt="Les Briques Rouges Festival">
        </button>
    </div>

    <!-- fond de la page contenant les décors -->
    <div class="fondAccueil">
        <img src="images/sun.ico" class="soleil1" alt="Soleil">
        <img src="images/sun.ico" class="soleil2" alt="Soleil">
        <img src="images/planet1.ico" class="planete1" alt="Planète 1">
        <img src="images/planet2.ico" class="planete2" alt="Planète 2">

        <img src="images/fusee.ico" class="fusee" alt="Fusée">

        <img src="images/LogoFullBlanc.png" class="grosLogo" alt="Les Briques Rouges Festival">
    </div>

    <!-- connexion des Admins -->
    <div id="connexionAdmin" class="arriereConnexion tailleComplete">

        <!-- formulaire à remplir -->
        <div class="rectangleFormApparition" style="background-color: <?php echo $couleur4 ?>; color: <?php echo $couleur7 ?>;">

            <!-- image du logo LBR centré -->
            <div class="imageLogoLBR">
                <img src="images/logoLong.png" alt="Les Briques Rouges Festival" style="width: 80%;">
            </div>

            <div style="padding: 0 4% 3% 4%;">
                <form action="actionConnexionAdmin.php" method="post">
                    <div>
                        <label for="uname"><b>Identifiant</b></label>
                        <input class="inputConnexionAdmin" style="border-color: <?php echo $couleur7 ?>; left: 15px; width: 86%;" type="text" placeholder="Saisir mon identifiant" name="uname" value=<?php if (isset($_COOKIE['login'])) {
                                                                                                                                                                                                            echo $_COOKIE['login'];
                                                                                                                                                                                                        } else if (isset($_SESSION['uname'])) {
                                                                                                                                                                                                            echo $_SESSION['uname'];
                                                                                                                                                                                                            unset($_SESSION['uname']);
                                                                                                                                                                                                        } ?>>
                    </div>

                    <div>
                        <label for="psw"><b>Mot de Passe</b></label>
                        <input class="inputConnexionAdmin" style="border-color: <?php echo $couleur7 ?>; left: 20px; width: 82%;" type="password" placeholder="Saisir mon mot de passe" name="psw" value=<?php if (isset($_COOKIE['mdp'])) {
                                                                                                                                                                                                                echo $_COOKIE['mdp'];
                                                                                                                                                                                                            } ?>>
                    </div>

                    <!-- envoi des données + connexion + redirection vers le menu des éditions -->
                    <button type="submit" class="boutonSansFond boutonMeConnecterAdmin" style="background-color: <?php echo $couleur2 ?>; color: <?php echo $couleur4 ?>">Me connecter</button>

                    <label>
                        <input type="checkbox" name="remember" <?php if (isset($_COOKIE['mdp'])) echo "checked = checked"; ?>> Se souvenir de moi
                    </label>
                </form>
            </div>

            <!-- si le mot de passe a été oublié -->
            <div style="padding: 0 4% 3% 4%; background-color: <?php echo $couleur1 ?>;">
                <form action="admin/actionMdpOublie.php" method="post">
                    <span class="mdpOublie">
                        <button type='submit' name='btnMdpOublie' value="1" id="btnMdpOublie" class="boutonSansFond" style="color: <?php echo $couleur4 ?>;">Mot de passe oublié ?</button>
                    </span>
                </form>
            </div>
        </div>
    </div>

    <!-- affichage d'un modal si le bouton (mot de passe oublié) a été cliqué -->
    <?php if (isset($_SESSION['btnMdpOublie'])) { ?>
        <form action="admin/actionMdpOublie.php" method="post">
            <div id="formMdpOublie" class="rectangleMdpOublie" style="background-color: <?php echo $couleur4 ?>; color: <?php echo $couleur7 ?>;">

                <div class="tailleComplete" style="text-align:center;">
                    <button id='close' type="submit" name="fermerMdpOublie" class="fermerMdpOublie boutonSansFond" style='text-decoration:none'>&times;</button>
                    <h3>Voici la procédure pour un <span class="ecritureOmbreRouge" style="color:<?php echo $couleur7 ?>;font-weight: 600; font-size: 25px;">mot de passe oublié </span> :</h3>
                    <form>
                        <br><br>
                        <span style="color:<?php echo $couleur1 ?>">I. </span><input type="text" class="inputConnexionAdmin" placeholder="Saisir mon identifiant" name="idMdpOublie" value='<?php if (isset($_SESSION['nomMdpOublie'])) {
                                                                                                                                                                                                echo $_SESSION['nomMdpOublie'];
                                                                                                                                                                                            } ?>'>

                        <br><br>

                        <br><br>
                        <p>
                            <span style="color:<?php echo $couleur1 ?>">II. </span>
                            Checker mes mails.
                        </p>
                        <p>
                            <span style="color:<?php echo $couleur1 ?>">IV. </span>
                            Attendre de recevoir mon nouveau mot de passe.
                        </p>

                        <br>
                        <input type="submit" name="envoiMdp" class="boutonSansFond boutonRouge" style="background-color: <?php echo $couleur2 ?>; color: <?php echo $couleur4 ?>;width: 15%;cursor:pointer;" value="Let's go !">
                    </form>
                </div>
            </div>

            </div>
        </form>
    <?php } ?>

    <script>
        // Apparition du formulaire
        let pageCoAdmin = document.getElementById('connexionAdmin');
    </script>

</body>

</html>