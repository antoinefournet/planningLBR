<!--                                * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *             Fichier PHP             *
                                    *      Form pour ajouter un lieu      *
                                    *           à une édition             *
                                    *                                     *
                                    * * * * * * * * * * * * * * * * * * * *    
-->

<!-- insertion du header avec le logo en haut à gauche -->
<?php include('../include/header.php');

/***** AJOUT D'UN CRENEAU A UN LIEU *****/

if (isset($_POST['ajoutLigne'])) {
    /* mise à false du booléen erreur pour détecter s'il y a ou non une erreur */
    $erreur = false;

    /* si les variables du form existent bien : enregistrement */
    if (isset($_POST['nomLieu']) && isset($_POST['heureDebut']) && isset($_POST['heureFin']) && isset($_POST['nombreBenevoles']) && isset($_POST['nombreMembres']) && isset($_POST['choixCouleur'])) {
        $nomLieu = $_POST['nomLieu'];
        $jour = $_POST['jour'];
        $heureDebut = $_POST['heureDebut'];
        $heureFin = $_POST['heureFin'];
        $nombreBenevoles = $_POST['nombreBenevoles'];
        $nombreMembres = $_POST['nombreMembres'];
        $choixCouleur = $_POST['choixCouleur'];

        if ($jour == "" || $heureDebut == "" || $heureFin == "" || $nombreBenevoles == "" || $nombreMembres == "") {
            /* erreur si au moins 1 des champs est vide */
            $erreur = true;
            $_SESSION['error'] = "Tous les champs doivent être remplis";
        } else {
            if (isset($_SESSION['idLieuCree'])) {
                /* sauvegarde de l'id du lieu crée */
                $idLieuCree = $_SESSION['idLieuCree'];
            } else {
                /* erreur si l'utilisateur essaye d'ajouter un créneau à un lieu non créé */
                $erreur = true;
                $_SESSION['error'] = "Vous devez créer un lieu avant de lui ajouter des créneaux.";
            }
        }
    } else {
        /* si tous les champs ne sont pas remplis : redirection avec le form */
        $erreur = true;
        $_SESSION['error'] = "Tous les champs doivent être remplis";
        header('location:ajouterLieu.php');
        die();
    }

    if ($erreur == false) {
        /* si aucune erreur n'a été détectée : enregistrement */
        $heure1 = explode(":", $heureDebut);
        $heure2 = explode(":", $heureFin);
        if (intval($heure1[0]) > intval($heure2[0])) {
            $heure = intval($heure2[0]) + 24;
            if ($heure < 10) {
                $heure2[0] = "0" . $heure;
            } else {
                $heure2[0] = $heure;
            }
        }
        $duree = intval($heure2[0]) - intval($heure1[0]);
        for ($i = 0; $i < $duree; $i++) /* on entre les créneaux dans la base de données heure par heure */ {
            $heure = explode(":", $heureDebut);
            $heure[0] = intval($heure[0]) + $i;
            $heureActuelle = implode(":", $heure);

            if (intval($heure[0]) >= 24) {
                $nombreJour = floor(intval($heure[0]) / 24);
                $heure[0] = intval($heure[0]) % 24;
                if ($heure[0] < 10) {
                    $heure[0] = "0" . $heure[0];
                }
                $heureActuelle = implode(":", $heure);
                $jourSuivant = explode("-", $jour);
                echo $heureActuelle;
                if ($heureActuelle == "00:00") {
                    $jourApres = intval($jourSuivant[2]) + $nombreJour;
                    if ($jourApres < 10) {
                        $jourSuivant[2] = "0" . $jourApres;
                    } else {
                        $jourSuivant[2] = $jourApres;
                    }
                }
                $jour = implode("-", $jourSuivant);
                if (intval($jourSuivant[2]) >= 30 && intval($jourSuivant[1]) == "02") {
                    $nbMois = floor(intval($jourSuivant[2] / 29));
                    $jourApres = intval($jourSuivant[2]) % 29;
                    if ($jourApres < 10) {
                        $jourSuivant[2] = "0" . $jourApres;
                    }
                    $mois = intval($jourSuivant[1]) + $nbMois;
                    if ($mois < 10) {
                        $jourSuivant[1] = "0" . $mois;
                    } else {
                        $jourSuivant[1] = $mois;
                    }
                    $jour = implode("-", $jourSuivant);
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
                    $jour = implode("-", $jourSuivant);
                } else if (intval($jourSuivant[2]) >= 32 && intval($jourSuivant[1]) == "12") {
                    $jourApres = intval($jourSuivant[2]) % 31;
                    if ($jourApres < 10) {
                        $jourSuivant[2] = "0" . $jourApres;
                    }
                    $jourSuivant[0] = intval($jourSuivant[0]) + 1;
                    $jourSuivant[1] = "01";
                    $jour = implode("-", $jourSuivant);
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
                    $jour = implode("-", $jourSuivant);
                }
            }
            if ($erreur == false) {
                $requete2 = "SELECT * FROM creneau WHERE dateDebut='" . $jour . "' AND heureDebut='" . $heureActuelle . "' AND idLieu='" . $idLieuCree . "' AND idEdition='" . $_SESSION['edition'] . "'";
                $result2 = mysqli_query($link, $requete2);
                if (mysqli_num_rows($result2) == 1) {
                    $_SESSION['warning'] = "Une partie du créneau a déjà été entré dans la base de données et n'a pas été modifié.";
                } else {
                    /* requête pour ajouter le créneau à la BDD */
                    $requete3 = "INSERT INTO creneau (dateDebut,heureDebut,nbBenevoleManquant,nbMembreManquant,idLieu,idEdition) VALUES ('" . $jour . "','" . $heureActuelle . "'," . $nombreBenevoles . "," . $nombreMembres . ",'" . $idLieuCree . "','" . $_SESSION['edition'] . "')";
                    $result3 = mysqli_query($link, $requete3);
                    $idCreneau = mysqli_insert_id($link);
                    if ($result3 == true) {
                        /* si la requête a fonctionné : ajout du créneau des bénévoles */
                        $requete4 = "INSERT INTO assigner VALUES('Benevole'," . $idCreneau . "," . $nombreBenevoles . ")";
                        $result4 = mysqli_query($link, $requete4);
                        if ($result4 == true) {
                            /* si la requête a fonctionné : ajout du créneau des membres */
                            $requete5 = "INSERT INTO assigner VALUES('Membre'," . $idCreneau . "," . $nombreMembres . ")";
                            $result5 = mysqli_query($link, $requete5);
                            if ($result5 == true) {
                                $_SESSION['success'] = "Le créneau a bien été ajouté.";
                            } else {
                                /* si erreur de la requête de l'ajout dans la BDD */
                                $erreur = true;
                                $_SESSION['error'] = "Les informations n'ont pas pu être insérer dans la base de données 1.";
                            }
                        } else {
                            /* si erreur de la requête de l'ajout dans la BDD */
                            $erreur = true;
                            $_SESSION['error'] = "Les informations n'ont pas pu être insérer dans la base de données 2.";
                        }
                    } else {
                        /* si erreur de la requête de l'ajout dans la BDD */
                        $erreur = true;
                        $_SESSION['error'] = "Les informations n'ont pas pu être insérer dans la base de données.";
                    }
                }
            }
        }
    } else {
        /* si au moins une erreur détectée */
        $erreur = true;
        $_SESSION['error'] = "Les informations n'ont pas pu être insérer dans la base de données.";
    }

    if ($erreur == true) {
        /* si au moins une erreur détectée : enregistrement + redirection au form */
        $_SESSION['nomLieu'] = $nomLieu;
        $_SESSION['jour'] = $jour;
        $_SESSION['heureDebut'] = $heureDebut;
        $_SESSION['heureFin'] = $heureFin;
        $_SESSION['nombreBenevoles'] = $nombreBenevoles;
        $_SESSION['nombreMembres'] = $nombreMembres;
        $_SESSION['choixCouleur'] = $choixCouleur;
        header('location:ajouterLieu.php');
        die();
    } else {
        /* sinon : enregistrement + redirection au form */
        $_SESSION['nomLieu'] = $nomLieu;
        $_SESSION['choixCouleur'] = $choixCouleur;
        header('location:ajouterLieu.php');
        die();
    }
}

