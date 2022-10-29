<?php include('../include/header.php');

if (isset($_POST['close'])) {
    header('location:planningGeneral.php');
    die();
}
if (isset($_POST['personne'])) {
    $_SESSION['personne'] = $_POST['personne'];
    header('location:planningPersonne.php');
    die();
}
if (isset($_POST['caseVide'])) {
    $_SESSION['caseVide'] = $_POST['caseVide'];
    header('location:planningGeneral.php');
    die();
}
if (isset($_POST['caseRemplie'])) {
    $_SESSION['caseRemplie'] = $_POST['caseRemplie'];
    header('location:planningGeneral.php');
    die();
}
if (isset($_POST['versPersonne'])) {
    $_SESSION['personne'] = $_POST['versPersonne'];
    header('location:planningPersonne.php');
    die();
}
if (isset($_POST['lieu'])) {
    $_SESSION['versLieu'] = $_POST['lieu'];
    header('location:planningLieu.php');
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
if (isset($_POST['ajoutCreneauLieu'])) {
    $_SESSION['lieuAModifier'] = $_POST['ajoutCreneauLieu'];
    header('location:../edition/modifierLieu.php');
    die();
}

if (isset($_POST['exportPDFGeneral'])) {
    header('location:../gerer_mail_fichierPDF_fichierCSV/exportPDF2.php');
    die();
}

if (isset($_POST['versPlanningLieu'])) {
    $_SESSION['versLieu'] = $_POST['versPlanningLieu'];
    header('location:planningLieu.php');
    die();
}
if (isset($_SESSION['planningGeneral'])) {
    $tableauGeneral = unserialize($_SESSION['planningGeneral']);
    if (isset($_POST['mettrePause'])) {
        $donnees = explode("_", $_POST['mettrePause']);
        $requete1 = "SELECT * FROM creneau WHERE dateDebut='" . $donnees['0'] . "' AND heureDebut='" . $donnees['1'] . "' AND idLieu='" . $donnees['3'] . "' AND idEdition='" . $_SESSION['edition'] . "'";
        $result1 = mysqli_query($link, $requete1);
        if (mysqli_num_rows($result1) == 1) {
            $row1 = mysqli_fetch_assoc($result1);
            $requete2 = "DELETE FROM travailler WHERE idCreneau='" . $row1['idCreneau'] . "' AND idPersonne='" . $donnees['2'] . "'";
            $result2 = mysqli_query($link, $requete2);
            if ($result2 == true) {
                $requete3 = "SELECT * FROM personne WHERE idPersonne='" . $donnees['2'] . "'";
                $result3 = mysqli_query($link, $requete3);
                if (mysqli_num_rows($result3) == 1) {
                    $row3 = mysqli_fetch_assoc($result3);
                    $date = explode("-", $donnees['0']);
                    $heure = explode(":", $donnees['1']);
                    $monthName = date("m", strtotime($donnees['0']));
                    $dayofweek = date('w', strtotime($donnees['0']));
                    $jour;
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
                    $jour = $jourDeLaSemaine . " " . $date['2'] . " " . $mois . " " . $date['0'];
                    $heure = intval($heure['0']) . "h";
                    $tableauGeneral->planningGeneral[$row3['nom'] . "_" . $row3['prenom'] . "_" . $row3['idPersonne'] . "_" . $row3['typePersonne']][$jour][$heure] = "Pause_" . $jour . "_" . $heure . "_" . $donnees['2'];
                    $_SESSION['planningGeneral'] = serialize($tableauGeneral);
                    $_SESSION['success'] = "La personne " . $row3['nom'] . " " . $row3['prenom'] . " est en pause le " . $donnees['0'] . " à " . $donnees['1'] . ".";
                } else {
                    $_SESSION['error'] = "Les informations n'ont pas pu être inséré dans la base de données.";
                }
            } else {
                $_SESSION['error'] = "Les informations n'ont pas pu être inséré dans la base de données.";
            }
        } else {
            $_SESSION['error'] = "Les informations n'ont pas pu être inséré dans la base de données.";
        }
        header('location:planningGeneral.php');
        die();
    } else if (isset($_POST['selectionnerLieu'])) {
        $donnees = explode("_", $_POST['selectionnerLieu']);
        echo $_POST['selectionnerLieu'];
        $requete1 = "SELECT idCreneau FROM creneau WHERE dateDebut='" . $donnees['0'] . "' AND heureDebut='" . $donnees['1'] . "' AND idLieu='" . $donnees['3'] . "' AND idEdition='" . $_SESSION['edition'] . "'";
        $result1 = mysqli_query($link, $requete1);
        if (mysqli_num_rows($result1) == 1) {
            $row1 = mysqli_fetch_assoc($result1);
            $requete2 = "INSERT INTO travailler (idCreneau,idPersonne) VALUES (" . $row1['idCreneau'] . ", " . $donnees['2'] . ")";
            $result2 = mysqli_query($link, $requete2);
            if ($result2 == true) {
                $requete3 = "SELECT nom,prenom,idPersonne,typePersonne FROM personne WHERE idPersonne='" . $donnees['2'] . "'";
                $result3 = mysqli_query($link, $requete3);
                if (mysqli_num_rows($result3) == 1) {
                    $row3 = mysqli_fetch_assoc($result3);
                    $requete9 = "SELECT * FROM participer WHERE idPersonne='" . $donnees['2'] . "' AND idEdition='" . $_SESSION['edition'] . "'";
                    $result9 = mysqli_query($link, $requete9);
                    if (mysqli_num_rows($result9) == 1) {
                        $row9 = mysqli_fetch_assoc($result9);
                        $requete4 = "UPDATE participer SET nbHeurePlanifiee='" . (intval($row9['nbHeurePlanifiee']) + 1) . "' WHERE idPersonne='" . $row3['idPersonne'] . "' AND idEdition='" . $_SESSION['edition'] . "'";
                        $result4 = mysqli_query($link, $requete4);
                        if ($result4 == true) {
                            $requete5 = "SELECT nom,idCouleur,idLieu FROM lieu WHERE idLieu='" . $donnees['3'] . "'";
                            $result5 = mysqli_query($link, $requete5);
                            if (mysqli_num_rows($result5) == 1) {
                                $row5 = mysqli_fetch_assoc($result5);
                                $date = explode("-", $donnees['0']);
                                $heure = explode(":", $donnees['1']);
                                $monthName = date("m", strtotime($donnees['0']));
                                $dayofweek = date('w', strtotime($donnees['0']));
                                $jour;
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
                                $jour = $jourDeLaSemaine . " " . $date['2'] . " " . $mois . " " . $date['0'];
                                $heure = intval($heure['0']) . "h";
                                $tableauGeneral->planningGeneral[$row3['nom'] . "_" . $row3['prenom'] . "_" . $row3['idPersonne'] . "_" . $row3['typePersonne']][$jour][$heure] = $row5['nom'] . "_" . $row5['idCouleur'] . "_" . $jour . "_" . $heure . "_" . $row3['idPersonne'] . "_" . $row5['idLieu'];
                                $_SESSION['planningGeneral'] = serialize($tableauGeneral);
                                $_SESSION['success'] = "La case a bien été modifié : " . $row3['nom'] . " " . $row3['prenom'] . " travaille le " . $date['2'] . "/" . $date['1'] . "/" . $date['0'] . " à " . intval($heure['0']) . "h à " . $row5['nom'];
                            } else {
                                $_SESSION['error'] = "Les informations n'ont pas pu être entrées dans la base de données.";
                            }
                        } else {
                            $_SESSION['error'] = "Les informations n'ont pas pu être entrées dans la base de données.";
                        }
                    } else {
                        $_SESSION['error'] = "Les informations n'ont pas pu être entrées dans la base de données.";
                    }
                } else {
                    $_SESSION['error'] = "Les informations n'ont pas pu être entrées dans la base de données.";
                }
            } else {
                $_SESSION['error'] = "Les informations n'ont pas pu être entrées dans la base de données.";
            }
        } else {
            $_SESSION['error'] = "Nous n'avons pas pu récupérer les informations dans la base de données.";
        }
        header('location:planningGeneral.php');
        die();
    } else if (isset($_POST['changerLieu'])) {
        $donnees = explode("_", $_POST['changerLieu']);
        echo $donnees['1'] . "_" . $donnees['2'] . "_" . $donnees['3'] . "_" . $donnees['4'];
        $requete1 = "SELECT * FROM creneau WHERE dateDebut='" . $donnees['0'] . "' AND heureDebut='" . $donnees['1'] . "' AND idLieu='" . $donnees['3'] . "' AND idEdition='" . $_SESSION['edition'] . "'";
        $result1 = mysqli_query($link, $requete1);
        if (mysqli_num_rows($result1) == 1) {
            $row1 = mysqli_fetch_assoc($result1);
            $requete2 = "SELECT * FROM creneau WHERE dateDebut='" . $donnees['0'] . "' AND heureDebut='" . $donnees['1'] . "' AND idLieu='" . $donnees['4'] . "' AND idEdition='" . $_SESSION['edition'] . "'";
            $result2 = mysqli_query($link, $requete2);
            if (mysqli_num_rows($result2) == 1) {
                $row2 = mysqli_fetch_assoc($result2);
                $requete3 = "DELETE FROM travailler WHERE idPersonne='" . $donnees['2'] . "' AND idCreneau='" . $row2['idCreneau'] . "'";
                $result3 = mysqli_query($link, $requete3);
                if ($result3 == true) {
                    $requete4 = "INSERT INTO travailler (idCreneau,idPersonne) VALUES (" . $row1['idCreneau'] . ", " . $donnees['2'] . ")";
                    $result4 = mysqli_query($link, $requete4);
                    if ($result4 == true) {
                        $requete5 = "SELECT nom,idCouleur,idLieu FROM lieu WHERE idLieu='" . $donnees['3'] . "'";
                        $result5 = mysqli_query($link, $requete5);
                        if (mysqli_num_rows($result5) == 1) {
                            $row5 = mysqli_fetch_assoc($result5);
                            $requete6 = "SELECT nom,prenom,idPersonne,typePersonne FROM personne WHERE idPersonne='" . $donnees['2'] . "'";
                            $result6 = mysqli_query($link, $requete6);
                            if (mysqli_num_rows($result6) == 1) {
                                $row6 = mysqli_fetch_assoc($result6);
                                $date = explode("-", $donnees['0']);
                                $heure = explode(":", $donnees['1']);
                                $monthName = date("m", strtotime($donnees['0']));
                                $dayofweek = date('w', strtotime($donnees['0']));
                                $jour;
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
                                $jour = $jourDeLaSemaine . " " . $date['2'] . " " . $mois . " " . $date['0'];
                                $heure = intval($heure['0']) . "h";
                                $tableauGeneral->planningGeneral[$row6['nom'] . "_" . $row6['prenom'] . "_" . $row6['idPersonne'] . "_" . $row6['typePersonne']][$jour][$heure] = $row5['nom'] . "_" . $row5['idCouleur'] . "_" . $jour . "_" . $heure . "_" . $row6['idPersonne'] . "_" . $row5['idLieu'];
                                $_SESSION['planningGeneral'] = serialize($tableauGeneral);
                                $_SESSION['success'] = "La case a bien été modifié : " . $row6['nom'] . " " . $row6['prenom'] . " travaille le " . $date['2'] . "/" . $date['1'] . "/" . $date['0'] . " à " . intval($heure['0']) . "h à " . $row5['nom'];
                            } else {
                                $_SESSION['error'] = "Les informations n'ont pas pu être entrées dans la base de données.";
                            }
                        } else {
                            $_SESSION['error'] = "Les informations n'ont pas pu être entrées dans la base de données.";
                        }
                    } else {
                        $_SESSION['error'] = "Les informations n'ont pas pu être entrées dans la base de données.";
                    }
                } else {
                    $_SESSION['error'] = "Les informations n'ont pas pu être entré dans la base de données.";
                }
            } else {
                $_SESSION['error'] = "Nous n'avons pas pu récupérer les informations dans la base de données.";
            }
        } else {
            $_SESSION['error'] = "Nous n'avons pas pu récupérer les informations dans la base de données.";
        }
        header('location:planningGeneral.php');
        die();
    }
} else {
    $_SESSION['error'] = "Un problème est survenu.";
    header('location:../edition/menuEdition.php');
    die();
}
