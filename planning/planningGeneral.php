<!--                                * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *             Fichier PHP             *
                                    *        Planning général et zoom     *
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

/* redirection vers le menu édition s'il y a une erreur */
if (!isset($_SESSION['edition'])) {
    $_SESSION['error'] = "Un problème est survenu.";
    header('location:../edition/menuEdition.php');
    die();
  } 

/* suppression des variables de session */
unset($_SESSION['personne']);
unset($_SESSION['versLieu']);
?>


<!-- style nécessitant du PHP pour la palette de couleur -->
<style>
    table {
        border: 1px solid <?php echo $couleur7 ?>;
        background-color: <?php echo $couleur4 ?>;
        color: <?php echo $couleur7 ?>;
    }

    th,
    td {
        border: 2px solid <?php echo $couleur7 ?>;
    }

    .close {
        color: <?php echo $couleur7 ?>;
    }

    .tabTitre {
        background-color: <?php echo $couleur2 ?>;
        color: <?php echo $couleur4 ?>;
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
        border: 2px solid <?php echo $couleur7 ?>;
    }
</style>

<?php
if (isset($_SESSION['caseVide'])) { ?>
    <form action="actionPlanningGeneral.php" method="post">
        <div id="caseVide" class="tailleComplete modalCase">

            <!-- modal rectangle de couleur jaune -->
            <div class="interieurModalCase" style="color:<?php echo $couleur7 ?>; background-color: <?php echo $couleur4 ?>;">
                <!-- bouton pour fermer le modal + redirection vers planning général -->
                <button type='submit' name='close' class="close">&times;</button>

                <?php

                /* récupération des données définissant la case : personne, heure, jour... */
                $donnees = explode('_', $_SESSION['caseVide']);

                /* suppression de la variable de données */
                unset($_SESSION['caseVide']);

                /* * * * * * * * * * * * * * * REQUETE NUMERO 1 * * * * * * * * * * * * * * */

                /* sélectionner les infos de la personne de la ligne */
                $idPersonne = $donnees['2'];
                $requete1 = "SELECT * FROM personne WHERE idPersonne='" . $donnees['2'] . "'";
                $result1 = mysqli_query($link, $requete1);
                if (mysqli_num_rows($result1) == 1) {
                    $row1 = mysqli_fetch_assoc($result1);
                    $nomPersonne = $row1['nom'];
                    $prenomPersonne = $row1['prenom'];
                    $typePersonne = $row1['typePersonne'];

                    /* réécriture du type de la personne */
                    if ($typePersonne == 'Benevole') {
                        $typePersonne = 'bénévole';
                    } else if ($typePersonne == 'Membre') {
                        $typePersonne = 'membre';
                    }

                    $requete11 = "SELECT * FROM participer WHERE idEdition='" . $_SESSION['edition'] . "' AND idPersonne='" . $row1['idPersonne'] . "'";
                    $result11 = mysqli_query($link, $requete11);
                    if (mysqli_num_rows($result11) == 1) {
                        $row11 = mysqli_fetch_assoc($result11);

                        /* cas d'exception si le nombre d'heures restantes est négatif */
                        $nbHeureRestante = intval($row11['nbHeureAFaire']) - intval($row11['nbHeurePlanifiee']);
                        if ($nbHeureRestante < 0) {
                            $nbHeureRestante = 0;
                        }

                        /* * * * * * * * * * * * * * * REQUETE NUMERO 2 * * * * * * * * * * * * * * */

                        /* sélectionner la date du 1e jour de l'édition */
                        $requete2 = "SELECT dateJ1 FROM edition WHERE idEdition='" . $_SESSION['edition'] . "'";
                        $result2 = mysqli_query($link, $requete2);
                        if (mysqli_num_rows($result2) == 1) {
                            $row2 = mysqli_fetch_assoc($result2);
                            $dateJ1 = explode("-", $row2['dateJ1']);

                            /* recherche du jour actuel */
                            $jour = intval($dateJ1['2']) + intval($donnees['0']);

                            /* écriture du jour avec un '0' devant */
                            if ($jour < 10) {
                                $dateJ1['2'] = "0" . $jour;
                            } else {
                                $dateJ1['2'] = $jour;
                            }
                            $date = $dateJ1['0'] . "-" . $dateJ1['1'] . "-" . $dateJ1['2'];

                            /* réécriture de l'heure avec minutes et secondes */
                            if (intval($donnees['1']) < 10) {
                                $heure = "0" . $donnees['1'] . ":00:00";
                            } else {
                                $heure = $donnees['1'] . ":00:00";
                            }

                            /* initialisation de variables */
                            $nbHeureJour = 0;

                            /* * * * * * * * * * * * * * * REQUETE NUMERO 3 * * * * * * * * * * * * * * */

                            /* sélectionner les infos du créneau qui correspond à la case : même édition, personne, jour, heure... */
                            $requete3 = "SELECT * FROM creneau WHERE idCreneau IN(SELECT idCreneau FROM travailler WHERE idPersonne='" . $donnees['2'] . "') AND dateDebut='" . $date . "' AND idEdition='" . $_SESSION['edition'] . "'";
                            $result3 = mysqli_query($link, $requete3);
                            while ($row3 = mysqli_fetch_assoc($result3)) {
                                /* incrémentation du nombre de jours */
                                $nbHeureJour++;
                            }

                            /* réécriture du jour pour pouvoir l'afficher */
                            if (intval($donnees['1']) < 10) {
                                $time = "0" . $donnees['1'];
                            } else {
                                $time = $donnees['1'];
                            }

                            /* cas d'exception : si le créneau commence à 23h il se finit à 00h (pas 24h) */
                            if ($time < 23) {
                                $timeFin = $time + 1;
                            } else if ($time == 23) {
                                $timeFin = '00';
                            }

                            /* récupération de la date et ajout du jour de la semaine */
                            $dateActuelle = implode("-", $dateJ1);
                            $dateJ1 = explode('-', $date);
                            $dateActuelle = implode("-", $dateJ1);

                            if (intval($dateJ1[2]) >= 30 && intval($dateJ1[1]) == "02") {
                                $nbMois = floor(intval($dateJ1[2] / 29));
                                $jourApres = intval($dateJ1[2]) % 29;
                                if ($jourApres < 10) {
                                    $dateJ1[2] = "0" . $jourApres;
                                }
                                $mois = intval($dateJ1[1]) + $nbMois;
                                if ($mois < 10) {
                                    $dateJ1[1] = "0" . $mois;
                                } else {
                                    $dateJ1[1] = $mois;
                                }
                                $dateActuelle = implode("-", $dateJ1);
                            } else if (intval($dateJ1[2]) >= 31 && (intval($dateJ1[1]) == "04" || intval($dateJ1[1]) == "06" || intval($dateJ1[1]) == "09" || intval($dateJ1[1]) == "11")) {
                                $nbMois = floor(intval($dateJ1[2] / 30));
                                $jourApres = intval($dateJ1[2]) % 30;
                                if ($jourApres < 10) {
                                    $dateJ1[2] = "0" . $jourApres;
                                }
                                $mois = intval($dateJ1[1]) + $nbMois;
                                if ($mois < 10) {
                                    $dateJ1[1] = "0" . $mois;
                                } else {
                                    $dateJ1[1] = $mois;
                                }
                                $dateActuelle = implode("-", $dateJ1);
                            } else if (intval($dateJ1[2]) >= 32 && intval($dateJ1[1]) == "12") {
                                $jourApres = intval($dateJ1[2]) % 31;
                                if ($jourApres < 10) {
                                    $dateJ1[2] = "0" . $jourApres;
                                }
                                $dateJ1[0] = intval($dateJ1[0]) + 1;
                                $dateJ1[1] = "01";
                                $dateActuelle = implode("-", $dateJ1);
                            } else if (intval($dateJ1[2]) >= 32) {
                                $nbMois = floor(intval($dateJ1[2] / 31));
                                $jourApres = intval($dateJ1[2]) % 31;
                                if ($jourApres < 10) {
                                    $dateJ1[2] = "0" . $jourApres;
                                }
                                $mois = intval($dateJ1[1]) + $nbMois;
                                if ($mois < 10) {
                                    $dateJ1[1] = "0" . $mois;
                                } else {
                                    $dateJ1[1] = $mois;
                                }
                                $dateActuelle = implode("-", $dateJ1);
                            }

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

                            /* TITRE DU MODAL */
                            echo "<h2>" . $nomPersonne . " " . $prenomPersonne . " - " . $jourDeLaSemaine . " " . $dateJ1['2'] . "/" . $dateJ1['1'] . "/" . $dateJ1['0'] . " - " . $time . "h00 à " . $timeFin  . "h00</h2>";

                            /* phrase d'intro explicatives avec données chiffrées */
                            echo "<div>";
                            echo "<span><button type='submit' class='boutonSansFond' name='versPersonne' value='" . $idPersonne . "' style='font-weight: bold;color:" . $couleur1 . ";'>" . $nomPersonne . " " . $prenomPersonne . "</button></span>";
                            echo " est en pause.";
                            if ($typePersonne != 'membre') {
                                echo "<span>Il reste <span style='font-weight: bold;color:" . $couleur1 . "'>" . $nbHeureRestante . "h</span> à planifier pour " . $row1['prenom'] . " lors de cette édition.</span>";
                            }

                            echo "<br>Nombre d'heure(s) planifiée(s) ce jour-là pour ce " . $typePersonne . " : <span style='font-weight: bold;color:" . $couleur1 . "'>" . $nbHeureJour . "h</span>";
                            echo "<br><br></div>";

                            /* tableau récapitulatif des lieux dispo à ce créneau */
                            echo "<div style='display: block; overflow-x: auto; height: 72%;''>";
                            echo "<table style='width:100%;'>";


                            /* * * * * * * * * * * * * * * REQUETE NUMERO 4 * * * * * * * * * * * * * * */

                            /* sélectionner les infos des lieux dispos correspondant au créneau */
                            $jourSuivant = explode("-", $date);

                            $date = implode("-", $jourSuivant);
                            if (intval($jourSuivant[2]) >= 30 && intval($jourSuivant[1]) == "02") {
                                $nbMois = floor(intval($jourSuivant[2] / 29));
                                $jourApres = intval($jourSuivant[2]) % 29;
                                if ($jourApres < 10) {
                                    $jourSuivant[2] = "0" . $jourApres;
                                } else {
                                    $jourSuivant[2] = $jourApres;
                                }
                                $mois = intval($jourSuivant[1]) + $nbMois;
                                if ($mois < 10) {
                                    $jourSuivant[1] = "0" . $mois;
                                } else {
                                    $jourSuivant[1] = $mois;
                                }
                                $date = implode("-", $jourSuivant);
                            } else if (intval($jourSuivant[2]) >= 31 && (intval($jourSuivant[1]) == "04" || intval($jourSuivant[1]) == "06" || intval($jourSuivant[1]) == "09" || intval($jourSuivant[1]) == "11")) {
                                $nbMois = floor(intval($jourSuivant[2] / 30));
                                $jourApres = intval($jourSuivant[2]) % 30;
                                if ($jourApres < 10) {
                                    $jourSuivant[2] = "0" . $jourApres;
                                }
                                $mois = intval($jourSuivant[1]) + $nbMois;
                                if ($mois < 10) {
                                    $jourSuivant[1] = "0" . $mois;
                                } else {
                                    $jourSuivant[1] = $mois;
                                }
                                $date = implode("-", $jourSuivant);
                            } else if (intval($jourSuivant[2]) >= 32 && intval($jourSuivant[1]) == "12") {
                                $jourApres = intval($jourSuivant[2]) % 31;
                                if ($jourApres < 10) {
                                    $jourSuivant[2] = "0" . $jourApres;
                                } else {
                                    $jourSuivant[2] = $jourApres;
                                }
                                $jourSuivant[0] = intval($jourSuivant[0]) + 1;
                                $jourSuivant[1] = "01";
                                $date = implode("-", $jourSuivant);
                            } else if (intval($jourSuivant[2]) >= 32) {
                                $nbMois = floor(intval($jourSuivant[2] / 31));
                                $jourApres = intval($jourSuivant[2]) % 31;
                                if ($jourApres < 10) {
                                    $jourSuivant[2] = "0" . $jourApres;
                                }
                                $mois = intval($jourSuivant[1]) + $nbMois;
                                if ($mois < 10) {
                                    $jourSuivant[1] = "0" . $mois;
                                } else {
                                    $jourSuivant[1] = $mois;
                                }
                                $date = implode("-", $jourSuivant);
                            }
                            $requete4 = "SELECT * FROM lieu WHERE idLieu IN(SELECT idLieu FROM creneau WHERE idEdition='" . $_SESSION['edition'] . "' AND dateDebut='" . $date . "' AND heureDebut='" . $heure . "')";
                            $result4 = mysqli_query($link, $requete4);
                            if (mysqli_num_rows($result4) > 0) {

                                /* création d'une ligne d'entête */
                                echo "<tr style='background-color: " . $couleur2 . ";'>";
                                echo "<th>Nom du Lieu</th>";
                                echo "<th>Besoin en " . $typePersonne . "</th>";
                                echo "<th>Nombre d'heure(s) effectué(es) par " . $prenomPersonne . " dans le lieu</th>";
                                echo "<th></th>";
                                echo "</tr>";

                                while ($row4 = mysqli_fetch_assoc($result4)) {

                                    /* pour chaque lieu dispo... */


                                    /* * * * * * * * * * * * * * * REQUETE NUMERO 5 * * * * * * * * * * * * * * */

                                    /* sélectionner les infos du créneau correspondant à ce lieu */
                                    $requete5 = "SELECT * FROM creneau WHERE dateDebut='" . $date . "' AND heureDebut='" . $heure . "' AND idLieu='" . $row4['idLieu'] . "' AND idEdition='" . $_SESSION['edition'] . "'";
                                    $result5 = mysqli_query($link, $requete5);
                                    if (mysqli_num_rows($result5) == 1) {
                                        $row5 = mysqli_fetch_assoc($result5);


                                        /* * * * * * * * * * * * * * * REQUETE NUMERO 6 * * * * * * * * * * * * * * */

                                        /* sélectionner les infos de la personne correspondant à la ligne */
                                        $requete6 = "SELECT * FROM personne WHERE idPersonne='" . $donnees['2'] . "'";
                                        $result6 = mysqli_query($link, $requete6);
                                        if (mysqli_num_rows($result6) == 1) {
                                            $row6 = mysqli_fetch_assoc($result6);
                                            $nbBenevoleManquant = $row5['nbBenevoleManquant'];
                                            $nbMembreManquant = $row5['nbMembreManquant'];


                                            /* * * * * * * * * * * * * * * REQUETE NUMERO 7 * * * * * * * * * * * * * * */

                                            /* sélectionner les id des personnes qui travaillent à ce moment à ce lieu */
                                            $requete7 = "SELECT * FROM travailler WHERE idCreneau='" . $row5['idCreneau'] . "'";
                                            $result7 = mysqli_query($link, $requete7);
                                            if (mysqli_num_rows($result7) > 0) {
                                                while ($row7 = mysqli_fetch_assoc($result7)) {


                                                    /* * * * * * * * * * * * * * * REQUETE NUMERO 8 * * * * * * * * * * * * * * */

                                                    /* sélectionner les infos de la personne */
                                                    $requete8 = "SELECT * FROM personne WHERE idPersonne='" . $row7['idPersonne'] . "'";
                                                    $result8 = mysqli_query($link, $requete8);
                                                    if (mysqli_num_rows($result8) == 1) {
                                                        $row8 = mysqli_fetch_assoc($result8);

                                                        /* décrémentation des nombres de personnes manquantes */
                                                        if ($row8['typePersonne'] == "Benevole") {
                                                            $nbBenevoleManquant--;
                                                        } else if ($row8['typePersonne'] == "Membre") {
                                                            $nbMembreManquant--;
                                                        }
                                                    }
                                                }
                                            }

                                            /* si la personne est un bénévole */
                                            if ($row6['typePersonne'] == "Benevole") {

                                                /* s'il ne manque qu'un bénévole */
                                                if ($nbBenevoleManquant == 1) {
                                                    echo "<tr>";
                                                    echo "<th style='background-color: " . $couleur2 . "'><button type='submit' name='versPlanningLieu' value='" . $row4['idLieu'] . "' class='boutonSansFond'>" . $row4['nom'] . "</button></th>";
                                                    echo "<td>" . $nbBenevoleManquant . " bénévole</td>";

                                                    /* variable pour le nombre d'heure de la personne passée dans ce lieu */
                                                    $nbHeureLieu = 0;


                                                    /* * * * * * * * * * * * * * * REQUETE NUMERO 9 * * * * * * * * * * * * * * */

                                                    /* sélectionner les infos du créneau correspondant à ce lie et cette personne */
                                                    $requete9 = "SELECT * FROM creneau WHERE idLieu='" . $row4['idLieu'] . "' AND idCreneau IN(SELECT idCreneau FROM travailler WHERE idPersonne='" . $donnees['2'] . "')";
                                                    $result9 = mysqli_query($link, $requete9);
                                                    if (mysqli_num_rows($result9) > 0) {
                                                        while ($row9 = mysqli_fetch_assoc($result9)) {
                                                            /* on incrémente le compteur */
                                                            $nbHeureLieu++;
                                                        }
                                                    }
                                                    echo "<td>" . $nbHeureLieu . "h</td>";

                                                    /* bouton pour sélectionner le lieu et donc assigner la personne à un créneau */
                                                    echo "<td><button type='submit' name='selectionnerLieu' value='" . $date . "_" . $heure . "_" . $donnees['2'] . "_" . $row4['idLieu'] . "' class='boutonSansFond' style='color:" . $couleur1 . "'>Sélectionner ce lieu</button>";
                                                    echo "</tr>";
                                                }

                                                /* s'il y a plus d'un bénévole manquant */ else if ($nbBenevoleManquant > 0) {
                                                    echo "<tr>";
                                                    echo "<th style='background-color: " . $couleur2 . "'><button type='submit' name='versPlanningLieu' value='" . $row4['idLieu'] . "' class='boutonSansFond'>" . $row4['nom'] . "</button></th>";
                                                    echo "<td>" . $nbBenevoleManquant . " bénévoles</td>";

                                                    /* variable pour le nombre d'heure de la personne passée dans ce lieu */
                                                    $nbHeureLieu = 0;


                                                    /* * * * * * * * * * * * * * * REQUETE NUMERO 9 * * * * * * * * * * * * * * */

                                                    /* sélectionner les infos du créneau correspondant à ce lie et cette personne */
                                                    $requete9 = "SELECT * FROM creneau WHERE idLieu='" . $row4['idLieu'] . "' AND idCreneau IN(SELECT idCreneau FROM travailler WHERE idPersonne='" . $donnees['2'] . "')";
                                                    $result9 = mysqli_query($link, $requete9);
                                                    if (mysqli_num_rows($result9) > 0) {
                                                        while ($row9 = mysqli_fetch_assoc($result9)) {
                                                            /* on incrémente */
                                                            $nbHeureLieu++;
                                                        }
                                                    }
                                                    echo "<td>" . $nbHeureLieu . "h</td>";

                                                    /* bouton pour sélectionner le lieu et donc assigner la personne à un créneau */
                                                    echo "<td><button type='submit' name='selectionnerLieu' value='" . $date . "_" . $heure . "_" . $donnees['2'] . "_" . $row4['idLieu'] . "' style='color:" . $couleur1 . "' class='boutonSansFond'>Sélectionner ce lieu</button>";
                                                    echo "</tr>";
                                                }

                                                /* s'il ne manque pas de bénévoles */ else {
                                                    echo "<tr>";
                                                    echo "<th style='background-color: " . $couleur2 . "'><button type='submit' name='versPlanningLieu' value='" . $row4['idLieu'] . "' class='boutonSansFond'>" . $row4['nom'] . "</button></th>";
                                                    echo "<td>" . $nbBenevoleManquant . " bénévoles</td>";

                                                    /* variable pour le nombre d'heure de la personne passée dans ce lieu */
                                                    $nbHeureLieu = 0;


                                                    /* * * * * * * * * * * * * * * REQUETE NUMERO 9 * * * * * * * * * * * * * * */

                                                    /* sélectionner les infos du créneau correspondant à ce lie et cette personne */
                                                    $requete9 = "SELECT * FROM creneau WHERE idLieu='" . $row4['idLieu'] . "' AND idCreneau IN(SELECT idCreneau FROM travailler WHERE idPersonne='" . $donnees['2'] . "')";
                                                    $result9 = mysqli_query($link, $requete9);
                                                    if (mysqli_num_rows($result9) > 0) {
                                                        while ($row9 = mysqli_fetch_assoc($result9)) {
                                                            /* on incrémente */
                                                            $nbHeureLieu++;
                                                        }
                                                    }
                                                    echo "<td>" . $nbHeureLieu . "h</td>";

                                                    /* bouton pour sélectionner le lieu et donc assigner la personne à un créneau */
                                                    echo "<td><button type='submit' name='selectionnerLieu' value='" . $date . "_" . $heure . "_" . $donnees['2'] . "_" . $row4['idLieu'] . "' style='color:" . $couleur1 . "' class='boutonSansFond'>Sélectionner ce lieu</button>";
                                                    echo "</tr>";
                                                }
                                            }

                                            /* si la personne est un membre */ else if ($row6['typePersonne'] == "Membre") {

                                                /* s'il ne manque qu'un seul membre */
                                                if ($nbMembreManquant == 1) {
                                                    echo "<tr>";
                                                    echo "<th style='background-color: " . $couleur2 . "'><button type='submit' name='versPlanningLieu' value='" . $row4['idLieu'] . "' class='boutonSansFond'>" . $row4['nom'] . "</button></th>";
                                                    echo "<td>" . $nbMembreManquant . " membre</td>";

                                                    /* variable pour le nombre d'heure de la personne passée dans ce lieu */
                                                    $nbHeureLieu = 0;


                                                    /* * * * * * * * * * * * * * * REQUETE NUMERO 9 * * * * * * * * * * * * * * */

                                                    /* sélectionner les infos du créneau correspondant à ce lie et cette personne */
                                                    $requete9 = "SELECT * FROM creneau WHERE idLieu='" . $row4['idLieu'] . "' AND idCreneau IN(SELECT idCreneau FROM travailler WHERE idPersonne='" . $donnees['2'] . "')";
                                                    $result9 = mysqli_query($link, $requete9);
                                                    if (mysqli_num_rows($result9) > 0) {
                                                        while ($row9 = mysqli_fetch_assoc($result9)) {
                                                            /* on incrémente */
                                                            $nbHeureLieu++;
                                                        }
                                                    }
                                                    echo "<td>" . $nbHeureLieu . "h</td>";

                                                    /* bouton pour sélectionner le lieu et donc assigner la personne à un créneau */
                                                    echo "<td><button type='submit' name='selectionnerLieu' value='" . $date . "_" . $heure . "_" . $donnees['2'] . "_" . $row4['idLieu'] . "' class='boutonSansFond' style='color:" . $couleur1 . "'>Sélectionner ce lieu</button>";
                                                    echo "</tr>";
                                                }

                                                /* s'il manque plus qu'un membre */ else if ($nbMembreManquant > 0) {
                                                    echo "<tr>";
                                                    echo "<th style='background-color: " . $couleur2 . "'><button type='submit' name='versPlanningLieu' value='" . $row4['idLieu'] . "' class='boutonSansFond'>" . $row4['nom'] . "</button></th>";
                                                    echo "<td>" . $nbMembreManquant . " membres</td>";

                                                    /* variable pour le nombre d'heure de la personne passée dans ce lieu */
                                                    $nbHeureLieu = 0;


                                                    /* * * * * * * * * * * * * * * REQUETE NUMERO 9 * * * * * * * * * * * * * * */

                                                    /* sélectionner les infos du créneau correspondant à ce lie et cette personne */
                                                    $requete9 = "SELECT * FROM creneau WHERE idLieu='" . $row4['idLieu'] . "' AND idCreneau IN(SELECT idCreneau FROM travailler WHERE idPersonne='" . $donnees['2'] . "')";
                                                    $result9 = mysqli_query($link, $requete9);
                                                    if (mysqli_num_rows($result9) > 0) {
                                                        while ($row9 = mysqli_fetch_assoc($result9)) {
                                                            /* on incrémente */
                                                            $nbHeureLieu++;
                                                        }
                                                    }
                                                    echo "<td>" . $nbHeureLieu . "h</td>";

                                                    /* bouton pour sélectionner le lieu et donc assigner la personne à un créneau */
                                                    echo "<td><button type='submit' name='selectionnerLieu' value='" . $date . "_" . $heure . "_" . $donnees['2'] . "_" . $row4['idLieu'] . "' class='boutonSansFond' style='color:" . $couleur1 . "'>Sélectionner ce lieu</button>";
                                                    echo "</tr>";
                                                }

                                                /* s'il manque ne manque aucun membre */ else {
                                                    echo "<tr>";
                                                    echo "<th style='background-color: " . $couleur2 . "'><button type='submit' name='versPlanningLieu' value='" . $row4['idLieu'] . "' class='boutonSansFond'>" . $row4['nom'] . "</button></th>";
                                                    echo "<td>" . $nbMembreManquant . " membres</td>";

                                                    /* variable pour le nombre d'heure de la personne passée dans ce lieu */
                                                    $nbHeureLieu = 0;


                                                    /* * * * * * * * * * * * * * * REQUETE NUMERO 9 * * * * * * * * * * * * * * */

                                                    /* sélectionner les infos du créneau correspondant à ce lie et cette personne */
                                                    $requete9 = "SELECT * FROM creneau WHERE idLieu='" . $row4['idLieu'] . "' AND idCreneau IN(SELECT idCreneau FROM travailler WHERE idPersonne='" . $donnees['2'] . "')";
                                                    $result9 = mysqli_query($link, $requete9);
                                                    if (mysqli_num_rows($result9) > 0) {
                                                        while ($row9 = mysqli_fetch_assoc($result9)) {
                                                            /* on incrémente */
                                                            $nbHeureLieu++;
                                                        }
                                                    }
                                                    echo "<td>" . $nbHeureLieu . "h</td>";

                                                    /* bouton pour sélectionner le lieu et donc assigner la personne à un créneau */
                                                    echo "<td><button type='submit' name='selectionnerLieu' value='" . $date . "_" . $heure . "_" . $donnees['2'] . "_" . $row4['idLieu'] . "' class='boutonSansFond' style='color:" . $couleur1 . "'>Sélectionner ce lieu</button>";
                                                    echo "</tr>";
                                                }
                                            }
                                        } else {
                                            /* cas d'erreur si nous ne trouvons pas la personne */
                                            $erreur = true;
                                            $_SESSION['error'] = "Les informations n'ont pas pu être récupérés dans la base de données.";
                                        }
                                    } else {
                                        /* cas d'erreur si nous ne trouvons pas le créneau */
                                        $erreur = true;
                                        $_SESSION['error'] = "Les informations n'ont pas pu être récupérés dans la base de données.";
                                    }
                                }
                            }
                        } else {
                            /* cas d'erreur si nous ne trouvons pas la date du J1 de l'édition */
                            $erreur = true;
                            $_SESSION['error'] = "Les informations n'ont pas pu être récupérés dans la base de données.";
                        }
                    } else {
                        $erreur = true;
                        $_SESSION['error'] = "Les informations n'ont pas pu être insérés dans la base de données.";
                    }
                } else {
                    /* cas d'erreur si nous ne trouvons pas la personne de la ligne */
                    $erreur = true;
                    $_SESSION['error'] = "Les informations n'ont pas pu être récupérés dans la base de données.";
                }
                ?>
                </tbody>
                </table>
            </div>
        </div>
        </div>
    </form>
<?php }