/***** MODIFICATION D'UN CRENEAU *****/

else if (isset($_POST['modifCreneau'])) {
    $_SESSION['modifCreneau'] = $_POST['modifCreneau'];
    header('location:ajouterLieu.php');
    die();
} else if (isset($_POST['validModifsCreneau'])) {
    $creneauAModifier = explode('_', $_POST['validModifsCreneau']);
    $erreur = false;

    if (isset($_POST['nomLieu']) && isset($_POST['modifHeureDebut']) && isset($_POST['modifHeureFin']) && isset($_POST['modifNbBenevole']) && isset($_POST['modifNbMembre']) && isset($_POST['choixCouleur'])) {
        $nomLieu = $_POST['nomLieu'];
        $jour = $_POST['modifJour'];
        $heureDebut = $_POST['modifHeureDebut'];
        $heureFin = $_POST['modifHeureFin'];
        $nombreBenevoles = $_POST['modifNbBenevole'];
        $nombreMembres = $_POST['modifNbMembre'];
        $choixCouleur = $_POST['choixCouleur'];

        if ($jour == "" || $heureDebut == "" || $heureFin == "" || $nombreBenevoles == "" || $nombreMembres == "") {
            /* erreur si au moins 1 des champs est vide */
            $erreur = true;
            $_SESSION['error'] = "Tous les champs doivent être remplis";
        } else {

            if (isset($_SESSION['idLieuCree'])) {
                /* sauvegarde de l'id du lieu crée */
                $idLieuCree = $_SESSION['idLieuCree'];
            } else {
                /* erreur si l'utilisateur essaye d'ajouter un créneau à un lieu non créé */
                $erreur = true;
                $_SESSION['error'] = "Vous devez créer un lieu avant de lui ajouter des créneaux.";
            }
        }
    } else {
        /* si tous les champs ne sont pas remplis : redirection avec le form */
        $erreur = true;
        $_SESSION['error'] = "Tous les champs doivent être remplis";
    }

    if ($erreur == false) {
        /* si aucune erreur n'a été détectée : enregistrement */
        foreach ($creneauAModifier as $creneau) {
            if ($erreur == false) {
                $requete1 = "DELETE FROM creneau WHERE idCreneau='" . $creneau . "'";
                $result1 = mysqli_query($link, $requete1);
                if ($result1 == true) {
                    $requete2 = "DELETE FROM assigner WHERE idCreneau='" . $creneau . "'";
                    $result2 = mysqli_query($link, $requete2);
                    if ($result2 == true) {
                        $requete3 = "DELETE FROM travailler WHERE idCreneau NOT IN(SELECT idCreneau FROM creneau)";
                        $result3 = mysqli_query($link, $requete3);
                        if ($result3 == false) {
                            $erreur = true;
                            $_SESSION['error'] = "Les informations n'ont pas pu être entré dans la base de données 1.";
                        }
                    } else {
                        $erreur = true;
                        $_SESSION['error'] = "Les informations n'ont pas pu être entré dans la base de données 2.";
                    }
                } else {
                    $erreur = true;
                    $_SESSION['error'] = "Les informations n'ont pas pu être entré dans la base de données 3.";
                }
            }
        }
        $heure1 = explode(":", $heureDebut);
        $heure2 = explode(":", $heureFin);
        if (intval($heure2[0]) <= intval($heure1[0])) {
            $heureTropGrande = 24 + intval($heure2[0]);
            $duree = $heureTropGrande - intval($heure1[0]);
        } else {
            $duree = intval($heure2[0]) - intval($heure1[0]);
        }
        for ($i = 0; $i < $duree; $i++) /* on entre les créneaux dans la base de données heure par heure */ {
            $heure = explode(":", $heureDebut);
            $heure[0] = intval($heure[0]) + $i;
            if (intval($heure[0]) >= 24) {
                $heureTropGrande = intval($heure[0]) - 24;
                if ($heureTropGrande < 10) {
                    $heure[0] = "0" . $heureTropGrande;
                } else {
                    $heure[0] = $heureTropGrande;
                }
            }
            $heureActuelle = implode(":", $heure);
            if ($erreur == false) {
                /* requête pour ajouter le créneau à la BDD */
                $requete2 = "INSERT INTO creneau (dateDebut,heureDebut,nbBenevoleManquant,nbMembreManquant,idLieu,idEdition) VALUES ('" . $jour . "','" . $heureActuelle . "'," . $nombreBenevoles . "," . $nombreMembres . ",'" . $idLieuCree . "','" . $_SESSION['edition'] . "')";
                $result2 = mysqli_query($link, $requete2);
                $idCreneau = mysqli_insert_id($link);
                if ($result2 == true) {
                    /* si la requête a fonctionné : ajout du créneau des bénévoles */
                    $requete3 = "INSERT INTO assigner VALUES('Benevole'," . $idCreneau . "," . $nombreBenevoles . ")";
                    $result3 = mysqli_query($link, $requete3);
                    if ($result3 == true) {
                        /* si la requête a fonctionné : ajout du créneau des membres */
                        $requete4 = "INSERT INTO assigner VALUES('Membre'," . $idCreneau . "," . $nombreMembres . ")";
                        $result4 = mysqli_query($link, $requete4);
                        if ($result4 == true) {
                            $_SESSION['success'] = "Le créneau a bien été modifié.";
                        } else {
                            /* si erreur de la requête de l'ajout dans la BDD */
                            $erreur = true;
                            $_SESSION['error'] = "Les informations n'ont pas pu être insérer dans la base de données 4.";
                        }
                    } else {
                        /* si erreur de la requête de l'ajout dans la BDD */
                        $erreur = true;
                        $_SESSION['error'] = "Les informations n'ont pas pu être insérer dans la base de données 5.";
                    }
                } else {
                    /* si erreur de la requête de l'ajout dans la BDD */
                    $erreur = true;
                    $_SESSION['error'] = "Les informations n'ont pas pu être insérer dans la base de données 6.";
                }
            }
        }
    }

    if ($erreur == true) {
        /* si au moins une erreur détectée : enregistrement + redirection au form */
        $_SESSION['nomLieu'] = $nomLieu;
        $_SESSION['modifJour'] = $jour;
        $_SESSION['modifHeureDebut'] = $heureDebut;
        $_SESSION['modifHeureFin'] = $heureFin;
        $_SESSION['modifNbBenevole'] = $nombreBenevoles;
        $_SESSION['modifNbMembre'] = $nombreMembres;
        $_SESSION['choixCouleur'] = $choixCouleur;
    } else {
        /* sinon : enregistrement + redirection au form */
        $_SESSION['nomLieu'] = $nomLieu;
        $_SESSION['choixCouleur'] = $choixCouleur;
        unset($_SESSION['modifJour']);
        unset($_SESSION['modifHeureDebut']);
        unset($_SESSION['modifHeureFin']);
        unset($_SESSION['modifNbBenevole']);
        unset($_SESSION['modifNbMembre']);
        unset($_SESSION['modifCreneau']);
    }
    header('location:ajouterLieu.php');
    die();
} else if (isset($_POST['annulerModifsCreneau'])) {
    unset($_SESSION['modifCreneau']);
    header('location:ajouterLieu.php');
    die();
}
/***** SUPPRESSION D'UN CRENEAU *****/

