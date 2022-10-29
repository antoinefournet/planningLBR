<!--                                * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *             Fichier PHP             *
                                    * Contenu du tab  planning simplifié  *
                                    *                                     *
                                    * * * * * * * * * * * * * * * * * * * *    
-->

<?php
/* initialisation du nombre d'heure */
$nbHeure = 24; ?>


<!-- emplacement général contenant le tableau -->
<div id="simplifie" class="tabcontent" style="display: block; overflow-x: auto; overflow-y: auto; height: 100%;">
    <table id="tableau">
        <?php
        echo "<tr>";
        echo "<th rowspan='2' class='tabTitre'></th>";

        /* initialisation du booléen erreur */
        $erreur = false;


        /* * * * * * * * * * * * * * * REQUETE NUMERO 1 * * * * * * * * * * * * * * */

        /* sélectionner les infos de l'édition active */
        $requete1 = "SELECT * FROM edition WHERE idEdition='" . $_SESSION['edition'] . "'";
        $result1 = mysqli_query($link, $requete1);
        if (mysqli_num_rows($result1) == 1) {
            $row1 = mysqli_fetch_assoc($result1);
            $dateJ1 = $row1['dateJ1'];
            $nbJour = $row1['nbJour'];

            /* parcours des jours de l'édition */
            for ($i = 0; $i < $nbJour; $i++) {

                /* obtention de la date actuelle */
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

                /* fonction pour afficher en toute lettre le jour et le mois */
                $monthName = date("m", strtotime($dateActuelle));
                $dayofweek = date('w', strtotime($dateActuelle));
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

                /* assemblage des différentes parties de la date */
                $jourActuel = $jourDeLaSemaine . " " . $date['2'] . " " . $mois . " " . $date['0'];

                /* affichage de 2 couleurs différentes des cases contenant le jour */
                if ($i % 2 == 0) {
                    echo "<th class='tabTitre' colspan='6' style='top:0px;text-align:center;z-index:3;'>" . $jourActuel . " - Nuit</th>";
                    echo "<th class='tabTitre' colspan='6' style='top:0px;text-align:center;z-index:3;'>" . $jourActuel . " - Matin</th>";
                    echo "<th class='tabTitre' colspan='6' style='top:0px;text-align:center;z-index:3;'>" . $jourActuel . " - Aprèm</th>";
                    echo "<th class='tabTitre' colspan='6' style='top:0px;text-align:center;z-index:3;'>" . $jourActuel . " - Soir</th>";
                } else {
                    echo "<th class='tabTitre' colspan='6' style='top:0px;text-align:center;background-color : $couleur3';z-index:3;>" . $jourActuel . " - Nuit</th>";
                    echo "<th class='tabTitre' colspan='6' style='top:0px;text-align:center;background-color : $couleur3';z-index:3;>" . $jourActuel . " - Matin</th>";
                    echo "<th class='tabTitre' colspan='6' style='top:0px;text-align:center;background-color : $couleur3';z-index:3;>" . $jourActuel . " - Aprèm</th>";
                    echo "<th class='tabTitre' colspan='6' style='top:0px;text-align:center;background-color : $couleur3';z-index:3;>" . $jourActuel . " - Soir</th>";
                }
            }
            echo "</tr>";

            /* ligne contenant les heures */
            echo "<tr>";
            for ($jour = 0; $jour < $nbJour; $jour++) {
                if ($jour % 2 == 0) {
                    for ($heure = 0; $heure < $nbHeure; $heure++) {
                        echo "<th style='top:36px;z-index:3;text-align:center;min-width:175px;' class='tabTitre'>" . $heure . "h</th>";
                    }
                } else {
                    for ($heure = 0; $heure < $nbHeure; $heure++) {
                        echo "<th style='top:36px;z-index:3;text-align:center;min-width:175px;background-color : $couleur3'' class='tabTitre'>" . $heure . "h</th>";
                    }
                }
            }
            echo "</tr>";


            /* * * * * * * * * * * * * * * REQUETE NUMERO 2 * * * * * * * * * * * * * * */

            /* sélectionner les infos des lieux participant à l'édition par ordre alphabétique */
            $requete2 = "SELECT * FROM lieu WHERE idLieu IN(SELECT idLieu FROM exister WHERE idEdition='" . $_SESSION['edition'] . "') ORDER BY nom ASC";
            $result2 = mysqli_query($link, $requete2);
            if (mysqli_num_rows($result2) > 0) {
                while ($row2 = mysqli_fetch_assoc($result2)) {
                    if ($erreur == false) {

                        /* affichage des lieux et redirection */
                        echo "<tr>";
                        echo "<td class='tabTitre' style='min-width:150px;left:0px;background-color :" . $couleur2 . ";'>";
                        echo "<button type='submit' name='lieu' class='boutonSansFond' style='color:" . $couleur4 . ";' value='" . $row2['idLieu'] . "'>";
                        $nomCase = $row2['nom'];
                        if (strlen($nomCase) > 20) {
                            for ($i = 0; $i < strlen($row2['nom']); $i++) {
                                echo $nomCase[$i];
                                if ($i % 17 == 0 && $i != 0) {
                                    echo " ";
                                }
                            }
                        } else {
                            echo $row2['nom'];
                        }
                        echo "</button></td>";

                        /* parcours des heures et chaque jour */
                        for ($jour = 0; $jour < $nbJour; $jour++) {
                            for ($heure = 0; $heure < $nbHeure; $heure++) {
                                $dateDebut = explode("-", $dateJ1);

                                /* mise en forme des dates */
                                $dateSuivante = intval($dateDebut['2']) + $jour;
                                if ($dateSuivante < 10) {
                                    $dateDebut['2'] = "0" . $dateSuivante;
                                } else {
                                    $dateDebut['2'] = $dateSuivante;
                                }

                                /* mise en forme des heures */
                                $date = implode("-", $dateDebut);
                                if ($heure < 10) {
                                    $heureDebut = "0" . $heure . ":00:00";
                                    $time = '0' . $heure . ':00:00';
                                } else {
                                    $heureDebut = $heure . ":00:00";
                                    $time = $heure . ':00:00';
                                }

                                /* initialisation des compteurs */
                                $nbMembreAffecte = 0;
                                $nbBenevoleAffecte = 0;


                                /* * * * * * * * * * * * * * * REQUETE NUMERO 3 * * * * * * * * * * * * * * */

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
                                /* sélectionner les infos des créneaux de l'édition à ce moment là et ce lieu là */
                                $requete3 = "SELECT * FROM creneau WHERE idEdition='" . $_SESSION['edition'] . "' AND idLieu='" . $row2['idLieu'] . "' AND dateDebut='" . $date . "' AND heureDebut='" . $time . "'";
                                $result3 = mysqli_query($link, $requete3);
                                if (mysqli_num_rows($result3) == 1) {
                                    $row3 = mysqli_fetch_assoc($result3);
                                    $idCreneau = $row3['idCreneau'];
                                    $nbBenevoleTotal = $row3['nbBenevoleManquant'];
                                    $nbMembreTotal = $row3['nbMembreManquant'];

                                    /* mise à jour des compteurs */
                                    $nbBenevoleManquant = $nbBenevoleTotal;
                                    $nbMembreManquant = $nbMembreTotal;


                                    /* * * * * * * * * * * * * * * REQUETE NUMERO 4 * * * * * * * * * * * * * * */

                                    /* sélectionner les personnes qui travaillent en ce lieu à ce moment là */
                                    $requete4 = "SELECT * FROM travailler WHERE idCreneau='" . $idCreneau . "'";
                                    $result4 = mysqli_query($link, $requete4);
                                    if (mysqli_num_rows($result4) > 0) {
                                        while ($row4 = mysqli_fetch_assoc($result4)) {
                                            $idPersonne = $row4['idPersonne'];


                                            /* * * * * * * * * * * * * * * REQUETE NUMERO 5 * * * * * * * * * * * * * * */

                                            /* sélectionner les infos des personnes trouvées */
                                            $requete5 = "SELECT * FROM personne WHERE idPersonne='" . $idPersonne . "'";
                                            $result5 = mysqli_query($link, $requete5);
                                            if (mysqli_num_rows($result5) == 1) {
                                                $row5 = mysqli_fetch_assoc($result5);
                                                $typePersonne = $row5['typePersonne'];

                                                /* mise à jour des compteurs */
                                                if ($typePersonne == "Membre") {
                                                    $nbMembreAffecte = $nbMembreAffecte + 1;
                                                    $nbMembreManquant = $nbMembreManquant - 1;
                                                } else if ($typePersonne == "Benevole") {
                                                    $nbBenevoleAffecte = $nbBenevoleAffecte + 1;
                                                    $nbBenevoleManquant = $nbBenevoleManquant - 1;
                                                }
                                            }
                                        }
                                    }

                                    /* compte des bénévoles/membres manquants et accord de la phrase */
                                    if ($nbMembreManquant <= 0) {
                                        if ($nbBenevoleManquant <= 0) {
                                            $manque = "";
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

                                    /* remplissage des cases */
                                    if ($manque == "") {
                                        echo "<td style='background-color: #C6E0B4 '> OK </td>";
                                    } else {
                                        echo "<td>" . $manque . "</td>";
                                    }
                                } else {
                                    /* si le créneau n'existe pas encore : aucun lieu n'a besoin besoin de bénévole ou membre pendant cette heure */
                                    echo "<td style='padding:0;background-color:" . $couleur7 . ";border-color:" . $couleur4 . ";'>";
                                    echo "<button type='submit' class='boutonSansFond tailleComplete' name='ajoutCreneauLieu' style='margin:0;padding:0;color:" . $couleur4 . ";' value='" . $row2['idLieu'] . "'>Créneau inexistant</button>";
                                    echo "</td>";
                                }
                            }
                        }
                    }
                }
            }
            echo "</tr>";
        } else {
            /* si on n'a pas trouvé l'édition */
            $erreur = true;
            $_SESSION['error'] = "Nous n'avons pas pu récupérer les informations dans la base de données.";
        } ?>
    </table>
</div>

<!-- insertion du script pour les fonctions -->
<script type="text/javascript" src="../js/planningGeneral.js"></script>