/* menu qui s'affiche au clic sur une case remplie */ else if (isset($_SESSION['caseRemplie'])) {
?>

    <form action="actionPlanningGeneral.php" method="post">
        <div id="caseRemplie" class="tailleComplete modalCase">

            <!-- modal rectangle de couleur jaune -->
            <div class="interieurModalCase" style="color:<?php echo $couleur7 ?>; background-color: <?php echo $couleur4 ?>;">
                <!-- bouton pour fermer le modal + redirection vers planning général -->
                <button type='submit' name='close' class="close">&times;</button>

                <?php

                /* récupération des données définissant la case : personne, heure, jour... */
                $donnees = explode('_', $_SESSION['caseRemplie']);

                /* suppression de la variable de données */
                unset($_SESSION['caseRemplie']);


                /* * * * * * * * * * * * * * * REQUETE NUMERO 0 * * * * * * * * * * * * * * */

                /* sélectionner les infos du lieu rempli dans la case */
                $requete0 = "SELECT * FROM lieu WHERE idLieu ='" . $donnees['3'] . "'";
                $result0 = mysqli_query($link, $requete0);
                if (mysqli_num_rows($result0) == 1) {
                    $row0 = mysqli_fetch_assoc($result0);
                    $nomLieuActuel = $row0['nom'];
                    $couleurLieuActuel = $row0['idCouleur'];


                    /* * * * * * * * * * * * * * * REQUETE NUMERO 1 * * * * * * * * * * * * * * */

                    /* sélectionner les infos de la personne de la ligne */
                    $idPersonne = $donnees['2'];
                    $requete1 = "SELECT * FROM personne WHERE idPersonne='" . $idPersonne . "'";
                    $result1 = mysqli_query($link, $requete1);
                    if (mysqli_num_rows($result1) == 1) {
                        $row1 = mysqli_fetch_assoc($result1);
                        $nomPersonne = $row1['nom'];
                        $prenomPersonne = $row1['prenom'];
                        $typePersonne = $row1['typePersonne'];

                        /* réécriture du type de la personne */
                        if ($typePersonne == 'Benevole') {
                            $typePersonne = 'bénévole';
                        } else if ($typePersonne == 'Membre') {
                            $typePersonne = 'membre';
                        }
                        //ICI
                        $requete11 = "SELECT * FROM participer WHERE idEdition='" . $_SESSION['edition'] . "' AND idPersonne='" . $row1['idPersonne'] . "'";
                        $result11 = mysqli_query($link, $requete11);
                        if (mysqli_num_rows($result11) == 1) {
                            $row11 = mysqli_fetch_assoc($result11);

                            /* cas d'exception si le nombre d'heures restantes est négatif */
                            $nbHeureRestante = intval($row11['nbHeureAFaire']) - intval($row11['nbHeurePlanifiee']);
                            if ($nbHeureRestante < 0) {
                                $nbHeureRestante = 0;
                            }

                            /* * * * * * * * * * * * * * * REQUETE NUMERO 2 * * * * * * * * * * * * * * */

                            /* sélectionner la date du 1e jour de l'édition */
                            $requete2 = "SELECT dateJ1 FROM edition WHERE idEdition='" . $_SESSION['edition'] . "'";
                            $result2 = mysqli_query($link, $requete2);
                            if (mysqli_num_rows($result2) == 1) {
                                $row2 = mysqli_fetch_assoc($result2);
                                $dateJ1 = explode("-", $row2['dateJ1']);

                                /* recherche du jour actuel */
                                $jour = intval($dateJ1['2']) + intval($donnees['0']);

                                /* écriture du jour avec un '0' devant */
                                if ($jour < 10) {
                                    $dateJ1['2'] = "0" . $jour;
                                } else {
                                    $dateJ1['2'] = $jour;
                                }
                                $date = $dateJ1['0'] . "-" . $dateJ1['1'] . "-" . $dateJ1['2'];

                                /* réécriture de l'heure avec minutes et secondes */
                                if (intval($donnees['1']) < 10) {
                                    $heure = "0" . intval($donnees['1']) . ":00:00";
                                } else {
                                    $heure = intval($donnees['1']) . ":00:00";
                                }

                                /* initialisation de variables */
                                $nbHeureJour = 0;

                                /* * * * * * * * * * * * * * * REQUETE NUMERO 3 * * * * * * * * * * * * * * */

                                /* sélectionner les infos du créneau qui correspond à la case : même édition, personne, jour, heure... */
                                $requete3 = "SELECT * FROM creneau WHERE idCreneau IN(SELECT idCreneau FROM travailler WHERE idPersonne='" . $donnees['2'] . "') AND dateDebut='" . $date . "' AND idEdition='" . $_SESSION['edition'] . "'";
                                $result3 = mysqli_query($link, $requete3);
                                while ($row3 = mysqli_fetch_assoc($result3)) {
                                    /* incrémentation du nombre de jours */
                                    $nbHeureJour++;
                                }

                                /* réécriture du jour pour pouvoir l'afficher */
                                if (intval($donnees['1']) < 10) {
                                    $time = "0" . $donnees['1'];
                                } else {
                                    $time = $donnees['1'];
                                }

                                /* cas d'exception : si le créneau commence à 23h il se finit à 00h (pas 24h) */
                                if ($time < 23) {
                                    $timeFin = $time + 1;
                                } else if ($time == 23) {
                                    $timeFin = '00';
                                }

                                /* récupération de la date et ajout du jour de la semaine */
                                $dateActuelle = implode("-", $dateJ1);
                                $dateJ1 = explode('-', $date);
                                $dateActuelle = implode("-", $dateJ1);

                                if (intval($dateJ1[2]) >= 30 && intval($dateJ1[1]) == "02") {
                                    $nbMois = floor(intval($dateJ1[2] / 29));
                                    $jourApres = intval($dateJ1[2]) % 29;
                                    if ($jourApres < 10) {
                                        $dateJ1[2] = "0" . $jourApres;
                                    }
                                    $mois = intval($dateJ1[1]) + $nbMois;
                                    if ($mois < 10) {
                                        $dateJ1[1] = "0" . $mois;
                                    } else {
                                        $dateJ1[1] = $mois;
                                    }
                                    $dateActuelle = implode("-", $dateJ1);
                                } else if (intval($dateJ1[2]) >= 31 && (intval($dateJ1[1]) == "04" || intval($dateJ1[1]) == "06" || intval($dateJ1[1]) == "09" || intval($dateJ1[1]) == "11")) {
                                    $nbMois = floor(intval($dateJ1[2] / 30));
                                    $jourApres = intval($dateJ1[2]) % 30;
                                    if ($jourApres < 10) {
                                        $dateJ1[2] = "0" . $jourApres;
                                    }
                                    $mois = intval($dateJ1[1]) + $nbMois;
                                    if ($mois < 10) {
                                        $dateJ1[1] = "0" . $mois;
                                    } else {
                                        $dateJ1[1] = $mois;
                                    }
                                    $dateActuelle = implode("-", $dateJ1);
                                } else if (intval($dateJ1[2]) >= 32 && intval($dateJ1[1]) == "12") {
                                    $jourApres = intval($dateJ1[2]) % 31;
                                    if ($jourApres < 10) {
                                        $dateJ1[2] = "0" . $jourApres;
                                    }
                                    $dateJ1[0] = intval($dateJ1[0]) + 1;
                                    $dateJ1[1] = "01";
                                    $dateActuelle = implode("-", $dateJ1);
                                } else if (intval($dateJ1[2]) >= 32) {
                                    $nbMois = floor(intval($dateJ1[2] / 31));
                                    $jourApres = intval($dateJ1[2]) % 31;
                                    if ($jourApres < 10) {
                                        $dateJ1[2] = "0" . $jourApres;
                                    }
                                    $mois = intval($dateJ1[1]) + $nbMois;
                                    if ($mois < 10) {
                                        $dateJ1[1] = "0" . $mois;
                                    } else {
                                        $dateJ1[1] = $mois;
                                    }
                                    $dateActuelle = implode("-", $dateJ1);
                                }
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

                                /* TITRE DU MODAL */
                                echo "<h2>" . $nomPersonne . " " . $prenomPersonne . " - " . $jourDeLaSemaine . " " . $dateJ1['2'] . "/" . $dateJ1['1'] . "/" . $dateJ1['0'] . " - " . $time . "h00 à " . $timeFin  . "h00</h2>";

                                /* initialisation du compteur d'heure dans ce lieu */
                                $nbHeureLieuActuel = 0;

                                /* * * * * * * * * * * * * * * REQUETE NUMERO 10 * * * * * * * * * * * * * * */

                                /* sélectionner les infos du créneau correspondant au lieu et à la personne */
                                $requete10 = "SELECT * FROM creneau WHERE idLieu='" . $donnees['3'] . "' AND idCreneau IN(SELECT idCreneau FROM travailler WHERE idPersonne='" . $donnees['2'] . "')";
                                $result10 = mysqli_query($link, $requete10);
                                if (mysqli_num_rows($result10) > 0) {
                                    while ($row10 = mysqli_fetch_assoc($result10)) {
                                        /* incrémentation du compteur dans ce lieu */
                                        $nbHeureLieuActuel++;
                                    }
                                }

                                /* phrase d'intro explicatives avec données chiffrées */
                                echo "<div>";
                                echo "<span><button type='submit' class='boutonSansFond' name='versPersonne' value='" . $idPersonne . "' style='font-weight: bold;color:" . $couleur1 . ";'>" . $nomPersonne . " " . $prenomPersonne . "</button></span>";
                                echo " travaille au lieu : ";
                                echo "<span><button type='submit' class='boutonSansFond' name='versLieu' value='" . $donnees['3'] . "' style='font-weight: bold;color:" . $couleur1 . ";'>" . $nomLieuActuel . "</button></span>.";
                                if ($typePersonne != 'membre') {
                                    echo "<span> Il reste <span style='font-weight: bold;color:" . $couleur1 . "'>" . $nbHeureRestante . "h</span> à planifier pour " . $row1['prenom'] . " lors de cette édition.</span>";
                                }

                                echo "<br>Nombre d'heure(s) planifiée(s) ce jour-là pour ce " . $typePersonne . " : <span style='font-weight: bold;color:" . $couleur1 . "'>" . $nbHeureJour . "h</span>";
                                echo "<br>Nombre d'heure(s) effectuée(s) dans le lieu " . $nomLieuActuel . " dans cette édition : <span class='boutonSansFond' style='font-weight: bold;color:" . $couleur1 . ";'>" . $nbHeureLieuActuel . "h</span></span>";

                                echo "<br><br></div>";
                            } else {
                                /* si on n'a pas trouvé le jour 1 de l'édition */
                                $erreur = true;
                                $_SESSION['error'] = "Les informations n'ont pas pu être récupérés dans la base de données.";
                            }
                        } else {
                            /* si on n'a pas trouvé les infos de la personne */
                            $erreur = true;
                            $_SESSION['error'] = "Les informations n'ont pas pu être récupérés dans la base de données.";
                        }
                    } else {
                        /* si on n'a pas trouvé les infos de la personne */
                        $erreur = true;
                        $_SESSION['error'] = "Les informations n'ont pas pu être récupérés dans la base de données.";
                    }

                    /* tableau récapitulatif des autres lieux dispo à ce créneau */
                    echo "<div  style='display: block; overflow-x: auto; height: 72%;''>";
                    echo "<table style='width:100%;'>";


                    /* * * * * * * * * * * * * * * REQUETE NUMERO 4 * * * * * * * * * * * * * * */

                    $jourSuivant = explode("-", $date);

                    $date = implode("-", $jourSuivant);
                    if (intval($jourSuivant[2]) >= 30 && intval($jourSuivant[1]) == "02") {
                        $nbMois = floor(intval($jourSuivant[2] / 29));
                        $jourApres = intval($jourSuivant[2]) % 29;
                        if ($jourApres < 10) {
                            $jourSuivant[2] = "0" . $jourApres;
                        } else {
                            $jourSuivant[2] = $jourApres;
                        }
                        $mois = intval($jourSuivant[1]) + $nbMois;
                        if ($mois < 10) {
                            $jourSuivant[1] = "0" . $mois;
                        } else {
                            $jourSuivant[1] = $mois;
                        }
                        $date = implode("-", $jourSuivant);
                    } else if (intval($jourSuivant[2]) >= 31 && (intval($jourSuivant[1]) == "04" || intval($jourSuivant[1]) == "06" || intval($jourSuivant[1]) == "09" || intval($jourSuivant[1]) == "11")) {
                        $nbMois = floor(intval($jourSuivant[2] / 30));
                        $jourApres = intval($jourSuivant[2]) % 30;
                        if ($jourApres < 10) {
                            $jourSuivant[2] = "0" . $jourApres;
                        }
                        $mois = intval($jourSuivant[1]) + $nbMois;
                        if ($mois < 10) {
                            $jourSuivant[1] = "0" . $mois;
                        } else {
                            $jourSuivant[1] = $mois;
                        }
                        $date = implode("-", $jourSuivant);
                    } else if (intval($jourSuivant[2]) >= 32 && intval($jourSuivant[1]) == "12") {
                        $jourApres = intval($jourSuivant[2]) % 31;
                        if ($jourApres < 10) {
                            $jourSuivant[2] = "0" . $jourApres;
                        } else {
                            $jourSuivant[2] = $jourApres;
                        }
                        $jourSuivant[0] = intval($jourSuivant[0]) + 1;
                        $jourSuivant[1] = "01";
                        $date = implode("-", $jourSuivant);
                    } else if (intval($jourSuivant[2]) >= 32) {
                        $nbMois = floor(intval($jourSuivant[2] / 31));
                        $jourApres = intval($jourSuivant[2]) % 31;
                        if ($jourApres < 10) {
                            $jourSuivant[2] = "0" . $jourApres;
                        }
                        $mois = intval($jourSuivant[1]) + $nbMois;
                        if ($mois < 10) {
                            $jourSuivant[1] = "0" . $mois;
                        } else {
                            $jourSuivant[1] = $mois;
                        }
                        $date = implode("-", $jourSuivant);
                    }

                    /* sélectionner les infos des lieux dispos correspondant au créneau */
                    $requete4 = "SELECT * FROM lieu WHERE idLieu IN(SELECT idLieu FROM creneau WHERE idEdition='" . $_SESSION['edition'] . "' AND dateDebut='" . $date . "' AND heureDebut='" . $heure . "') AND idLieu!='" . $donnees['3'] . "'";
                    $result4 = mysqli_query($link, $requete4);
                    if (mysqli_num_rows($result4) > 0) {

                        /* création de 2 lignes d'entête */
                        echo "<tr style='background-color: " . $couleur2 . ";'><th colspan='4' style='align:center;'>CHANGEMENT DE LIEU POSSIBLE</th></tr>";
                        echo "<tr style='background-color: " . $couleur2 . ";'>";
                        echo "<th>Nom du Lieu</th>";
                        echo "<th>Besoin en " . $typePersonne . "</th>";
                        echo "<th>Nombre d'heure(s) effectuée(s) par " . $prenomPersonne . " dans le lieu</th>";
                        echo "<th></th>";
                        echo "</tr>";

                        while ($row4 = mysqli_fetch_assoc($result4)) {

                            /* pour chaque lieu dispo... */


                            /* * * * * * * * * * * * * * * REQUETE NUMERO 5 * * * * * * * * * * * * * * */

                            /* sélectionner les infos du créneau correspondant à ce lieu */
                            $requete5 = "SELECT * FROM creneau WHERE dateDebut='" . $date . "' AND heureDebut='" . $heure . "' AND idLieu='" . $row4['idLieu'] . "' AND idEdition='" . $_SESSION['edition'] . "'";
                            $result5 = mysqli_query($link, $requete5);
                            if (mysqli_num_rows($result5) == 1) {
                                $row5 = mysqli_fetch_assoc($result5);


                                /* * * * * * * * * * * * * * * REQUETE NUMERO 6 * * * * * * * * * * * * * * */

                                /* sélectionner les infos de la personne correspondant à la ligne */
                                $requete6 = "SELECT * FROM personne WHERE idPersonne='" . $donnees['2'] . "'";
                                $result6 = mysqli_query($link, $requete6);
                                if (mysqli_num_rows($result6) == 1) {
                                    $row6 = mysqli_fetch_assoc($result6);
                                    $nbBenevoleManquant = $row5['nbBenevoleManquant'];
                                    $nbMembreManquant = $row5['nbMembreManquant'];


                                    /* * * * * * * * * * * * * * * REQUETE NUMERO 7 * * * * * * * * * * * * * * */

                                    /* sélectionner les id des personnes qui travaillent à ce moment à ce lieu */
                                    $requete7 = "SELECT * FROM travailler WHERE idCreneau='" . $row5['idCreneau'] . "' AND idPersonne!='" . $donnees['2'] . "'";
                                    $result7 = mysqli_query($link, $requete7);
                                    if (mysqli_num_rows($result7) > 0) {
                                        while ($row7 = mysqli_fetch_assoc($result7)) {


                                            /* * * * * * * * * * * * * * * REQUETE NUMERO 8 * * * * * * * * * * * * * * */

                                            /* sélectionner les infos de la personne */
                                            $requete8 = "SELECT * FROM personne WHERE idPersonne='" . $row7['idPersonne'] . "'";
                                            $result8 = mysqli_query($link, $requete8);
                                            if (mysqli_num_rows($result8) == 1) {
                                                $row8 = mysqli_fetch_assoc($result8);

                                                /* décrémentation des nombres de personnes manquantes */
                                                if ($row8['typePersonne'] == "Benevole") {
                                                    $nbBenevoleManquant--;
                                                } else if ($row8['typePersonne'] == "Membre") {
                                                    $nbMembreManquant--;
                                                }
                                            }
                                        }
                                    }

                                    /* si la personne est un bénévole */
                                    if ($row6['typePersonne'] == "Benevole") {

                                        /* s'il ne manque qu'un bénévole */
                                        if ($nbBenevoleManquant == 0) {
                                            echo "<tr>";
                                            echo "<th style='background-color: " . $couleur2 . "'><button type='submit' name='versPlanningLieu' value='" . $row4['idLieu'] . "' class='souligneHover' style='border:none; background:none;cursor:pointer;'>" . $row4['nom'] . "</button></th>";
                                            echo "<td>" . $nbBenevoleManquant . " bénévole</td>";

                                            /* variable pour le nombre d'heure de la personne passée dans ce lieu */
                                            $nbHeureLieu = 0;


                                            /* * * * * * * * * * * * * * * REQUETE NUMERO 9 * * * * * * * * * * * * * * */

                                            /* sélectionner les infos du créneau correspondant à ce lie et cette personne */
                                            $requete9 = "SELECT * FROM creneau WHERE idLieu='" . $row4['idLieu'] . "' AND idCreneau IN(SELECT idCreneau FROM travailler WHERE idPersonne='" . $donnees['2'] . "')";
                                            $result9 = mysqli_query($link, $requete9);
                                            if (mysqli_num_rows($result9) > 0) {
                                                while ($row9 = mysqli_fetch_assoc($result9)) {
                                                    /* on incrémente le compteur */
                                                    $nbHeureLieu++;
                                                }
                                            }
                                            echo "<td>" . $nbHeureLieu . "h</td>";

                                            /* bouton pour sélectionner le lieu et donc assigner la personne à un créneau */
                                            echo "<td><button type='submit' name='changerLieu' value='" . $date . "_" . $heure . "_" . $donnees['2'] . "_" . $row4['idLieu'] . "_" . $donnees['3'] . "' class='boutonSansFond' style='color:" . $couleur1 . "'>Sélectionner ce lieu</button>";
                                            echo "</tr>";
                                        }

                                        /* s'il y a plus d'un bénévole manquant */ else if ($nbBenevoleManquant > 0) {
                                            echo "<tr>";
                                            echo "<th style='background-color: " . $couleur2 . "'><button type='submit' name='versPlanningLieu' value='" . $row4['idLieu'] . "' class='boutonSansFond'>" . $row4['nom'] . "</button></th>";
                                            echo "<td>" . $nbBenevoleManquant . " bénévoles</td>";

                                            /* variable pour le nombre d'heure de la personne passée dans ce lieu */
                                            $nbHeureLieu = 0;


                                            /* * * * * * * * * * * * * * * REQUETE NUMERO 9 * * * * * * * * * * * * * * */

                                            /* sélectionner les infos du créneau correspondant à ce lie et cette personne */
                                            $requete9 = "SELECT * FROM creneau WHERE idLieu='" . $row4['idLieu'] . "' AND idCreneau IN(SELECT idCreneau FROM travailler WHERE idPersonne='" . $donnees['2'] . "')";
                                            $result9 = mysqli_query($link, $requete9);
                                            if (mysqli_num_rows($result9) > 0) {
                                                while ($row9 = mysqli_fetch_assoc($result9)) {
                                                    /* on incrémente */
                                                    $nbHeureLieu++;
                                                }
                                            }
                                            echo "<td>" . $nbHeureLieu . "h</td>";

                                            /* bouton pour sélectionner le lieu et donc assigner la personne à un créneau */
                                            echo "<td><button type='submit' name='changerLieu' value='" . $date . "_" . $heure . "_" . $donnees['2'] . "_" . $row4['idLieu'] . "_" . $donnees['3'] . "' class='boutonSansFond' style='color:" . $couleur1 . "'>Sélectionner ce lieu</button>";
                                            echo "</tr>";
                                        }

                                        /* s'il ne manque pas de bénévoles */ else {
                                            echo "<tr>";
                                            echo "<th style='background-color: " . $couleur2 . "'><button type='submit' name='versPlanningLieu' value='" . $row4['idLieu'] . "' class='boutonSansFond'>" . $row4['nom'] . "</button></th>";
                                            echo "<td>" . $nbBenevoleManquant . " bénévoles</td>";

                                            /* variable pour le nombre d'heure de la personne passée dans ce lieu */
                                            $nbHeureLieu = 0;


                                            /* * * * * * * * * * * * * * * REQUETE NUMERO 9 * * * * * * * * * * * * * * */

                                            /* sélectionner les infos du créneau correspondant à ce lie et cette personne */
                                            $requete9 = "SELECT * FROM creneau WHERE idLieu='" . $row4['idLieu'] . "' AND idCreneau IN(SELECT idCreneau FROM travailler WHERE idPersonne='" . $donnees['2'] . "')";
                                            $result9 = mysqli_query($link, $requete9);
                                            if (mysqli_num_rows($result9) > 0) {
                                                while ($row9 = mysqli_fetch_assoc($result9)) {
                                                    /* on incrémente */
                                                    $nbHeureLieu++;
                                                }
                                            }
                                            echo "<td>" . $nbHeureLieu . "h</td>";

                                            /* bouton pour sélectionner le lieu et donc assigner la personne à un créneau */
                                            echo "<td><button type='submit' name='selectionnerLieu' value='" . $date . "_" . $heure . "_" . $donnees['2'] . "_" . $row4['idLieu'] . "_" . $donnees['3'] . "' style='color:" . $couleur1 . "' class='boutonSansFond'>Sélectionner ce lieu</button>";
                                            echo "</tr>";
                                        }
                                    }

                                    /* si la personne est un membre */ else if ($row6['typePersonne'] == "Membre") {

                                        /* s'il ne manque qu'un seul membre */
                                        if ($nbMembreManquant == 1) {
                                            echo "<tr>";
                                            echo "<th style='background-color: " . $couleur2 . "'><button type='submit' name='versPlanningLieu' value='" . $row4['idLieu'] . "' class='boutonSansFond'>" . $row4['nom'] . "</button></th>";
                                            echo "<td>" . $nbMembreManquant . " membre</td>";

                                            /* variable pour le nombre d'heure de la personne passée dans ce lieu */
                                            $nbHeureLieu = 0;


                                            /* * * * * * * * * * * * * * * REQUETE NUMERO 9 * * * * * * * * * * * * * * */

                                            /* sélectionner les infos du créneau correspondant à ce lie et cette personne */
                                            $requete9 = "SELECT * FROM creneau WHERE idLieu='" . $row4['idLieu'] . "' AND idCreneau IN(SELECT idCreneau FROM travailler WHERE idPersonne='" . $donnees['2'] . "')";
                                            $result9 = mysqli_query($link, $requete9);
                                            if (mysqli_num_rows($result9) > 0) {
                                                while ($row9 = mysqli_fetch_assoc($result9)) {
                                                    /* on incrémente */
                                                    $nbHeureLieu++;
                                                }
                                            }
                                            echo "<td>" . $nbHeureLieu . "h</td>";

                                            /* bouton pour sélectionner le lieu et donc assigner la personne à un créneau */
                                            echo "<td><button type='submit' name='changerLieu' value='" . $date . "_" . $heure . "_" . $donnees['2'] . "_" . $row4['idLieu'] . "_" . $donnees['3'] . "' class='boutonSansFond' style='color:" . $couleur1 . "'>Sélectionner ce lieu</button>";
                                            echo "</tr>";
                                        }

                                        /* s'il manque plus qu'un membre */ else if ($nbMembreManquant > 0) {
                                            echo "<tr>";
                                            echo "<th style='background-color: " . $couleur2 . "'><button type='submit' name='versPlanningLieu' value='" . $row4['idLieu'] . "' class='boutonSansFond'>" . $row4['nom'] . "</button></th>";
                                            echo "<td>" . $nbMembreManquant . " membres</td>";

                                            /* variable pour le nombre d'heure de la personne passée dans ce lieu */
                                            $nbHeureLieu = 0;

                                            /* * * * * * * * * * * * * * * REQUETE NUMERO 9 * * * * * * * * * * * * * * */

                                            /* sélectionner les infos du créneau correspondant à ce lie et cette personne */
                                            $requete9 = "SELECT * FROM creneau WHERE idLieu='" . $row4['idLieu'] . "' AND idCreneau IN(SELECT idCreneau FROM travailler WHERE idPersonne='" . $donnees['2'] . "')";
                                            $result9 = mysqli_query($link, $requete9);
                                            if (mysqli_num_rows($result9) > 0) {
                                                while ($row9 = mysqli_fetch_assoc($result9)) {
                                                    /* on incrémente */
                                                    $nbHeureLieu++;
                                                }
                                            }
                                            echo "<td>" . $nbHeureLieu . "h</td>";

                                            /* bouton pour sélectionner le lieu et donc assigner la personne à un créneau */
                                            echo "<td><button type='submit' name='changerLieu' value='" . $date . "_" . $heure . "_" . $donnees['2'] . "_" . $row4['idLieu'] . "_" . $donnees['3'] . "' class='boutonSansFond' style='color:" . $couleur1 . "'>Sélectionner ce lieu</button>";
                                            echo "</tr>";
                                        }

                                        /* s'il ne manque pas de membres */ else {
                                            echo "<tr>";
                                            echo "<th style='background-color: " . $couleur2 . "'><button type='submit' name='versPlanningLieu' value='" . $row4['idLieu'] . "' class='boutonSansFond'>" . $row4['nom'] . "</button></th>";
                                            echo "<td>" . $nbMembreManquant . " membres</td>";

                                            /* variable pour le nombre d'heure de la personne passée dans ce lieu */
                                            $nbHeureLieu = 0;

                                            /* * * * * * * * * * * * * * * REQUETE NUMERO 9 * * * * * * * * * * * * * * */

                                            /* sélectionner les infos du créneau correspondant à ce lie et cette personne */
                                            $requete9 = "SELECT * FROM creneau WHERE idLieu='" . $row4['idLieu'] . "' AND idCreneau IN(SELECT idCreneau FROM travailler WHERE idPersonne='" . $donnees['2'] . "')";
                                            $result9 = mysqli_query($link, $requete9);
                                            if (mysqli_num_rows($result9) > 0) {
                                                while ($row9 = mysqli_fetch_assoc($result9)) {
                                                    /* on incrémente */
                                                    $nbHeureLieu++;
                                                }
                                            }
                                            echo "<td>" . $nbHeureLieu . "h</td>";

                                            /* bouton pour sélectionner le lieu et donc assigner la personne à un créneau */
                                            echo "<td><button type='submit' name='selectionnerLieu' value='" . $date . "_" . $heure . "_" . $donnees['2'] . "_" . $row4['idLieu'] . "_" . $donnees['3'] . "' style='color:" . $couleur1 . "' class='boutonSansFond'>Sélectionner ce lieu</button>";
                                            echo "</tr>";
                                        }
                                    }
                                } else {
                                    /* cas d'erreur si nous ne trouvons pas la personne */
                                    $erreur = true;
                                    $_SESSION['error'] = "Les informations n'ont pas pu être récupérés dans la base de données.";
                                }
                            } else {
                                /* cas d'erreur si nous ne trouvons pas le créneau */
                                $erreur = true;
                                $_SESSION['error'] = "Les informations n'ont pas pu être récupérés dans la base de données.";
                            }
                        }

                        echo "<tr>";
                        /* bouton pour mettre en pause la personne simplifié */
                        echo "<td colspan='4'><button type='submit' name='mettrePause' style='color:" . $couleur1 . ";' class='boutonSansFond' value='" . $date . "_" . $heure . "_" . $donnees['2'] . "_" . $donnees['3'] . "'>Mettre en pause</button></td>";
                        echo "</tr>";
                    } else {
                        echo "<td colspan='4'><button type='submit' name='mettrePause' style='color:" . $couleur1 . ";' class='boutonSansFond' value='" . $date . "_" . $heure . "_" . $donnees['2'] . "_" . $donnees['3'] . "'>Mettre en pause</button></td>";
                    }
                }
                ?>
                </tbody>
                </table>
            </div>
        </div>
        </div>
    </form>
<?php } else { ?>
    <div class="arrierePlanRectangle">
        <h1 class="titrePrincipal"><?php echo $_SESSION['edition'] ?></h1>

        <!-- Curseur -->
        <div class="curseurBox">
            <p style="margin-bottom: 0%; text-align: center;"><span id="demo"></span>%</p>
            <button id="zoom0" class="boutonSansFond" style="color: <?php echo $couleur4 ?>;"><i class="fa fa-minus curseurIcone"></i></button>
            <input class="curseur" type="range" min="0.17" max="2" step="0.01" value="0.8" id="myRange" style="background: <?php echo $couleur4 ?>;">
            <button id="zoom1" class="boutonSansFond" style="color: <?php echo $couleur4 ?>;"> <i class="fa fa-plus curseurIcone"></i></button>
        </div>

        <!-- Zoom bloqué à 100 pour sticky la 1e colonne et les 2 1e lignes -->
        <div class="checkBox">
            <input type="checkbox" id="case" onclick="exemple()" value="Case">
            <label>100%</label>
        </div>

        <form action="actionPlanningGeneral.php" method="post" style="height:100%;">

            <div class="rectanglePlanning">
                <div id="general" class="tabcontent" style="display: block; overflow-x: auto;overflow-y: auto; height: 100%;">
                    <?php include('tabGeneral.php'); ?>
                </div>
            </div>
        </form>
        <!-- bouton pour télécharger en PDF -->
        <form action="actionPlanningGeneral.php" method="post" target="_blank">
            <button type="submit" name="exportPDFGeneral" class="boutonSansFond ecritureOmbreRouge pdfGeneral" style="color: <?php echo $couleur4 ?>;">Télécharger en PDF</button>
        </form>

        <!-- redirection vers le planning simplifié -->
        <a class="next aSansFond" href='planningSimplifie.php' style='color: <?php echo $couleur4 ?>;'>❯</a>
    </div>
<?php }

include("../include/footer.php"); ?>