else if (isset($_POST['supprCreneau'])) {
    $_SESSION['creneauASupprimer'] = $_POST['supprCreneau'];
    header('location:ajouterLieu.php');
    die();
} else if (isset($_POST['non'])) {
    unset($_SESSION['creneauASupprimer']);
    header('location:ajouterLieu.php');
    die();
} else if (isset($_POST['oui'])) {
    $erreur = false;
    $idsCreneau = $_SESSION['creneauASupprimer'];
    unset($_SESSION['creneauASupprimer']);
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
            $heureDebut = $row6['heureDebut'];
            $heure = explode(":", $heureDebut);
            $heureSuivante = intval($heure['0']) + 1;
            if ($heureSuivante < 10) {
                $heureFin = "0" . $heureSuivante . ":00:00";
            } else {
                $heureFin = $heureSuivante . ":00:00";
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
            $heureDebut = $row6['heureDebut'];
            $row6 = mysqli_fetch_assoc($result6);
            $heureFin = $row6['heureDebut'];
            $heure = explode(":", $heureFin);
            $heureSuivante = intval($heure['0']) + 1;
            if ($heureSuivante < 10) {
                $heureFin = "0" . $heureSuivante . ":00:00";
            } else {
                $heureFin = $heureSuivante . ":00:00";
            }
        } else {
            $erreur = true;
        }
    }

    foreach ($ids as $id) {
        if ($erreur == false) {
            $requete7 = "DELETE FROM creneau WHERE idCreneau='" . $id . "'";
            $result7 = mysqli_query($link, $requete7);
            if ($result7 == false) {
                $erreur = true;
            }
        }
    }

    if ($erreur == false) {
        $_SESSION['success'] = "Le créneau le " . $date . " de " . $heureDebut . " à " . $heureFin . " a bien été supprimé.";
        header('location:ajouterLieu.php');
        die();
    } else {
        $_SESSION['error'] = "Nous n'avons pas pu entré les informations dans la base de données.";
        header('location:ajouterLieu.php');
        die();
    }
}

