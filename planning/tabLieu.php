<!--                                * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *             Fichier PHP             *
                                    *   Contenu du tab - planning lieu    *
                                    *                                     *
                                    * * * * * * * * * * * * * * * * * * * *    
-->
<style>
    .souligneHover:hover {
        text-decoration: underline;
        cursor: pointer;
    }


    .lieuDeroulant {
        position: relative;
        display: inline-block;
    }

    .lieuDeroulant-content {
        display: none;
        position: absolute;
        overflow: auto;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
        width: 100%;
        text-align: center;
        padding: 1%;
    }

    .lieuDeroulant-content a {
        display: block;
    }

    .onAffiche {
        display: block;
    }
</style>
<?php

$nbJour;
$nbPersonne;
$nbHeure = 24;

if (isset($_SESSION['versLieu'])) {
    $lieuActuel = $_SESSION['versLieu'];
} else {
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
    $requete0 = "SELECT * FROM lieu WHERE idLieu='" . $lieuActuel . "'";
    $result0 = mysqli_query($link, $requete0);
    if (mysqli_num_rows($result0) == 1) {
        $row0 = mysqli_fetch_assoc($result0);
        $nomLieu = $row0['nom'];
        $idCouleur = $row0['idCouleur'];

?>

        <div class="lieuDeroulant" style='width:100%'>
            <button onclick="ouverture()" class="lieuDeroulantBtn boutonSansFond tailleComplete">
                <i class='fa fa-caret-down' style="color: <?php echo $couleur4 ?>; transform: scale(3);position: relative;right: 30px;bottom: 15px;"></i>
                <span id='nomLieuChoisi' class='titrePrincipal' style="color: <?php echo $couleur4 ?>">
                    <?php
                    if (isset($_SESSION['versLieu'])) {


                        /* * * * * * * * * * * * * * * REQUETE NUMERO 2 * * * * * * * * * * * * * * */

                        /* sélectionner les infos des lieux participant à l'édition par ordre alphabétique */
                        $requete01 = "SELECT * FROM lieu WHERE idLieu='" . $_SESSION['versLieu'] . "'";
                        $result01 = mysqli_query($link, $requete01);
                        if (mysqli_num_rows($result01) == 1) {
                            $row01 = mysqli_fetch_assoc($result01);
                            echo $row01['nom'];
                        }
                    }
                    ?>
                </span>
            </button>
            <div id="lieuxQuiDeroulent" class="lieuDeroulant-content" style="background: <?php echo $couleur7 ?>;">
                <form action='actionPlanningLieu.php' method='POST'>
                    <?php

                    /* * * * * * * * * * * * * * * REQUETE NUMERO 2 * * * * * * * * * * * * * * */

                    /* sélectionner les infos des lieux participant à l'édition par ordre alphabétique */
                    $requete02 = "SELECT * FROM lieu WHERE idLieu IN(SELECT idLieu FROM exister WHERE idEdition='" . $_SESSION['edition'] . "') ORDER BY nom ASC";
                    $result02 = mysqli_query($link, $requete02);
                    if (mysqli_num_rows($result02) > 0) {
                        while ($row02 = mysqli_fetch_assoc($result02)) {
                            echo "<button type='submit' class='boutonSansFond ecritureOmbreRouge' name='lieuChoisi' value='" . $row02['idLieu'] . "' style='color:" . $couleur4 . "'>" . $row02['nom'] . "</button><br>";
                        }
                    } ?>
                </form>
            </div>
        </div>

        <div class="tab rectanglePlanning" style="background-color: <?php echo $couleur4 ?>;">

            <form action="planningLieu.php" method="post" style="width:100%; height:100%;">

                <div id="Lieu" class="tabcontent" style="overflow-x: auto; height: 100%;">
                    <table style="margin: 0;min-width:100%;min-height:100%;">

                <?php
                /* recherche de toutes les dates du festival */
                $requete1 = "SELECT * FROM edition WHERE idEdition='" . $_SESSION['edition'] . "'";
                $result1 = mysqli_query($link, $requete1);
                if (mysqli_num_rows($result1) == 1) {
                    $row1 = mysqli_fetch_assoc($result1);
                    $dateJ1 = $row1['dateJ1'];
                    $nbJour = $row1['nbJour'];

                    echo "<tr>";
                    echo "<th colspan='2' class='tabTitre'></th>";
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

                    echo "</tr>";


                    $nuit = false;
                    $matin = false;
                    $aprem = false;
                    $soir = false;
                    for ($heure = 0; $heure < $nbHeure; $heure++) {
                        $lignePlanning = array();
                        echo "<tr>";
                        /* affichage des parties de la journée en fonction de l'heure pour un dutur accordéon */
                        if ($heure < 6 && !$nuit) {
                            echo "<th rowspan='6' class='tabTitre' style='text-align:center;min-width:90px;left:0;'><p style='color:". $couleur4 ."'>Nuit</p></th>";
                            $nuit = true;
                        }
                        if ($heure >= 6 && $heure < 12 && !$matin) {
                            echo "<th rowspan='6' class='tabTitre' style='text-align:center;min-width:90px;left:0;'><p style='color:". $couleur4 ."'>Matin</p></th>";
                            $matin = true;
                        }
                        if ($heure >= 12 && $heure < 18 && !$aprem) {
                            echo "<th rowspan='6' class='tabTitre' style='text-align:center;min-width:90px;left:0;'><p style='color:". $couleur4 ."'>Aprèm</p></th>";
                            $aprem = true;
                        }
                        if ($heure >= 18 && $heure < 24 && !$soir) {
                            echo "<th rowspan='6' class='tabTitre' style='text-align:center;min-width:90px;left:0;'><p style='color:'>S". $couleur4 ."oir</p></th>";
                            $soir = true;
                        }

                        /* affichage de l'heure */
                        echo "<th class='tabTitre' style='text-align:center;min-width:60px;left:0;'><p style='color:". $couleur4 ."'>$heure h</p></th>";

                        /* remplissage du planning */
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

                            $listePersonnes = "";
                            $pdfListePersonne = "";
                            $testListe = false;
                            $nbMembreAffecte = 0;
                            $nbBenevoleAffecte = 0;

                            if (intval($dateDebut[2]) >= 30 && intval($dateDebut[1]) == "02") {
                                $nbMois = floor(intval($dateDebut[2] / 29));
                                $jourApres = intval($dateDebut[2]) % 29;
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
                            } else if (intval($dateDebut[2]) >= 31 && (intval($dateDebut[1]) == "04" || intval($dateDebut[1]) == "06" || intval($dateDebut[1]) == "09" || intval($dateDebut[1]) == "11")) {
                                $nbMois = floor(intval($dateDebut[2] / 30));
                                $jourApres = intval($dateDebut[2]) % 30;
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

                            $requete2 = "SELECT * FROM creneau WHERE idEdition='" . $_SESSION['edition'] . "' AND idLieu='" . $lieuActuel . "' AND dateDebut='" . $date . "' AND heureDebut='" . $time . "'";
                            $result2 = mysqli_query($link, $requete2);
                            if (mysqli_num_rows($result2) == 1) {
                                $row2 = mysqli_fetch_assoc($result2);
                                $idCreneau = $row2['idCreneau'];
                                $nbBenevoleTotal = $row2['nbBenevoleManquant'];
                                $nbMembreTotal = $row2['nbMembreManquant'];

                                $nbBenevoleManquant = $nbBenevoleTotal;
                                $nbMembreManquant = $nbMembreTotal;

                                $requete3 = "SELECT * FROM travailler WHERE idCreneau='" . $idCreneau . "'";
                                $result3 = mysqli_query($link, $requete3);
                                if (mysqli_num_rows($result3) > 0) {
                                    while ($row3 = mysqli_fetch_assoc($result3)) {
                                        $idPersonne = $row3['idPersonne'];

                                        $requete4 = "SELECT * FROM personne WHERE idPersonne='" . $idPersonne . "'";
                                        $result4 = mysqli_query($link, $requete4);
                                        if (mysqli_num_rows($result4) == 1) {
                                            $row4 = mysqli_fetch_assoc($result4);
                                            $nomPersonne = $row4['nom'];
                                            $prenomPersonne = $row4['prenom'];
                                            $telPersonne = $row4['telephone'];
                                            $typePersonne = $row4['typePersonne'];

                                            if ($typePersonne == "Membre") {
                                                $nbMembreAffecte = $nbMembreAffecte + 1;
                                                $nbMembreManquant = $nbMembreManquant - 1;
                                                $listePersonnes = $listePersonnes . "<button type='submit' value='" . $idPersonne . "' name='versPersonne' style='margin:0;width:100%;border:none;background:none;color:" . $couleur3 . "'>" . $nomPersonne . " " . $prenomPersonne . " - " . $telPersonne . "</button><br>";
                                            } else if ($typePersonne == "Benevole") {
                                                $nbBenevoleAffecte = $nbBenevoleAffecte + 1;
                                                $nbBenevoleManquant = $nbBenevoleManquant - 1;
                                                $listePersonnes = $listePersonnes . "<button type='submit' value='" . $idPersonne . "' name='versPersonne' class='boutonSansFond' style='margin:0;width:100%;color:" . $couleur2 . "'>" . $nomPersonne . " " . $prenomPersonne . " - " . $telPersonne . "</button><br>";
                                            } else {
                                                $listePersonnes = $listePersonnes . "<button type='submit' value='" . $idPersonne . "' name='versPersonne' style='margin:0;width:100%;border:none;background:none;color:" . $couleur1 . "'>" . $nomPersonne . " " . $prenomPersonne . " - " . $telPersonne . "</button><br>";
                                            }
                                            $testListe = true;
                                            $pdfListePersonne = $pdfListePersonne . $nomPersonne . " " . $prenomPersonne . " - " . $telPersonne . "/ ";
                                        } else {
                                        }
                                    }
                                } else {
                                }

                                if (!$testListe) {
                                    $listPersonne = "";
                                } else {
                                    $listPersonne = $listePersonnes . " <br>";
                                }
                                $lignePlanning[$jours[$jour]] = $pdfListePersonne;

                                if ($nbMembreManquant <= 0) {
                                    if ($nbBenevoleManquant <= 0) {
                                        $manque = "";
                                        $listPersonne = $listePersonnes;
                                    } else if ($nbBenevoleManquant == 1) {
                                        $manque = "Manque : 1 bénévole";
                                    } else {
                                        $manque = "Manque : " . $nbBenevoleManquant . " bénévoles";
                                    }
                                } else if ($nbMembreManquant == 1) {
                                    if ($nbBenevoleManquant == 0) {
                                        $manque = "Manque : 1 membre";
                                    } else if ($nbBenevoleManquant == 1) {
                                        $manque = "Manque : 1 membre <br> et 1 bénévole";
                                    } else {
                                        $manque = "Manque : 1 membre <br> et " . $nbBenevoleManquant . " bénévoles";
                                    }
                                } else {
                                    if ($nbBenevoleManquant == 0) {
                                        $manque = "Manque : " . $nbMembreManquant . " membres";
                                    } else if ($nbBenevoleManquant == 1) {
                                        $manque = "Manque : " . $nbMembreManquant . " membres <br> et 1 bénévole";
                                    } else {
                                        $manque = "Manque : " . $nbMembreManquant . " membres <br> et " . $nbBenevoleManquant . " bénévoles";
                                    }
                                }

                                if ($manque == "") {
                                    echo "<td style='background-color: #C6E0B4 '>" . $listPersonne . $manque . "</td>";
                                } else {
                                    echo "<td>" . $listPersonne . $manque . "</td>";
                                }
                            } else {
                                $lignePlanning[$jours[$jour]] = '';
                                /* ajouter un bouton qui redirige pour ajouter un créneau */
                                echo "<td style='padding:0;background-color:" . $couleur7 . ";border-color:" . $couleur4 . ";'>";
                                echo "<button type='submit' class='boutonSansFond tailleComplete' name='ajoutCreneauLieu' style='margin:0;padding:0;color:" . $couleur4 . ";' value='" . $lieuActuel . "'>Créneau inexistant</button>";
                                echo "</td>";
                            }
                        }
                        echo "</tr>";
                        $planningATelecharger[$heure . "h"] = $lignePlanning;
                    }

                    unset($_SESSION['planningPDFPersonne']);
                    unset($_SESSION['planningPDFLieu']);
                    unset($_SESSION['planningPDFGeneral']);
                    $_SESSION['planningPDFLieu'] = $planningATelecharger;
                    $_SESSION['redirectPDF'] = "planningLieu";
                } else {
                    $erreur = true;
                    $_SESSION['error'] = "Nous n'avons pas pu récupérer les informations dans la base de données.";
                }
                echo "</table>";
            } else {
                $erreur = true;
                $_SESSION['error'] = "Nous n'avons pas pu récupérer les informations dans la base de données.";
            }
        }

                ?>
                </div>
        </div>

        <div style="margin-top: 34.5%;">
            <?php echo "<button type='submit' class='boutonSansFond' name='ajoutCreneauLieu' style='position:absolute;top: 30%;margin-top: 5%;left: 20px;font-weight: 600;font-size: 20px;transform:rotate(-90deg);line-height: 120%;color:". $couleur4 ."; text-shadow: -5px 8px 6px rgba(234, 0, 28, 0.72);' value='" . $lieuActuel . "'>Paramètres lieu</button>"; ?>
        </div>
    </form>
    <form action='planningLieu.php' method='post' target="_blank">
        <button type="submit" name="telechargerPDFLieu" style='background: none; border: none; position:absolute;top: 35%;margin-top: 20%;left: auto;font-weight: 600;font-size: 20px;transform:rotate(-90deg);line-height: 120%;color:". $couleur4 ."; text-shadow: -5px 8px 6px rgba(234, 0, 28, 0.72);'>Télécharger en PDF</button>
    </form>