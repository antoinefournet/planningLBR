<?php include("../include/header.php");
unset($_SESSION['planningGeneral']);

if (!isset($_SESSION['username'])) {
    $_SESSION['error'] = "Vous avez été déconnecté.";
    header('location:../accueil.php');
    die();
}

if (isset($_POST['versLieu'])) {
    $_SESSION['versLieu'] = $_POST['versLieu'];
    header('location:planningLieu.php');
    die();
}

if (isset($_POST['versAjoutCreneau'])) {
    header('location:../edition/modifierEditionLieu.php');
    die();
}

if (isset($_POST['envoyerMail'])) {
    $_SESSION['personneDestinataire'] = "PersonneXX";
    header('location:../gerer_mail_fichierPDF_fichierCSV/exportPDF.php');
    die();
} else if (isset($_POST['telechargerPDF'])) {
    $_SESSION['personneDestinataire'] = "PersonneXX";
    header('location:../gerer_mail_fichierPDF_fichierCSV/exportPDF2.php');
    die();
} else {
    unset($_SESSION['planning']);
}

if (isset($_POST['annulerModifsCarte'])) {
    unset($_SESSION['nom']);
    unset($_SESSION['prenom']);
    unset($_SESSION['email']);
    unset($_SESSION['telephone']);
    unset($_SESSION['informations']);
    unset($_SESSION['disponibilite']);
    unset($_SESSION['heuresTravail']);
    header('location:planningPersonne.php');
}
if (isset($_POST['modifsCarte'])) {
    if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['telephone']) && isset($_POST['disponibilite'])) {
        /* enregistrement des données récoltées */
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];

        if (isset($_POST['informations'])) {
            $informations = $_POST['informations'];
        } else {
            $informations = "";
        }
        $disponibilite = $_POST['disponibilite'];
        $requete1 = "SELECT typePersonne FROM personne WHERE idPersonne='" . $_SESSION['personne'] . "'";
        $result1 = mysqli_query($link, $requete1);
        if (mysqli_num_rows($result1) == 1) {
            $row1 = mysqli_fetch_assoc($result1);
            $type = $row1['typePersonne'];
            if ($type == "Benevole") {
                $heuresTravail = $_POST['heuresTravail'];
            } else {
                $heuresTravail = 0;
            }

            if ($nom == "" || $prenom == "" || $email == "" || $telephone == "" || $disponibilite == "") {
                /* si au moins un champ n'est pas rempli */
                $_SESSION['error'] = "Tous les champs marqués d'une * doivent être remplis.";
                $erreur = true;
            } else if ($type == "Benevole" && $heuresTravail == "") {
                /* si un bénévole n'a pas de nombre total d'heures de travail attribué */
                $_SESSION['error'] = "Vous devez indiquez le nombre d'heures de travail à effectuer pour un bénévole.";
                $erreur = true;
            } else if (!preg_match("/^[a-zA-Z-'éèàïäëöêîôç]*$/", $nom)) {
                /* si le nom n'est pas au bon format */
                $_SESSION['error'] = "Le nom ne doit contenir que des lettres et/ou des accents et/ou des traits d'unions et/ou des apostrophes.";
                $erreur = true;
            } else if (!preg_match("/^[a-zA-Z-'éèàïäëöêîôç]*$/", $prenom)) {
                /* si le prénom n'est pas au bon format */
                $_SESSION['error'] = "Le prénom ne doit contenir que des lettres et/ou des accents et/ou des traits d'unions et/ou des apostrophes.";
                $erreur = true;
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                /* si l'email n'est pas valide */
                $_SESSION['error'] = "Vous devez entrer une adresse mail valide.";
                $erreur = true;
            } else if (!preg_match("/^(0|\+33)[1-9][ .-]?[0-9]{2}[ .-]?[0-9]{2}[ .-]?[0-9]{2}[ .-]?[0-9]{2}$/", $telephone)) {
                /* si le numéro de téléphone n'est pas valide */
                $_SESSION['error'] = "Vous devez entrer un numéro de téléphone valide.";
                $erreur = true;
            } else if (!preg_match("/^([0][0-9]|[1][0-9]|[2][0-3]):00$/", $disponibilite)) {
                /* si l'heure n'est pas cohérente */
                $_SESSION['error'] = "Vous devez entrer une heure de disponibilité entre 0 et 23.";
                $erreur = true;
            } else if (!preg_match("/^[1-9]|1[1-2]$/", $heuresTravail) && $type == "Benevole") {
                /* si le nombre total d'heure à travailler n'est pas adéquat */
                $_SESSION['error'] = "Vous devez entrer un nombre d'heures de travail entre 1h et 12h.";
                $erreur = true;
            }

            if ($erreur == false) {
                $requete2 = "SELECT * FROM edition WHERE idEdition='" . $_SESSION['edition'] . "'";
                $result2 = mysqli_query($link, $requete2);
                if (mysqli_num_rows($result2) == 1) {
                    $row2 = mysqli_fetch_assoc($result2);
                    $dispoJ1 = $disponibilite . ":00";
                    $nom = strtoupper($nom);
                    $prenom = mb_strtolower($prenom, "UTF-8");
                    $prenom = mb_strtoupper(mb_substr($prenom, 0, 1, "UTF-8"), "UTF-8") . mb_substr($prenom, 1, strlen($prenom) - 1, "UTF-8");
                    $requete3 = "UPDATE personne SET nom='" . $nom . "', prenom='" . $prenom . "', mail='" . $email . "',telephone='" . $telephone . "',infoSupplementaires='" . $informations . "' WHERE idPersonne='" . $_SESSION['personne'] . "'";
                    $result3 = mysqli_query($link, $requete3);
                    if ($result3 == true) {
                        $requete4 = "UPDATE participer SET heureDispoJ1='" . $dispoJ1 . "',nbHeureAFaire='" . $heuresTravail . "' WHERE idEdition='" . $_SESSION['edition'] . "' AND idPersonne='" . $_SESSION['personne'] . "'";
                        $result4 = mysqli_query($link, $requete4);
                        if ($result4 == true) {
                            $requete5 = "SELECT * FROM personne WHERE idPersonne='" . $_SESSION['personne'] . "'";
                            $result5 = mysqli_query($link, $requete5);
                            if (mysqli_num_rows($result5) == 1) {
                                $row5 = mysqli_fetch_assoc($result5);
                                $_SESSION['success'] = "La personne " . $row5['nom'] . " " .$row5['prenom'] . " a bien été modifiée.";
                            } else {
                                $erreur = true;
                                $_SESSION['error'] = "Les informations n'ont pas pu être entré dans la base de données.";
                            }
                        } else {
                            $erreur = true;
                            $_SESSION['error'] = "Les informations n'ont pas pu être entré dans la base de données.";
                        }
                    } else {
                        $erreur = true;
                        $_SESSION['error'] = "Les informations n'ont pas pu être entré dans la base de données.";
                    }
                }
            }
        } else {
            $erreur = true;
            $_SESSION['error'] = "Les informations n'ont pas pu être entré dans la base de données.";
        }
    } else {
        /* si tous les champs obligatoires ne sont pas remplis */
        $erreur = true;
        $_SESSION['error'] = "Tous les champs marqués d'une * doivent être remplis.";
    }

    if ($erreur == true) {
        /* si au moins une erreur est détectée */
        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;
        $_SESSION['email'] = $email;
        $_SESSION['telephone'] = $telephone;
        $_SESSION['informations'] = $informations;
        $_SESSION['disponibilite'] = $disponibilite;
        $_SESSION['heuresTravail'] = $heuresTravail;
    } else {
        /* sinon suppression des varibales de sessions + alerte */
        unset($_SESSION['nom']);
        unset($_SESSION['prenom']);
        unset($_SESSION['email']);
        unset($_SESSION['telephone']);
        unset($_SESSION['informations']);
        unset($_SESSION['disponibilite']);
        unset($_SESSION['heuresTravail']);
    }
    header('location:planningPersonne.php');
    die();
}
?>

<style>
    table {
        border-collapse: collapse;
        border: 1px solid <?php echo $couleur7 ?>;
        background-color: <?php echo $couleur4 ?>;
        color: <?php echo $couleur7 ?>;
    }

    th,
    td {
        border: 2px solid <?php echo $couleur7 ?>;
        text-align: center;
        padding: 6px;
    }

    .tabTitre {
        background-color: <?php echo $couleur2 ?>;
        color: <?php echo $couleur4 ?>;
        position: sticky;
        border: 2px solid <?php echo $couleur7 ?>;
    }
</style>

<div class="arrierePlanRectangle">
    <?php include("tabPersonne.php"); ?>
</div>
<?php include("../include/footer.php"); ?>