/***** AJOUT D'UN LIEU *****/

else if (isset($_POST['ajoutLieu'])) {
    /* mise à false du booléen erreur pour détecter s'il y a ou non une erreur */
    $erreur = false;

    /* si les variables du form existent bien : enregistrement */
    if (isset($_POST['nomLieu']) && isset($_POST['choixCouleur'])) {
        $nomLieu = $_POST['nomLieu'];
        $choixCouleur = $_POST['choixCouleur'];

        if ($nomLieu == "" || $choixCouleur == "") {
            /* erreur si au moins 1 des champs est vide */
            $erreur = true;
            $_SESSION['error'] = "Tous les champs doivent être remplis";
        }
    } else {
        /* erreur si tous les champs ne sont pas remplis */
        $erreur = true;
        $_SESSION['error'] = "Tous les champs doivent être remplis";
    }

    if ($erreur == false) {
        /* si aucune erreur n'a été détectée : requête pour voir si lieu existe déjà */
        $requete1 = "SELECT * FROM lieu WHERE nom='" . $nomLieu . "'";
        $result1 = mysqli_query($link, $requete1);

        if (mysqli_num_rows($result1) > 0) {
            /* si le lieu existe : requête pour le mettre à jour */
            $row1 = mysqli_fetch_assoc($result1);
            $requete2 = "SELECT * FROM exister WHERE idEdition='" . $_SESSION['edition'] . "' AND idLieu='" . $row1['idLieu'] . "'";
            $result2 = mysqli_query($link, $requete2);

            if (mysqli_num_rows($result2) == 1) {
                /* erreur si le lieu existe déjà pour cette édition */
                $erreur = true;
                $_SESSION['error'] = "Ce nom de lieu existe déjà pour cette édition.";
            } else {
                /* si le lieu n'existe pas : requête pour le créer */
                $requete5 = "INSERT INTO exister (idEdition,idLieu) VALUES ('" . $_SESSION['edition'] . "', " . $row1['idLieu'] . ")";
                $result5 = mysqli_query($link, $requete5);

                /* alerte en fonction du résultat de la requête */
                if ($result5 == true) {
                    $_SESSION['idLieuCree'] = $row1['idLieu'];
                    if ($choixCouleur != "#fffee6") {
                        $requete8 = "UPDATE lieu SET idCouleur='" . $choixCouleur . "' WHERE idLieu='" . $row1['idLieu'] . "'";
                        $result8 = mysqli_query($link, $requete8);

                        if ($result8 == true) {
                            $_SESSION['success'] = "Ce lieu existait déjà dans une autre édition et a été ajouté à cette édition avec sa nouvelle couleur.";
                        } else {
                            $erreur = true;
                            $_SESSION['error'] = "Les informations n'ont pas pu être insérer dans la base de données.";
                        }
                    } else {
                        $requete9 = "SELECT * FROM lieu WHERE idLieu='" . $row1['idLieu'] . "'";
                        $result9 = mysqli_query($link, $requete9);
                        if (mysqli_num_rows($result9) == 1) {
                            $row9 = mysqli_fetch_assoc($result9);
                            $_SESSION['success'] = "Ce lieu existait déjà dans une autre édition et a été ajouté à cette édition.";
                            $choixCouleur = $row9['idCouleur'];
                        } else {
                            $erreur = true;
                            $_SESSION['error'] = "Les informations n'ont pas pu être insérer dans la base de données.";
                        }
                    }
                } else {
                    $erreur = true;
                    $_SESSION['error'] = "Les informations n'ont pas pu être insérer dans la base de données.";
                }
            }
        } else {
            /* si le lieu n'existe pas : requête pour le créer */
            $requete2 = "INSERT INTO lieu (nom,idCouleur) VALUES ('" . $nomLieu . "', '" . $choixCouleur . "')";
            $result2 = mysqli_query($link, $requete2);
            $idDuLieuCree = mysqli_insert_id($link);

            if ($result2 == true) {
                /* si la requête a fonctionné : requête pour insérer dans une autre table */
                $requete4 = "INSERT INTO exister (idEdition,idLieu) VALUES ('" . $_SESSION['edition'] . "', " . $idDuLieuCree . ")";
                $result4 = mysqli_query($link, $requete4);

                /* alerte en fonction du résultat de la requête */
                if ($result4 == true) {
                    $_SESSION['idLieuCree'] = $idDuLieuCree;
                    $_SESSION['success'] = "Le lieu " . $nomLieu . " a bien été ajouté.";
                } else {
                    $erreur = true;
                    $_SESSION['error'] = "Les informations n'ont pas pu être insérer dans la base de données.";
                }
            } else {
                /* sinon erreur liée à la BDD */
                $erreur = true;
                $_SESSION['error'] = "Les informations n'ont pas pu être insérer dans la base de données.";
            }
        }
    }

    if ($erreur == true) {
        /* si au moins une erreur a été détectée : enregistrement et redirection au form */
        $_SESSION['nomLieu'] = $nomLieu;
        $_SESSION['choixCouleur'] = $choixCouleur;
        header('location:ajouterLieu.php');
        die();
    } else {
        /* si au moins une erreur a été détectée : enregistrement et redirection au form */
        $_SESSION['nomLieu'] = $nomLieu;
        $_SESSION['choixCouleur'] = $choixCouleur;
        $_SESSION['creneauCree'] = 0;
        header('location:ajouterLieu.php');
        die();
    }
}

