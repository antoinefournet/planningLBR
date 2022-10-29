<!--                                * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *             Fichier PHP             *
                                    *   Form pour modifier une édition    *
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

if (isset($_SESSION['lieuAModifier']) && !isset($_SESSION['nomLieu'])) {
    $requete1 = "SELECT nom FROM lieu WHERE idLieu='" . $_SESSION['lieuAModifier'] . "'";
    $result1 = mysqli_query($link, $requete1);
    if (mysqli_num_rows($result1) == 1) {
        $row1 = mysqli_fetch_assoc($result1);
        $_SESSION['nomLieu'] = $row1['nom'];
    } else {
        $_SESSION['error'] = "Nous n'avons pas pu récupérer les informations dans la base de données.";
        header('location:modifierLieu.php');
        die();
    }
}

if (isset($_SESSION['creneauASupprimer'])) {
    $erreur = false;
    $idsCreneau = $_SESSION['creneauASupprimer'];
    $ids = explode("_", $idsCreneau);
    if (count($ids) == 1) {
        $requete6 = "SELECT * FROM creneau WHERE idCreneau='" . $ids['0'] . "'";
        $result6 = mysqli_query($link, $requete6);
        if (mysqli_num_rows($result6) == 1) {
            $row6 = mysqli_fetch_assoc($result6);
            $dateDebut = explode("-", $row6['dateDebut']);
            $dayofweek = date('w', strtotime($row6['dateDebut']));
            $jourDeLaSemaine;
            switch ($dayofweek) {
                case 0:
                    $jourDeLaSemaine = "Dimanche";
                    break;
                case 1:
                    $jourDeLaSemaine = "Lundi";
                    break;
                case 2:
                    $jourDeLaSemaine = "Mardi";
                    break;
                case 3:
                    $jourDeLaSemaine = "Mercredi";
                    break;
                case 4:
                    $jourDeLaSemaine = "Jeudi";
                    break;
                case 5:
                    $jourDeLaSemaine = "Vendredi";
                    break;
                case 6:
                    $jourDeLaSemaine = "Samedi";
                    break;
            }
            $date = $jourDeLaSemaine . " " . $dateDebut['2'] . "/" . $dateDebut['1'] . "/" . $dateDebut['0'];
            $heure1 = $row6['heureDebut'];
            $heure = explode(":", $heure1);
            $heureSuivante = intval($heure['0']) + 1;
            if ($heureSuivante < 10) {
                $heure2 = "0" . $heureSuivante . ":00:00";
            } else {
                $heure2 = $heureSuivante . ":00:00";
            }
        } else {
            $erreur = true;
        }
    } else {
        $requete6 = "SELECT * FROM creneau WHERE idCreneau IN('" . $ids['0'] . "','" . $ids[count($ids) - 1] . "') ORDER by heureDebut ASC";
        $result6 = mysqli_query($link, $requete6);
        if (mysqli_num_rows($result6) == 2) {
            $row6 = mysqli_fetch_assoc($result6);
            $dateDebut = explode("-", $row6['dateDebut']);
            $dayofweek = date('w', strtotime($row6['dateDebut']));
            $jourDeLaSemaine;
            switch ($dayofweek) {
                case 0:
                    $jourDeLaSemaine = "Dimanche";
                    break;
                case 1:
                    $jourDeLaSemaine = "Lundi";
                    break;
                case 2:
                    $jourDeLaSemaine = "Mardi";
                    break;
                case 3:
                    $jourDeLaSemaine = "Mercredi";
                    break;
                case 4:
                    $jourDeLaSemaine = "Jeudi";
                    break;
                case 5:
                    $jourDeLaSemaine = "Vendredi";
                    break;
                case 6:
                    $jourDeLaSemaine = "Samedi";
                    break;
            }
            $date = $jourDeLaSemaine . " " . $dateDebut['2'] . "/" . $dateDebut['1'] . "/" . $dateDebut['0'];
            $heure1 = $row6['heureDebut'];
            $row6 = mysqli_fetch_assoc($result6);
            $heure2 = $row6['heureDebut'];
            $heure = explode(":", $heure2);
            $heureSuivante = intval($heure['0']) + 1;
            if ($heureSuivante < 10) {
                $heure2 = "0" . $heureSuivante . ":00:00";
            } else {
                $heure2 = $heureSuivante . ":00:00";
            }
        } else {
            $erreur = true;
        }
    }
    echo "<form action='actionModifierLieu.php' method='post'>";
    echo "<div id='alertSuppression' style='position: absolute;margin: 25% 10%;z-index: 4;width: 65%;background-color: " . $couleur7 . ";height: 22%;padding: 2%;'>";
    echo "<p style='text-align:center'>Voulez-vous vraiment supprimer le créneau le " . $date . " de " . $heure1[0] . $heure1[1] . "h à " . $heure2[0] . $heure2[1] . "h ?</p>";
    echo "<input id='oui' name='oui' type='submit' value='Oui' style='margin: 2% 20%;width: 20%;background: none;color: ". $couleur4 .";text-transform: uppercase;border: 2px solid #EA001C;border-radius: 30px;'>";
    echo "<input id='non' name='non' type='submit' value='Non' style='width: 20%;background: none;color: ". $couleur4 .";text-transform: uppercase;border: 2px solid #EA001C;border-radius: 30px;'>";
    echo "</div>";
    echo "</form>";
} ?>

