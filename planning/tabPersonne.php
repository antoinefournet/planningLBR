<style>
    ::placeholder {
        opacity: 1;
        color: <?php echo $couleur4 ?>;
    }

    .champModifierPersonneBas::placeholder {
        color: <?php echo $couleur4 ?>;
    }

    .champModifierPersonne::placeholder {
        color: <?php echo $couleur4 ?>;
    }

    /* chaque ligne du tableau général au survol */
    #tabPersonne tr:hover td button,
    #tabPersonne tr:hover td p {
        font-weight: bold;
        text-transform: uppercase;
    }
</style>


<?php
// INFO A AVOIR
$nbJour;
$nbPersonne = 20;
$nbHeure = 24;

if (isset($_SESSION['personne'])) {
    $personneActuelle = $_SESSION['personne'];
} else {
    echo "Erreur";
    $_SESSION['error'] = "Un problème est survenu.";
    header('location:../edition/menuEdition.php');
    die();
}

if (!isset($_SESSION['edition'])) {
    $_SESSION['error'] = "Un problème est survenu.";
    header('location:../edition/menuEdition.php');
    die();
} else {
    $planningATelecharger = array();
    $erreur = false;
    $requete0 = "SELECT * FROM personne WHERE idPersonne='" . $personneActuelle . "'";
    $result0 = mysqli_query($link, $requete0);
    if (mysqli_num_rows($result0) == 1) {
        $row0 = mysqli_fetch_assoc($result0);
        $requete7 = "SELECT * FROM participer WHERE idEdition='" . $_SESSION['edition'] . "' AND idPersonne='" . $personneActuelle . "'";
        $result7 = mysqli_query($link, $requete7);
        if (mysqli_num_rows($result7) == 1) {
            $row7 = mysqli_fetch_assoc($result7);
            $nomPersonne = $row0['nom'];
            $prenomPersonne = $row0['prenom'];
            $telPersonne = $row0['telephone'];
            $mailPersonne = $row0['mail'];
            $typePersonne = $row0['typePersonne'];
            $infoPersonne = $row0['infoSupplementaires'];
            $nbHeureAFaire = $row7['nbHeureAFaire'];
            $heureDispoJ1 = $row7['heureDispoJ1'];
?>
            <h1 class="titrePrincipal"> <?php echo $nomPersonne . " " . $prenomPersonne ?></h1>
            <form action="planningPersonne.php" method="post">
                <div style="MARGIN-top: 3%;">
                    <div style="background-color: <?php echo $couleur4 ?>;margin: 0% 4%;width: 55%;height: 65%;position: absolute;right: 0;">

                        <div class="tabcontent" style="overflow-x: auto; height: 100%;width:100%;">
                            <table id="tabPersonne" style="margin: 0;min-width:100%;min-height:100%;">
                                <?php
                                /* recherche de toutes les dates du festival */
                                $requete1 = "SELECT * FROM edition WHERE idEdition='" . $_SESSION['edition'] . "'";
                                $result1 = mysqli_query($link, $requete1);
                                if (mysqli_num_rows($result1) == 1) {
                                    $row1 = mysqli_fetch_assoc($result1);
                                    $dateJ1 = $row1['dateJ1'];
                                    $nbJour = $row1['nbJour'];

                                    echo "<tr>";
                                    echo "<th class='tabTitre'></th>";
                                    $jours = array();
                                    for ($i = 0; $i < $nbJour; $i++) {
                                        $date = explode('-', $dateJ1);
                                        $date['2'] = $date['2'] + $i;
                                        $dateActuelle = implode("-", $date);

                                        if (intval($date[2]) >= 30 && intval($date[1]) == "02") {
                                            $nbMois = floor(intval($date[2] / 29));
                                            $jourApres = intval($date[2]) % 29;
                                            if ($jourApres < 10) {
                                                $date[2] = "0" . $jourApres;
                                            }
                                            $mois = intval($date[1]) + $nbMois;
                                            if ($mois < 10) {
                                                $date[1] = "0" . $mois;
                                            } else {
                                                $date[1] = $mois;
                                            }
                                            $dateActuelle = implode("-", $date);
                                        } else if (intval($date[2]) >= 31 && (intval($date[1]) == "04" || intval($date[1]) == "06" || intval($date[1]) == "09" || intval($date[1]) == "11")) {
                                            $nbMois = floor(intval($date[2] / 30));
                                            $jourApres = intval($date[2]) % 30;
                                            if ($jourApres < 10) {
                                                $date[2] = "0" . $jourApres;
                                            }
                                            $mois = intval($date[1]) + $nbMois;
                                            if ($mois < 10) {
                                                $date[1] = "0" . $mois;
                                            } else {
                                                $date[1] = $mois;
                                            }
                                            $dateActuelle = implode("-", $date);
                                        } else if (intval($date[2]) >= 32 && intval($date[1]) == "12") {
                                            $jourApres = intval($date[2]) % 31;
                                            if ($jourApres < 10) {
                                                $date[2] = "0" . $jourApres;
                                            }
                                            $date[0] = intval($date[0]) + 1;
                                            $date[1] = "01";
                                            $dateActuelle = implode("-", $date);
                                        } else if (intval($date[2]) >= 32) {
                                            $nbMois = floor(intval($date[2] / 31));
                                            $jourApres = intval($date[2]) % 31;
                                            if ($jourApres < 10) {
                                                $date[2] = "0" . $jourApres;
                                            }
                                            $mois = intval($date[1]) + $nbMois;
                                            if ($mois < 10) {
                                                $date[1] = "0" . $mois;
                                            } else {
                                                $date[1] = $mois;
                                            }
                                            $dateActuelle = implode("-", $date);
                                        }
                                        /* récupération de données : jour de la semaine et mois */
                                        $monthName = date("m", strtotime($dateActuelle));
                                        $dayofweek = date('w', strtotime($dateActuelle));
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
                                        /* mise en forme du mois */
                                        $mois;
                                        switch ($monthName) {
                                            case 1:
                                                $mois = "Janvier";
                                                break;
                                            case 2:
                                                $mois = "Février";
                                                break;
                                            case 3:
                                                $mois = "Mars";
                                                break;
                                            case 4:
                                                $mois = "Avril";
                                                break;
                                            case 5:
                                                $mois = "Mai";
                                                break;
                                            case 6:
                                                $mois = "Juin";
                                                break;
                                            case 7:
                                                $mois = "Juillet";
                                                break;
                                            case 8:
                                                $mois = "Août";
                                                break;
                                            case 9:
                                                $mois = "Septembre";
                                                break;
                                            case 10:
                                                $mois = "Octobre";
                                                break;
                                            case 11:
                                                $mois = "Novembre";
                                                break;
                                            case 12:
                                                $mois = "Décembre";
                                                break;
                                        }

                                        $jourActuel = $jourDeLaSemaine . " " . $date['2'] . " " . $mois . " " . $date['0'];
                                        /* affichage dans les cases */
                                        if ($i % 2 == 0) {
                                            echo "<th class='tabTitre' style='text-align:center;top:0;'>" . $jourActuel . "</th>";
                                        } else {
                                            echo "<th class='tabTitre' style='text-align:center;top:0; background-color : $couleur3'>" . $jourActuel . "</th>";
                                        }
                                        array_push($jours, $jourActuel);
                                    }

                                    for ($heure = 0; $heure < $nbHeure; $heure++) {
                                        $lignePlanning = array();
                                        echo "<tr>";
                                        /* AFFICHAGE DE L'HEURE */
                                        echo "<th class='tabTitre' style='text-align:center;min-width:60px;left:0;'><p style='color:". $couleur4."'>$heure h</p></th>";

                                        /* REMPLISSAGE DU PLANNING */
                                        for ($jour = 0; $jour < $nbJour; $jour++) {

                                            /* recherche de la date actuelle */
                                            $dateDebut = explode("-", $dateJ1);
                                            $dateSuivante = intval($dateDebut['2']) + $jour;
                                            if ($dateSuivante < 10) {
                                                $dateDebut['2'] = "0" . $dateSuivante;
                                            } else {
                                                $dateDebut['2'] = $dateSuivante;
                                            }
                                            $date = implode("-", $dateDebut);

                                            if ($heure < 10) {
                                                $time = '0' . $heure . ':00:00';
                                            } else {
                                                $time = $heure . ':00:00';
                                            }

                                            $heureDispo = explode(":", $row7['heureDispoJ1']);
                                            if ($jour == 0 && $heure < intval($heureDispo['0'])) {
                                                $lignePlanning[$jours[$jour]] = "Indisponible";
                                                echo "<td style='padding:0;background-color:" . $couleur5 . ";'></td>";
                                            } else {
                                                $dateDebut = explode("-", $date);
                                                if (intval($dateDebut[2]) >= 30 && intval($dateDebut[1]) == "02") {
                                                    $nbMois = floor(intval($dateDebut[2] / 29));
                                                    $jourApres = intval($dateDebut[2]) % 29;
                                                    if ($jourApres < 10) {
                                                        $dateDebut[2] = "0" . $jourApres;
                                                    }
                                                    $mois = intval($date[1]) + $nbMois;
                                                    if ($mois < 10) {
                                                        $dateDebut[1] = "0" . $mois;
                                                    } else {
                                                        $dateDebut[1] = $mois;
                                                    }
                                                    $date = implode("-", $dateDebut);
                                                } else if (intval($dateDebut[2]) >= 31 && (intval($dateDebut[1]) == "04" || intval($dateDebut[1]) == "06" || intval($dateDebut[1]) == "09" || intval($dateDebut[1]) == "11")) {
                                                    $nbMois = floor(intval($dateDebut[2] / 30));
                                                    $jourApres = intval($dateDebut[2]) % 30;
                                                    if ($jourApres < 10) {
                                                        $dateDebut[2] = "0" . $jourApres;
                                                    }
                                                    $mois = intval($date[1]) + $nbMois;
                                                    if ($mois < 10) {
                                                        $dateDebut[1] = "0" . $mois;
                                                    } else {
                                                        $dateDebut[1] = $mois;
                                                    }
                                                    $date = implode("-", $dateDebut);
                                                } else if (intval($dateDebut[2]) >= 32 && intval($dateDebut[1]) == "12") {
                                                    $jourApres = intval($dateDebut[2]) % 31;
                                                    if ($jourApres < 10) {
                                                        $dateDebut[2] = "0" . $jourApres;
                                                    }
                                                    $dateDebut[0] = intval($dateDebut[0]) + 1;
                                                    $dateDebut[1] = "01";
                                                    $date = implode("-", $dateDebut);
                                                } else if (intval($dateDebut[2]) >= 32) {
                                                    $nbMois = floor(intval($dateDebut[2] / 31));
                                                    $jourApres = intval($dateDebut[2]) % 31;
                                                    if ($jourApres < 10) {
                                                        $dateDebut[2] = "0" . $jourApres;
                                                    }
                                                    $mois = intval($dateDebut[1]) + $nbMois;
                                                    if ($mois < 10) {
                                                        $dateDebut[1] = "0" . $mois;
                                                    } else {
                                                        $dateDebut[1] = $mois;
                                                    }
                                                    $date = implode("-", $dateDebut);
                                                }
                                                $requete4 = "SELECT * FROM creneau WHERE idEdition='" . $_SESSION['edition'] . "' AND dateDebut='" . $date . "' AND heureDebut='" . $time . "'";
                                                $result4 = mysqli_query($link, $requete4);
                                                if (mysqli_num_rows($result4) > 0) {
                                                    $caseOk = false;
                                                    while ($row4 = mysqli_fetch_assoc($result4)) {
                                                        $requete5 = "SELECT * FROM travailler WHERE idPersonne='" . $personneActuelle . "' AND idCreneau='" . $row4['idCreneau'] . "'";
                                                        $result5 = mysqli_query($link, $requete5);
                                                        if (mysqli_num_rows($result5) == 1) {
                                                            $requete6 = "SELECT * FROM lieu WHERE idLieu='" . $row4['idLieu'] . "'";
                                                            $result6 = mysqli_query($link, $requete6);
                                                            if (mysqli_num_rows($result6) == 1) {
                                                                $caseOk = true;
                                                                $row6 = mysqli_fetch_assoc($result6);
                                                                $lignePlanning[$jours[$jour]] = $row6['nom'];
                                                                echo "<td style='padding:0; background-color: " . $row6['idCouleur'] . ";color:" . $couleur1 . ";'>";
                                                                echo "<button type='submit' class='boutonSansFond' name='versLieu' value='" . $row6['idLieu'] . "' style='font-weight: bold;'>";
                                                                $nomCase = $row6['nom'];
                                                                if (strlen($nomCase) > 40) {
                                                                    for ($i = 0; $i < strlen($row6['nom']); $i++) {
                                                                        echo $nomCase[$i];
                                                                        if ($i % 37 == 0 && $i != 0) {
                                                                            echo " ";
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo $row6['nom'];
                                                                }
                                                                echo "</button></td>";
                                                            }
                                                        }
                                                    }
                                                    if ($caseOk == false) {
                                                        $lignePlanning[$jours[$jour]] = "Pause";
                                                        echo "<td style='padding:0;'><p style='margin:0'>Pause</p></td>";
                                                    }
                                                } else {
                                                    $lignePlanning[$jours[$jour]] = "AucunCreneau";
                                                    echo "<td style='padding:0;background-color:" . $couleur7 . ";border-color:" . $couleur4 . ";'>";
                                                    echo "<button type='submit' class='boutonSansFond tailleComplete' name='versAjoutCreneau' style='margin:0;padding:0;color:" . $couleur4 . ";'>Créneau inexistant</button>";
                                                    echo "</td>";
                                                }
                                            }
                                        }
                                        echo "</tr>";
                                        $planningATelecharger[$heure . "h"] = $lignePlanning;
                                    }

                                    unset($_SESSION['planningPDFPersonne']);
                                    unset($_SESSION['planningPDFLieu']);
                                    unset($_SESSION['planningPDFGeneral']);
                                    $_SESSION['planningPDFPersonne'] = $planningATelecharger;
                                    $_SESSION['redirectPDF'] = "planningPersonne";
                                } else {
                                    $erreur = true;
                                    $_SESSION['error'] = "Nous n'avons pas pu récupérer les informations dans la base de données.";
                                } ?>
                            </table>
                        </div>
                    </div>
                </div>

                <div style="margin: 0% 4%;width: 33%;position: absolute;height: 70%;">
                    <?php if (isset($typePersonne)) {
                        if ($typePersonne == 'Membre') {
                            echo "<div style='position: RELATIVE; background-color: " . $couleur3 . ";width: 100%;height: 65%;'>";
                            echo "<img src='../images/membre.jpg' style='max-height: 50%;margin: 3%;float : left;'></img>";
                        } else if ($typePersonne == 'Benevole') {
                            echo "<div style='position: RELATIVE; background-color: " . $couleur2 . ";width: 100%;height: 65%;'>";
                            echo "<img src='../images/benevole.jpg' style='max-height: 50%;margin: 3%;float : left;'></img>";
                        } else {
                            echo "<div style='position: RELATIVE; background-color: " . $couleur1 . ";width: 100%;height: 65%;'>";
                        }
                    } else {
                        echo "<div style='position: RELATIVE; background-color: " . $couleur1 . ";width: 100%;height: 65%;'>";
                    } ?>

                    <div class="ModifierInfoPersonne">
                        <div class="blocModifierInfoHaut">
                            <input class="champModifierPersonne champModifierPersonneHaut" style="color:<?php echo $couleur4 ?>;border-color : <?php echo $couleur7 ?>;" type="text" value="<?php
                                                                                                                                                                                            if (isset($_SESSION['nom'])) {
                                                                                                                                                                                                echo $_SESSION['nom'];
                                                                                                                                                                                            } else if (isset($nomPersonne)) {
                                                                                                                                                                                                echo $nomPersonne;
                                                                                                                                                                                            } ?>" name="nom" id="nom">
                            <input class="champModifierPersonne champModifierPersonneHaut" style="color:<?php echo $couleur4 ?>;border-color : <?php echo $couleur7 ?>;" type="text" value="<?php
                                                                                                                                                                                            if (isset($_SESSION['prenom'])) {
                                                                                                                                                                                                echo $_SESSION['prenom'];
                                                                                                                                                                                            } else if (isset($prenomPersonne)) {
                                                                                                                                                                                                echo $prenomPersonne;
                                                                                                                                                                                            } ?>" name="prenom" id="prenom">
                            <input class="champModifierPersonne champModifierPersonneHaut" style="color:<?php echo $couleur4 ?>;border-color : <?php echo $couleur7 ?>;" type="text" value="<?php
                                                                                                                                                                                            if (isset($_SESSION['telephone'])) {
                                                                                                                                                                                                echo $_SESSION['telephone'];
                                                                                                                                                                                            } else if (isset($telPersonne)) {
                                                                                                                                                                                                echo $telPersonne;
                                                                                                                                                                                            } ?>" name="telephone" id="telephone">
                            <input class="champModifierPersonne champModifierPersonneHaut" style="color:<?php echo $couleur4 ?>;border-color : <?php echo $couleur7 ?>;" type="text" value="<?php
                                                                                                                                                                                            if (isset($_SESSION['email'])) {
                                                                                                                                                                                                echo $_SESSION['email'];
                                                                                                                                                                                            } else  if (isset($mailPersonne)) {
                                                                                                                                                                                                echo $mailPersonne;
                                                                                                                                                                                            } ?>" name="email" id="email">
                        </div>

                        <div class="blocModifierInfoBas">

                            <div class="champBas">
                                <label class="champModifierPersonneBas" style='border-color: <?php echo $couleur7 ?>;color: <?php echo $couleur7 ?>;'>Heure de dispo J1 : </label>
                                <input class="champModifierPersonne  champModifierPersonneBas" style="color:<?php echo $couleur4 ?>;border-color: <?php echo $couleur7 ?>;" type="time" name="disponibilite" id="disponibilite" value=<?php
                                                                                                                                                                                                                                        if (isset($_SESSION['disponibilite'])) {
                                                                                                                                                                                                                                            echo $_SESSION['disponibilite'];
                                                                                                                                                                                                                                        } else if (isset($heureDispo)) {
                                                                                                                                                                                                                                            echo $heureDispo['0'] . ":" . $heureDispo['1'];
                                                                                                                                                                                                                                        } ?>>
                            </div>
                            <div>
                                <?php
                                if (isset($typePersonne)) {
                                    if ($typePersonne == "Benevole") {
                                ?>
                                        <label class="champModifierPersonneBas" style='border-color: <?php echo $couleur7 ?>; color: <?php echo $couleur7 ?>;'>Nombre d'heures à effectuer : </label>
                                        <input class="champModifierPersonne  champModifierPersonneBas" style="width : 15%; color:<?php echo $couleur4 ?>;border-color: <?php echo $couleur1 ?>;" name="heuresTravail" type="number" value="<?php if (isset($_SESSION['heuresTravail'])) {
                                                                                                                                                                                                                                                echo $_SESSION['heuresTravail'];
                                                                                                                                                                                                                                            } else if (isset($personneActuelle)) {
                                                                                                                                                                                                                                                $requete2 = "SELECT * FROM participer WHERE idPersonne='" . $personneActuelle . "' AND idEdition='" . $_SESSION['edition'] . "'";
                                                                                                                                                                                                                                                $result2 = mysqli_query($link, $requete2);
                                                                                                                                                                                                                                                if (mysqli_num_rows($result2) == 1) {
                                                                                                                                                                                                                                                    $row2 = mysqli_fetch_assoc($result2);
                                                                                                                                                                                                                                                    echo $row2['nbHeureAFaire'];
                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                            } else if (isset($_SESSION['edition'])) {
                                                                                                                                                                                                                                                $requete3 = "SELECT nbHeureBenevoleDefaut FROM edition WHERE idEdition='" . $_SESSION['edition'] . "'";
                                                                                                                                                                                                                                                $result3 = mysqli_query($link, $requete3);
                                                                                                                                                                                                                                                if (mysqli_num_rows($result3) == 1) {
                                                                                                                                                                                                                                                    $row3 = mysqli_fetch_assoc($result3);
                                                                                                                                                                                                                                                    echo $row3['nbHeureBenevoleDefaut'];
                                                                                                                                                                                                                                                } else {
                                                                                                                                                                                                                                                    echo $nombreHeureBenevolesDefaut;
                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                            } ?>"><br>

                                <?php
                                    }
                                }
                                ?>
                            </div>

                            <textarea class="champBas champModifierPersonneBas" style="border-style: solid; border-color:<?php echo $couleur7 ?>; color:<?php echo $couleur4 ?>; resize: none;" type="text" placeholder="informations supplémentaires" name="informations" id="informations" rows="2" cols="50"><?php if (isset($_SESSION['informations'])) echo $_SESSION['informations'];
                                                                                                                                                                                                                                                                                                                else if (isset($infoPersonne)) {
                                                                                                                                                                                                                                                                                                                    echo $infoPersonne;
                                                                                                                                                                                                                                                                                                                } ?></textarea><br>

                        </div>
                    </div>

                    <?php if (isset($typePersonne)) {
                        if ($typePersonne == 'Membre') {
                            echo "<button type='submit' name='annulerModifsCarte' class='boutonSansFond boutonRouge' style='background:" . $couleur3 . ";margin-top:2%;position: relative;color:" . $couleur4 . ";'>Annuler les modifs</button>";
                        } else if ($typePersonne == 'Benevole') {
                            echo "<button type='submit' name='annulerModifsCarte' class='boutonSansFond boutonRouge' style='background:" . $couleur2 . ";margin-top:2%;position: relative;color:" . $couleur4 . ";'>Annuler les modifs</button>";
                        } else {
                            echo "<button type='submit' name='annulerModifsCarte' class='boutonSansFond boutonRouge' style='background:" . $couleur1 . ";margin-top:2%;position: relative;color:" . $couleur4 . ";'>Annuler les modifs</button>";
                        }
                    } else {
                        echo "<button type='submit' name='annulerModifsCarte' class='boutonSansFond boutonRouge' style='background:" . $couleur1 . ";margin-top:2%;position: relative;color:" . $couleur4 . ";'>Annuler les modifs</button>";
                    } ?>
                </div>
                <div style="width: 60%;position: RELATIVE;float:right;margin: 3% 0%;">
                    <button type="submit" name="modifsCarte" style='border: none;background: none;margin: 2%;position: relative;text-align: right;text-transform: uppercase;font-weight: 600;font-size: 20px;line-height: 120%;color:<?php echo $couleur4?>; text-shadow: -5px 8px 6px rgba(234, 0, 28, 0.72);'>Enregistrer la carte</button>
            </form>
            <form action="planningPersonne.php" method="post" target="_blank">
                <button type="submit" name="telechargerPDF" style='BORDER: none;background: none;margin: 2%;position: relative;text-align: right;text-transform: uppercase;font-weight: 600;font-size: 20px;line-height: 120%;color:<?php echo $couleur4?>; text-shadow: -5px 8px 6px rgba(234, 0, 28, 0.72);'>Télécharger en PDF</button>
            </form>
            <form action="planningPersonne.php" method="post">
                <button type="submit" name="envoyerMail" style='BORDER: none;background: none;text-transform: uppercase;margin: 2%;position: relative;text-align: right;font-weight: 600;font-size: 20px;line-height: 120%;color:<?php echo $couleur4?>; text-shadow: -5px 8px 6px rgba(234, 0, 28, 0.72);'>Envoyer le PDF par mail</button>
            </form>
            </div>
            </div>



<?php } else {
            $erreur = true;
            $_SESSION['error'] = "Nous n'avons pas pu récupérer les informations dans la base de données.";
        }
    } else {
        $erreur = true;
        $_SESSION['error'] = "Nous n'avons pas pu récupérer les informations dans la base de données.";
    }
} ?>