/***** MODIFICATION D'UN LIEU *****/

else if (isset($_POST['modifLieu'])) {
    /* mise à false des booléens erreur pour détecter s'il y a ou non une erreur */
    $erreur = false;
    $erreurModif = false;

    /* si les variables du form existent bien : enregistrement */
    if (isset($_POST['nomLieu']) && isset($_POST['choixCouleur'])) {
        $nomLieu = $_POST['nomLieu'];
        $choixCouleur = $_POST['choixCouleur'];

        if ($nomLieu == "" || $choixCouleur == "") {
            /* erreur si au moins 1 des champs est vide */
            $erreur = true;
            $_SESSION['error'] = "Tous les champs doivent être remplis";
        }
    } else {
        /* erreur tous les champs ne sont pas remplis */
        $erreur = true;
        $_SESSION['error'] = "Tous les champs doivent être remplis";
    }

    if ($erreur == false) {
        /* si aucune erreur n'est détectée : requête sélection du lieu */
        $requete1 = "SELECT * FROM lieu WHERE nom='" . $nomLieu . "'";
        $result1 = mysqli_query($link, $requete1);

        if (mysqli_num_rows($result1) > 0) {
            /* si plusieurs lieux ont été trouvés dans la BDD : requête pour trouver l'id du lieu */
            $requete2 = "SELECT nom FROM lieu WHERE idLieu='" . $_SESSION['idLieuCree'] . "'";
            $result2 = mysqli_query($link, $requete2);

            if (mysqli_num_rows($result2) == 1) {
                /* si la requête a fonctionné */
                $row2 = mysqli_fetch_assoc($result2);

                if ($nomLieu == $row2['nom']) {
                    $requete3 = "UPDATE lieu SET idCouleur='" . $choixCouleur . "' WHERE idLieu='" . $_SESSION['idLieuCree'] . "'";
                    $result3 = mysqli_query($link, $requete3);

                    /* alerte en fonction du résultat de la requête */
                    if ($result3 == true) {
                        $_SESSION['success'] = "Le lieu " . $nomLieu . " a bien été modifié.";
                    } else {
                        $erreur = true;
                        $erreurModif = true;
                        $_SESSION['error'] = "Les informations n'ont pas pu être insérer dans la base de données.";
                    }
                } else {
                    /* si le nom de lieu existe déjà : erreur car impossible de modif */
                    $erreur = true;
                    $erreurModif = true;
                    $_SESSION['error'] = "Le nom de lieu " . $nomLieu . " existe déjà.";
                }
            } else {
                /* si la requête n'a pas fonctionné */
                $erreur = true;
                $erreurModif = true;
                $_SESSION['error'] = "Nous n'avons pas pu récupérer les informations dans la base de données.";
            }
        } else {
            /* si un seul lieu a été trouvé : update de la BDD */
            $requete2 = "UPDATE lieu SET nom='" . $nomLieu . "', idCouleur='" . $choixCouleur . "' WHERE idLieu='" . $_SESSION['idLieuCree'] . "'";
            $result2 = mysqli_query($link, $requete2);

            /* alerte en fonction du résultat de la requête */
            if ($result2 == true) {
                $_SESSION['success'] = "Le lieu " . $nomLieu . " a bien été modifié.";
            } else {
                $erreur = true;
                $erreurModif = true;
                $_SESSION['error'] = "Les informations n'ont pas pu être insérer dans la base de données.";
            }
        }
    }

    if ($erreurModif == false) {
        /* si pas d'erreur : enregistrement des données */
        $_SESSION['nomLieu'] = $nomLieu;
        $_SESSION['choixCouleur'] = $choixCouleur;
    }

    /* redirection vers le form */
    header('location:ajouterLieu.php');
    die();
} else if (isset($_POST['retour'])) {
    /* si bouton retour pressé : redirection vers le form initialisation d'édition */
    header('location:initialiserEditionLieu.php');
    die();
} ?>