<!-- PARTIE VISUELLE DE LA PAGE -->

<div class="arrierePlanRectangle">
    <h1 class="titrePrincipal">modification d'un lieu</h1>
    <div class="rectangleJaune" style="background-color: <?php echo $couleur4 ?>; color: <?php echo $couleur7 ?>;">

        <form class="formAjoutLieu" action="actionModifierLieu.php" method="post">
            <label class="sousTitre" for="nomLieu">Paramètres du Lieu :</label><br>

            <div class="parametreLieu">
                <input class="inputForm " style="width:40%" placeholder="Saisir le nom du lieu" type="text" name="nomLieu" id="nomLieu" value=<?php if (isset($_SESSION['nomLieu'])) {
                                                                                                                                                    echo $_SESSION['nomLieu'];
                                                                                                                                                } ?>>

                <label id="ajoutLigne" for="choixCouleur">Couleur du lieu :</label>
                <input id="colorLieu" type="color" class="boutonSansFond" name="choixCouleur" value=<?php if (isset($_SESSION['choixCouleur'])) {
                                                                                                        echo $_SESSION['choixCouleur'];
                                                                                                    } else if (isset($_SESSION['lieuAModifier'])) {
                                                                                                        $requete2 = "SELECT idCouleur FROM lieu WHERE idLieu='" . $_SESSION['lieuAModifier'] . "'";
                                                                                                        $result2 = mysqli_query($link, $requete2);
                                                                                                        if (mysqli_num_rows($result2) == 1) {
                                                                                                            $row2 = mysqli_fetch_assoc($result2);
                                                                                                            echo $row2['idCouleur'];
                                                                                                        }
                                                                                                    } else {
                                                                                                        echo "#fffee6";
                                                                                                    }
                                                                                                    ?>>

                <input class="boutonSansFond boutonRouge" style='background-color: <?php echo $couleur2 ?>;color: <?php echo $couleur4 ?>; margin-left:8%;' type="submit" name="modifLieu" value="Modifier">
            </div>

            <div class="formAjoutCreneau" id="creneau">

                <?php if (isset($_SESSION['lieuAModifier'])) {

                ?>
                    <div class="listeInfoCreneau">

                        <?php
                        $requete3 = "SELECT * FROM edition WHERE idEdition='" . $_SESSION['edition'] . "'";
                        $result3 = mysqli_query($link, $requete3);
                        if (mysqli_num_rows($result3) == 1) {
                            $row3 = mysqli_fetch_assoc($result3);
                            $duree = $row3['nbJour'];
                            $dateJ1 = explode("-", $row3['dateJ1']);
                            echo "<label class='labelInfoCreneau' for='jour'>Jour :</label>";
                            echo "<select name='jour' id='jour' class='champInfoCreneau' style='margin-bottom:-2px'>";

                            for ($compteur = 0; $compteur < $duree; $compteur++) {
                                $dateDuJour[0] = $dateJ1[0];
                                $dateDuJour[1] = $dateJ1[1];
                                $dateDuJour[2] = $dateJ1[2] + $compteur;

                                $dateActu = implode("-", $dateDuJour);

                                if (intval($dateDuJour[2]) >= 30 && intval($dateDuJour[1]) == "02") {
                                    $nbMois = floor(intval($dateDuJour[2] / 29));
                                    $jourApres = intval($dateDuJour[2]) % 29;
                                    if ($jourApres < 10) {
                                        $dateDuJour[2] = "0" . $jourApres;
                                    }
                                    $mois = intval($dateDuJour[1]) + $nbMois;
                                    if ($mois < 10) {
                                        $dateDuJour[1] = "0" . $mois;
                                    } else {
                                        $dateDuJour[1] = $mois;
                                    }
                                    $dateActu = implode("-", $dateDuJour);
                                } else if (intval($dateDuJour[2]) >= 31 && (intval($dateDuJour[1]) == "04" || intval($dateDuJour[1]) == "06" || intval($dateDuJour[1]) == "09" || intval($dateDuJour[1]) == "11")) {
                                    $nbMois = floor(intval($dateDuJour[2] / 30));
                                    $jourApres = intval($dateDuJour[2]) % 30;
                                    if ($jourApres < 10) {
                                        $dateDuJour[2] = "0" . $jourApres;
                                    }
                                    $mois = intval($dateDuJour[1]) + $nbMois;
                                    if ($mois < 10) {
                                        $dateDuJour[1] = "0" . $mois;
                                    } else {
                                        $dateDuJour[1] = $mois;
                                    }
                                    $dateActu = implode("-", $dateDuJour);
                                } else if (intval($dateDuJour[2]) >= 32 && intval($dateDuJour[1]) == "12") {
                                    $jourApres = intval($dateDuJour[2]) % 31;
                                    if ($jourApres < 10) {
                                        $dateDuJour[2] = "0" . $jourApres;
                                    }
                                    $dateDuJour[0] = intval($dateDuJour[0]) + 1;
                                    $dateDuJour[1] = "01";
                                    $dateActu = implode("-", $dateDuJour);
                                } else if (intval($dateDuJour[2]) >= 32) {
                                    $nbMois = floor(intval($dateDuJour[2] / 31));
                                    $jourApres = intval($dateDuJour[2]) % 31;
                                    if ($jourApres < 10) {
                                        $dateDuJour[2] = "0" . $jourApres;
                                    }
                                    $mois = intval($dateDuJour[1]) + $nbMois;
                                    if ($mois < 10) {
                                        $dateDuJour[1] = "0" . $mois;
                                    } else {
                                        $dateDuJour[1] = $mois;
                                    }
                                    $dateActu = implode("-", $dateDuJour);
                                }

                                /* récupération de données : jour de la semaine et mois */
                                $dayofweek = date('w', strtotime($dateActu));
                                /* mise en forme du jour de la semaine */
                                $jourDeLaSemaine;
                                switch ($dayofweek) {
                                    case 0:
                                        $jourDeLaSemaine = "Di";
                                        break;
                                    case 1:
                                        $jourDeLaSemaine = "Lu";
                                        break;
                                    case 2:
                                        $jourDeLaSemaine = "Ma";
                                        break;
                                    case 3:
                                        $jourDeLaSemaine = "Me";
                                        break;
                                    case 4:
                                        $jourDeLaSemaine = "Je";
                                        break;
                                    case 5:
                                        $jourDeLaSemaine = "Ve";
                                        break;
                                    case 6:
                                        $jourDeLaSemaine = "Sa";
                                        break;
                                }
                                if (isset($_SESSION['jour'])) {
                                    if ($_SESSION['jour'] == $dateActu) {
                                        echo "<option selected value='" . $dateActu . "'>" . $jourDeLaSemaine . " " . $dateDuJour[2] . "/" . $dateDuJour[1] . "/" . $dateDuJour[0] . "</option>";
                                    } else {
                                        echo "<option value='" . $dateActu . "'>" . $jourDeLaSemaine . " " . $dateDuJour[2] . "/" . $dateDuJour[1] . "/" . $dateDuJour[0] . "</option>";
                                    }
                                } else {
                                    echo "<option value='" . $dateActu . "'>" . $jourDeLaSemaine . " " . $dateDuJour[2] . "/" . $dateDuJour[1] . "/" . $dateDuJour[0] . "</option>";
                                }
                            }
                            echo "</select>";
                        }
                        ?>

                        <label class="labelInfoCreneau" for="heureDebut">Heure de Début :</label>
                        <input class="champInfoCreneau" type="time" name="heureDebut" id="heureDebut" value=<?php if (isset($_SESSION['heureDebut'])) {
                                                                                                                echo $_SESSION['heureDebut'];
                                                                                                                unset($_SESSION['heureDebut']);
                                                                                                            } ?>>
                        <label class="labelInfoCreneau" for="heureFin">Heure de Fin :</label>
                        <input class="champInfoCreneau" type="time" name="heureFin" id="heureFin" value=<?php if (isset($_SESSION['heureFin'])) {
                                                                                                            echo $_SESSION['heureFin'];
                                                                                                            unset($_SESSION['heureFin']);
                                                                                                        } ?>>
                        <label class="labelInfoCreneau" for="nombreBenevoles">Nombre de Bénévoles :</label>
                        <input class="champInfoCreneau" type="number" name="nombreBenevoles" id="nombreBenevoles" value=<?php if (isset($_SESSION['nombreBenevoles'])) {
                                                                                                                            echo $_SESSION['nombreBenevoles'];
                                                                                                                            unset($_SESSION['nombreBenevoles']);
                                                                                                                        } ?>>
                        <label class="labelInfoCreneau" for="nombreMembres">Nombre de Membres :</label>
                        <input class="champInfoCreneau" type="number" name="nombreMembres" id="nombreMembres" value=<?php if (isset($_SESSION['nombreMembres'])) {
                                                                                                                        echo $_SESSION['nombreMembres'];
                                                                                                                        unset($_SESSION['nombreMembres']);
                                                                                                                    } ?>>
                        <input class="boutonSansFond boutonRouge petitPlusAjouterCreneau" style='background-color: <?php echo $couleur2 ?>;color: <?php echo $couleur4 ?>' type='submit' name='ajoutLigne' value='+'>
                    <?php echo "</div>";


                    $requete4 = "SELECT * FROM creneau WHERE idLieu='" . $_SESSION['lieuAModifier'] . "' AND idEdition='" . $_SESSION['edition'] . "' ORDER BY dateDebut ASC, heureDebut ASC";
                    $result4 = mysqli_query($link, $requete4);
                    if (mysqli_num_rows($result4) > 0) {
                        if (isset($_SESSION['modifCreneau'])) {
                            $modifCreneau = $_SESSION['modifCreneau'];
                        }
                        echo '<div class="listeCreneau">';
                        $nouveauCreneau = true;
                        $date = "00/00/0000";
                        $heureDebut = "00:00:00";
                        $heureFin = "00:00:00";
                        $nbBenevole = 0;
                        $nbMembre = 0;
                        $idsCreneau = "";
                        while ($row4 = mysqli_fetch_assoc($result4)) {
                            if ($nbBenevole != $row4['nbBenevoleManquant'] || $nbMembre != $row4['nbMembreManquant'] || $heureFin != $row4['heureDebut'] || ($date != $row4['dateDebut'] && $row4['heureDebut'] != '00:00:00')) {
                                if ($date != "00/00/0000") {
                                    if (isset($modifCreneau)) {
                                        if ($idsCreneau == $modifCreneau) {
                                            $dateActuelle = explode('-', $date);
                                            $dateDebut = $dateActuelle['2'] . '/' . $dateActuelle['1'] . '/' . $dateActuelle['0'];
                                            echo "<span>Le </span>";
                                            $requete5 = "SELECT * FROM edition WHERE idEdition='" . $_SESSION['edition'] . "'";
                                            $result5 = mysqli_query($link, $requete5);
                                            if (mysqli_num_rows($result5) == 1) {
                                                $row5 = mysqli_fetch_assoc($result5);
                                                $duree = $row5['nbJour'];
                                                $dateJ1 = explode("-", $row5['dateJ1']);
                                                echo "<select name='modifJour' id='jour' class='champInfoCreneau'>";

                                                for ($compteur = 0; $compteur < $duree; $compteur++) {
                                                    $dateDuJour[0] = $dateJ1[0];
                                                    $dateDuJour[1] = $dateJ1[1];
                                                    $dateDuJour[2] = $dateJ1[2] + $compteur;

                                                    $dateActu = implode("-", $dateDuJour);
                                                    /* récupération de données : jour de la semaine et mois */
                                                    $dayofweek = date('w', strtotime($dateActu));
                                                    /* mise en forme du jour de la semaine */
                                                    $jourDeLaSemaine;
                                                    switch ($dayofweek) {
                                                        case 0:
                                                            $jourDeLaSemaine = "Di";
                                                            break;
                                                        case 1:
                                                            $jourDeLaSemaine = "Lu";
                                                            break;
                                                        case 2:
                                                            $jourDeLaSemaine = "Ma";
                                                            break;
                                                        case 3:
                                                            $jourDeLaSemaine = "Me";
                                                            break;
                                                        case 4:
                                                            $jourDeLaSemaine = "Je";
                                                            break;
                                                        case 5:
                                                            $jourDeLaSemaine = "Ve";
                                                            break;
                                                        case 6:
                                                            $jourDeLaSemaine = "Sa";
                                                            break;
                                                    }
                                                    if (isset($_SESSION['modifJour'])) {
                                                        echo $_SESSION['modifJour'] . "<br>";
                                                        if ($_SESSION['modifJour'] == $dateActu) {
                                                            echo "<option selected value='" . $dateActu . "'>" . $jourDeLaSemaine . " " . $dateDuJour[2] . "/" . $dateDuJour[1] . "/" . $dateDuJour[0] . "</option>";
                                                        } else {
                                                            echo "<option value='" . $dateActu . "'>" . $jourDeLaSemaine . " " . $dateDuJour[2] . "/" . $dateDuJour[1] . "/" . $dateDuJour[0] . "</option>";
                                                        }
                                                    } else {
                                                        echo $row4['dateDebut'] . "<br>";
                                                        if ($date == $dateActu) {
                                                            echo "<option selected value='" . $dateActu . "'>" . $jourDeLaSemaine . " " . $dateDuJour[2] . "/" . $dateDuJour[1] . "/" . $dateDuJour[0] . "</option>";
                                                        } else {
                                                            echo "<option value='" . $dateActu . "'>" . $jourDeLaSemaine . " " . $dateDuJour[2] . "/" . $dateDuJour[1] . "/" . $dateDuJour[0] . "</option>";
                                                        }
                                                    }
                                                }
                                            }
                                            echo "</select>";
                                            echo "<span>         de </span>";
                                            if (isset($_SESSION['modifHeureDebut'])) {
                                                echo "<input class='champInfoCreneau' name='modifHeureDebut' type='time' value=" . $_SESSION['modifHeureDebut'] . ">";
                                            } else {
                                                echo "<input class='champInfoCreneau' name='modifHeureDebut' type='time' value=" . $heureDebut . ">";
                                            }
                                            echo "<span>         à </span>";
                                            if (isset($_SESSION['modifHeureFin'])) {
                                                echo "<input class='champInfoCreneau' name='modifHeureFin' type='time' value=" . $_SESSION['modifHeureFin'] . ">";
                                            } else {
                                                echo "<input class='champInfoCreneau' name='modifHeureFin' type='time' value=" . $heureFin . ">";
                                            }
                                            echo "<span>         il faut </span>";
                                            if (isset($_SESSION['modifNbBenevole'])) {
                                                echo "<input class='champInfoCreneau' style='width:8%' name='modifNbBenevole' type='number' value=" . $_SESSION['modifNbBenevole'] . ">";
                                            } else {
                                                echo "<input class='champInfoCreneau' style='width:8%' name='modifNbBenevole' type='number' value=" . $nbBenevole . ">";
                                            }
                                            echo "<span>         bénévoles et </span>";
                                            if (isset($_SESSION['modifNbMembre'])) {
                                                echo "<input class='champInfoCreneau' style='width:8%' name='modifNbMembre' type='number' value=" . $_SESSION['modifNbMembre'] . ">";
                                            } else {
                                                echo "<input class='champInfoCreneau' style='width:8%' name='modifNbMembre' type='number' value=" . $nbMembre . ">";
                                            }
                                            echo "<span>         membres.</span>";
                                            echo "<button type='submit' class='boutonSansFond' style='margin-left:10px;' name='validModifsCreneau' value='" . $idsCreneau . "'><i class='fa fa-check' style='color:" . $couleur1 . "'></i></button>";
                                            echo "<button type='submit' class='boutonSansFond' style='margin-left:10px;' name='annulerModifsCreneau' value='" . $idsCreneau . "'><i class='fa fa-close' style='color:" . $couleur1 . "'></i></button>";
                                            echo "<br>";
                                        } else {
                                            $dayofweek = date('w', strtotime($date));
                                            /* mise en forme du jour de la semaine */
                                            $jourDeLaSemaine;
                                            switch ($dayofweek) {
                                                case 0:
                                                    $jourDeLaSemaine = "Dimanche";
                                                    break;
                                                case 1:
                                                    $jourDeLaSemaine = "Lundi";
                                                    break;
                                                case 2:
                                                    $jourDeLaSemaine = "Mardi";
                                                    break;
                                                case 3:
                                                    $jourDeLaSemaine = "Mercredi";
                                                    break;
                                                case 4:
                                                    $jourDeLaSemaine = "Jeudi";
                                                    break;
                                                case 5:
                                                    $jourDeLaSemaine = "Vendredi";
                                                    break;
                                                case 6:
                                                    $jourDeLaSemaine = "Samedi";
                                                    break;
                                            }
                                            $dateActuelle = explode('-', $date);
                                            $dateDebut = $dateActuelle['2'] . '/' . $dateActuelle['1'] . '/' . $dateActuelle['0'];
                                            echo "<span>Le </span>";
                                            echo "<span>" . $jourDeLaSemaine . "</span>";
                                            echo "<span> </span>";
                                            echo "<span>" . $dateDebut . "        </span>";
                                            echo "<span> de </span>";
                                            echo "<span>" . $heureDebut[0] . $heureDebut[1] . "        </span>";
                                            echo "<span>h à </span>";
                                            echo "<span>" . $heureFin[0] . $heureFin[1] . "        </span>";
                                            echo "<span>h, il faut : </span>";
                                            echo "<span style='color:" . $couleur2 . "'>" . $nbBenevole . "        </span>";
                                            echo "<span> bénévoles et </span>";
                                            echo "<span style='color:" . $couleur2 . "'>" . $nbMembre . "        </span>";
                                            echo "<span> membres.</span>";
                                            echo "<button type='submit' class='boutonSansFond' style='margin-left:10px;' name='modifCreneau' value='" . $idsCreneau . "'><i class='fa fa-edit' style='color:" . $couleur1 . "'></i></button>";
                                            echo "<button type='submit' class='boutonSansFond' style='margin-left:10px;' name='supprCreneau' value='" . $idsCreneau . "'><i class='fa fa-trash' style='color:" . $couleur1 . "'></i></button>";
                                            echo "<br>";
                                        }
                                    } else {
                                        $dayofweek = date('w', strtotime($date));
                                        /* mise en forme du jour de la semaine */
                                        $jourDeLaSemaine;
                                        switch ($dayofweek) {
                                            case 0:
                                                $jourDeLaSemaine = "Dimanche";
                                                break;
                                            case 1:
                                                $jourDeLaSemaine = "Lundi";
                                                break;
                                            case 2:
                                                $jourDeLaSemaine = "Mardi";
                                                break;
                                            case 3:
                                                $jourDeLaSemaine = "Mercredi";
                                                break;
                                            case 4:
                                                $jourDeLaSemaine = "Jeudi";
                                                break;
                                            case 5:
                                                $jourDeLaSemaine = "Vendredi";
                                                break;
                                            case 6:
                                                $jourDeLaSemaine = "Samedi";
                                                break;
                                        }
                                        $dateActuelle = explode('-', $date);
                                        $dateDebut = $dateActuelle['2'] . '/' . $dateActuelle['1'] . '/' . $dateActuelle['0'];
                                        echo "<span>Le </span>";
                                        echo "<span>" . $jourDeLaSemaine . "</span>";
                                        echo "<span> </span>";
                                        echo "<span>" . $dateDebut . "        </span>";
                                        echo "<span> de </span>";
                                        echo "<span>" . $heureDebut[0] . $heureDebut[1] . "        </span>";
                                        echo "<span>h à </span>";
                                        echo "<span>" . $heureFin[0] . $heureFin[1] . "        </span>";
                                        echo "<span>h, il faut : </span>";
                                        echo "<span style='color:" . $couleur2 . "'>" . $nbBenevole . "        </span>";
                                        echo "<span> bénévoles et </span>";
                                        echo "<span style='color:" . $couleur2 . "'>" . $nbMembre . "        </span>";
                                        echo "<span> membres.</span>";
                                        echo "<button type='submit' class='boutonSansFond' style='margin-left:10px;' name='modifCreneau' value='" . $idsCreneau . "'><i class='fa fa-edit' style='color:" . $couleur1 . "'></i></button>";
                                        echo "<button type='submit' class='boutonSansFond' style='margin-left:10px;' name='supprCreneau' value='" . $idsCreneau . "'><i class='fa fa-trash' style='color:" . $couleur1 . "'></i></button>";
                                        echo "<br>";
                                    }
                                }
                                $nouveauCreneau = true;
                            }
                            if ($nouveauCreneau == true) {
                                $idsCreneau = $row4['idCreneau'];
                                $date = $row4['dateDebut'];
                                $heureDebut = $row4['heureDebut'];
                                $heure = explode(':', $row4['heureDebut']);
                                $heureSuivante = intval($heure['0']) + 1;
                                if ($heureSuivante < 10) {
                                    $heureFin = "0" . $heureSuivante . ":00:00";
                                } else {
                                    $heureFin = $heureSuivante . ":00:00";
                                }
                                $nbBenevole = $row4['nbBenevoleManquant'];
                                $nbMembre = $row4['nbMembreManquant'];
                                $nouveauCreneau = false;
                            } else {
                                $idsCreneau = $idsCreneau . "_" . $row4['idCreneau'];
                                $heure = explode(':', $row4['heureDebut']);
                                $heureSuivante = intval($heure['0']) + 1;
                                if ($heureSuivante < 10) {
                                    $heureFin = "0" . $heureSuivante . ":00:00";
                                } else {
                                    $heureFin = $heureSuivante . ":00:00";
                                }
                                if ($heureFin == "24:00:00") {
                                    $heureFin = "00:00:00";
                                }
                            }
                        }
                        if (isset($modifCreneau)) {
                            if ($idsCreneau == $modifCreneau) {
                                $dateActuelle = explode('-', $date);
                                $dateDebut = $dateActuelle['2'] . '/' . $dateActuelle['1'] . '/' . $dateActuelle['0'];
                                echo "<span>Le </span>";
                                $requete3 = "SELECT * FROM edition WHERE idEdition='" . $_SESSION['edition'] . "'";
                                $result3 = mysqli_query($link, $requete3);
                                if (mysqli_num_rows($result3) == 1) {
                                    $row3 = mysqli_fetch_assoc($result3);
                                    $duree = $row3['nbJour'];
                                    $dateJ1 = explode("-", $row3['dateJ1']);
                                    echo "<select name='modifJour' id='jour' class='champInfoCreneau'>";

                                    for ($compteur = 0; $compteur < $duree; $compteur++) {
                                        $dateDuJour[0] = $dateJ1[0];
                                        $dateDuJour[1] = $dateJ1[1];
                                        $dateDuJour[2] = $dateJ1[2] + $compteur;

                                        $dateActu = implode("-", $dateDuJour);
                                        /* récupération de données : jour de la semaine et mois */
                                        $dayofweek = date('w', strtotime($dateActu));
                                        /* mise en forme du jour de la semaine */
                                        $jourDeLaSemaine;
                                        switch ($dayofweek) {
                                            case 0:
                                                $jourDeLaSemaine = "Di";
                                                break;
                                            case 1:
                                                $jourDeLaSemaine = "Lu";
                                                break;
                                            case 2:
                                                $jourDeLaSemaine = "Ma";
                                                break;
                                            case 3:
                                                $jourDeLaSemaine = "Me";
                                                break;
                                            case 4:
                                                $jourDeLaSemaine = "Je";
                                                break;
                                            case 5:
                                                $jourDeLaSemaine = "Ve";
                                                break;
                                            case 6:
                                                $jourDeLaSemaine = "Sa";
                                                break;
                                        }
                                        if (isset($_SESSION['modifJour'])) {
                                            echo $_SESSION['modifJour'] . "<br>";
                                            if ($_SESSION['modifJour'] == $dateActu) {
                                                echo "<option selected value='" . $dateActu . "'>" . $jourDeLaSemaine . " " . $dateDuJour[2] . "/" . $dateDuJour[1] . "/" . $dateDuJour[0] . "</option>";
                                            } else {
                                                echo "<option value='" . $dateActu . "'>" . $jourDeLaSemaine . " " . $dateDuJour[2] . "/" . $dateDuJour[1] . "/" . $dateDuJour[0] . "</option>";
                                            }
                                        } else {
                                            echo $row4['dateDebut'] . "<br>";
                                            if ($date == $dateActu) {
                                                echo "<option selected value='" . $dateActu . "'>" . $jourDeLaSemaine . " " . $dateDuJour[2] . "/" . $dateDuJour[1] . "/" . $dateDuJour[0] . "</option>";
                                            } else {
                                                echo "<option value='" . $dateActu . "'>" . $jourDeLaSemaine . " " . $dateDuJour[2] . "/" . $dateDuJour[1] . "/" . $dateDuJour[0] . "</option>";
                                            }
                                        }
                                    }
                                }
                                echo "</select>";
                                echo "<span>         de </span>";
                                if (isset($_SESSION['modifHeureDebut'])) {
                                    echo "<input class='champInfoCreneau' name='modifHeureDebut' type='time' value=" . $_SESSION['modifHeureDebut'] . ">";
                                } else {
                                    echo "<input class='champInfoCreneau' name='modifHeureDebut' type='time' value=" . $heureDebut . ">";
                                }
                                echo "<span>         à </span>";
                                if (isset($_SESSION['modifHeureFin'])) {
                                    echo "<input class='champInfoCreneau' name='modifHeureFin' type='time' value=" . $_SESSION['modifHeureFin'] . ">";
                                } else {
                                    if ($heureFin == "24:00:00") {
                                        $heureFin = "00:00:00";
                                    }
                                    echo "<input class='champInfoCreneau' name='modifHeureFin' type='time' value=" . $heureFin . ">";
                                }
                                echo "<span>         il faut </span>";
                                if (isset($_SESSION['modifNbBenevole'])) {
                                    echo "<input class='champInfoCreneau' style='width:8%' name='modifNbBenevole' type='number' value=" . $_SESSION['modifNbBenevole'] . ">";
                                } else {
                                    echo "<input class='champInfoCreneau' style='width:8%' name='modifNbBenevole' type='number' value=" . $nbBenevole . ">";
                                }
                                echo "<span>         bénévoles et </span>";
                                if (isset($_SESSION['modifNbMembre'])) {
                                    echo "<input class='champInfoCreneau' style='width:8%' name='modifNbMembre' type='number' value=" . $_SESSION['modifNbMembre'] . ">";
                                } else {
                                    echo "<input class='champInfoCreneau' style='width:8%' name='modifNbMembre' type='number' value=" . $nbMembre . ">";
                                }
                                echo "<span>         membres.</span>";
                                echo "<button type='submit' class='boutonSansFond' style='margin-left:10px;' name='validModifsCreneau' value='" . $idsCreneau . "'><i class='fa fa-check' style='color:" . $couleur1 . "'></i></button>";
                                echo "<button type='submit' class='boutonSansFond' style='margin-left:10px;' name='annulerModifsCreneau' value='" . $idsCreneau . "'><i class='fa fa-close' style='color:" . $couleur1 . "'></i></button>";
                                echo "<br>";
                            } else {
                                $dayofweek = date('w', strtotime($date));
                                /* mise en forme du jour de la semaine */
                                $jourDeLaSemaine;
                                switch ($dayofweek) {
                                    case 0:
                                        $jourDeLaSemaine = "Dimanche";
                                        break;
                                    case 1:
                                        $jourDeLaSemaine = "Lundi";
                                        break;
                                    case 2:
                                        $jourDeLaSemaine = "Mardi";
                                        break;
                                    case 3:
                                        $jourDeLaSemaine = "Mercredi";
                                        break;
                                    case 4:
                                        $jourDeLaSemaine = "Jeudi";
                                        break;
                                    case 5:
                                        $jourDeLaSemaine = "Vendredi";
                                        break;
                                    case 6:
                                        $jourDeLaSemaine = "Samedi";
                                        break;
                                }
                                $dateActuelle = explode('-', $date);
                                $dateDebut = $dateActuelle['2'] . '/' . $dateActuelle['1'] . '/' . $dateActuelle['0'];
                                echo "<span>Le </span>";
                                echo "<span>" . $jourDeLaSemaine . "</span>";
                                echo "<span> </span>";
                                echo "<span>" . $dateDebut . "        </span>";
                                echo "<span> de </span>";
                                echo "<span>" . $heureDebut[0] . $heureDebut[1] . "        </span>";
                                echo "<span>h à </span>";
                                echo "<span>" . $heureFin[0] . $heureFin[1] . "        </span>";
                                echo "<span>h, il faut : </span>";
                                echo "<span style='color:" . $couleur2 . "'>" . $nbBenevole . "        </span>";
                                echo "<span> bénévoles et </span>";
                                echo "<span style='color:" . $couleur2 . "'>" . $nbMembre . "        </span>";
                                echo "<span> membres.</span>";
                                echo "<button type='submit' class='boutonSansFond' style='margin-left:10px;' name='modifCreneau' value='" . $idsCreneau . "'><i class='fa fa-edit' style='color:" . $couleur1 . "'></i></button>";
                                echo "<button type='submit' class='boutonSansFond' style='margin-left:10px;' name='supprCreneau' value='" . $idsCreneau . "'><i class='fa fa-trash' style='color:" . $couleur1 . "'></i></button>";
                                echo "<br>";
                                echo '</div>';
                            }
                        } else {
                            $dayofweek = date('w', strtotime($date));
                            /* mise en forme du jour de la semaine */
                            $jourDeLaSemaine;
                            switch ($dayofweek) {
                                case 0:
                                    $jourDeLaSemaine = "Dimanche";
                                    break;
                                case 1:
                                    $jourDeLaSemaine = "Lundi";
                                    break;
                                case 2:
                                    $jourDeLaSemaine = "Mardi";
                                    break;
                                case 3:
                                    $jourDeLaSemaine = "Mercredi";
                                    break;
                                case 4:
                                    $jourDeLaSemaine = "Jeudi";
                                    break;
                                case 5:
                                    $jourDeLaSemaine = "Vendredi";
                                    break;
                                case 6:
                                    $jourDeLaSemaine = "Samedi";
                                    break;
                            }
                            $dateActuelle = explode('-', $date);
                            $dateDebut = $dateActuelle['2'] . '/' . $dateActuelle['1'] . '/' . $dateActuelle['0'];
                            echo "<span>Le </span>";
                            echo "<span>" . $jourDeLaSemaine . "</span>";
                            echo "<span> </span>";
                            echo "<span>" . $dateDebut . "        </span>";
                            echo "<span> de </span>";
                            echo "<span>" . $heureDebut[0] . $heureDebut[1] . "        </span>";
                            echo "<span>h à </span>";
                            echo "<span>" . $heureFin[0] . $heureFin[1] . "        </span>";
                            echo "<span>h, il faut : </span>";
                            echo "<span style='color:" . $couleur2 . "'>" . $nbBenevole . "        </span>";
                            echo "<span> bénévoles et </span>";
                            echo "<span style='color:" . $couleur2 . "'>" . $nbMembre . "        </span>";
                            echo "<span> membres.</span>";
                            echo "<button type='submit' class='boutonSansFond' style='margin-left:10px;' name='modifCreneau' value='" . $idsCreneau . "'><i class='fa fa-edit' style='color:" . $couleur1 . "'></i></button>";
                            echo "<button type='submit' class='boutonSansFond' style='margin-left:10px;' name='supprCreneau' value='" . $idsCreneau . "'><i class='fa fa-trash' style='color:" . $couleur1 . "'></i></button>";
                            echo "<br>";
                            echo '</div>';
                        }
                    }
                }
                    ?>
                    </div>
                    <button class="boutonSansFond boutonRouge retourEditionPageAjoutLieu" style='background-color: <?php echo $couleur2 ?>;color: <?php echo $couleur4 ?>' type="submit" name="retour">Retour à l'édition</button>
        </form>
    </div>
</div>

<!-- insertion du footer qui comprend la question des cookies -->
<?php include('../include/footer.php'); ?>