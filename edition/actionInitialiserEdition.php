<!--                                * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *             Fichier PHP             *
                                    *  Form pour initialiser une édition  *
                                    *                                     *
                                    * * * * * * * * * * * * * * * * * * * *    
-->

<!-- insertion du header avec le logo en haut à gauche -->
<?php include("../include/header.php");

/* suppression une variable de session */
unset($_SESSION['lieuAModifier']);

if (isset($_POST['versInfo'])) {
    header('location:initialiserEditionInfo.php');
    die();
}

if (isset($_POST['annulerCreation'])) {
    $requete1 = "DELETE FROM edition WHERE idEdition='" . $_SESSION['nomEdition'] . "'";
    $result1 = mysqli_query($link, $requete1);
    if ($result1 == true) {
        $requete2 = "DELETE FROM assigner WHERE idCreneau IN (SELECT idCreneau FROM creneau WHERE idEdition='" . $_SESSION['nomEdition'] . "')";
        $result2 = mysqli_query($link, $requete2);
        if ($result2 == true) {
            $requete3 = "DELETE FROM travailler WHERE idCreneau IN (SELECT idCreneau FROM creneau WHERE idEdition='" . $_SESSION['nomEdition'] . "')";
            $result3 = mysqli_query($link, $requete3);
            if ($result3 == true) {
                $requete4 = "DELETE FROM creneau WHERE idEdition='" . $_SESSION['nomEdition'] . "'";
                $result4 = mysqli_query($link, $requete4);
                if ($result4 == true) {
                    $requete5 = "DELETE FROM exister WHERE idEdition='" . $_SESSION['nomEdition'] . "'";
                    $result5 = mysqli_query($link, $requete5);
                    if ($result5 == true) {
                        $requete6 = "DELETE FROM participer WHERE idEdition='" . $_SESSION['nomEdition'] . "'";
                        $result6 = mysqli_query($link, $requete6);
                        if ($result6 == true) {
                            $_SESSION['success'] = "L'édition " . $_SESSION['nomEdition'] . " a bien été supprimée.";
                            header('location:menuEdition.php');
                            die();
                        } else {
                            $_SESSION['error'] = "L'édition n'a pas pu être supprimée.";
                            header('location:menuEdition.php');
                            die();
                        }
                    } else {
                        $_SESSION['error'] = "L'édition n'a pas pu être supprimée.";
                        header('location:menuEdition.php');
                        die();
                    }
                } else {
                    $_SESSION['error'] = "L'édition n'a pas pu être supprimée.";
                    header('location:menuEdition.php');
                    die();
                }
            } else {
                $_SESSION['error'] = "L'édition n'a pas pu être supprimée.";
                header('location:menuEdition.php');
                die();
            }
        } else {
            $_SESSION['error'] = "L'édition n'a pas pu être supprimée.";
            header('location:menuEdition.php');
            die();
        }
    } else {
        $_SESSION['error'] = "L'édition n'a pas pu être supprimée.";
        header('location:menuEdition.php');
        die();
    }

    unset($_SESSION['nomEdition']);
    unset($_SESSION['dureeEdition']);
    unset($_SESSION['dateEdition']);

    header('location:menuEdition.php');
    die();
}
if (isset($_POST['versPersonne'])) {
    header('location:initialiserEditionPersonne.php');
    die();
}
if (isset($_POST['versLieu'])) {
    header('location:initialiserEditionLieu.php');
    die();
}

/***** Supprimer un lieu *****/

if (isset($_POST['supprimer'])) {
    $_SESSION['lieuASupprimer'] = $_POST['supprimer'];
    header('location:initialiserEditionLieu.php');
    die();
}
if (isset($_POST['non'])) {
    unset($_SESSION['lieuASupprimer']);
    header('location:initialiserEditionLieu.php');
    die();
}
if (isset($_POST['seule'])) {
    $erreur = false;
    $requete = "SELECT * FROM lieu WHERE idLieu='" . $_SESSION['lieuASupprimer'] . "'";
    $result = mysqli_query($link, $requete);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $nomLieu = $row['nom'];
        $requete1 = "DELETE FROM travailler WHERE idCreneau IN(SELECT idCreneau FROM creneau WHERE idLieu='" . $_SESSION['lieuASupprimer'] . "' AND idEdition='" . $_SESSION['edition'] . "')";
        $result1 = mysqli_query($link, $requete1);
        if ($result1 == true) {
            $requete2 = "DELETE FROM assigner WHERE idCreneau IN(SELECT idCreneau FROM creneau WHERE idLieu='" . $_SESSION['lieuASupprimer'] . "' AND idEdition='" . $_SESSION['edition'] . "')";
            $result2 = mysqli_query($link, $requete2);
            if ($result2 == true) {
                $requete3 = "DELETE FROM creneau WHERE idLieu='" . $_SESSION['lieuASupprimer'] . "' AND idEdition='" . $_SESSION['edition'] . "'";
                $result3 = mysqli_query($link, $requete3);
                if ($result3 == true) {
                    $requete4 = "SELECT * FROM exister WHERE idLieu='" . $_SESSION['lieuASupprimer'] . "'";
                    $result4 = mysqli_query($link, $requete4);
                    if (mysqli_num_rows($result4) == 1) {
                        $requete5 = "DELETE FROM lieu WHERE idLieu='" . $_SESSION['lieuASupprimer'] . "'";
                        $result5 = mysqli_query($link, $requete5);
                        if ($result5 == false) {
                            $erreur = true;
                        }
                    }
                    if ($erreur == false) {
                        $requete6 = "DELETE FROM exister WHERE idLieu='" . $_SESSION['lieuASupprimer'] . "'";
                        $result6 = mysqli_query($link, $requete6);
                        if ($result6 == false) {
                            $erreur = true;
                        }
                    }
                } else {
                    $erreur = true;
                }
            } else {
                $erreur = true;
            }
        } else {
            $erreur = true;
        }
    } else {
        $erreur = true;
    }

    if ($erreur == true) {
        $_SESSION['error'] = "Le lieu n'a pas pu être supprimé.";
    } else {
        $_SESSION['success'] = "Le lieu " . $nomLieu . " a bien été supprimé de cette édition.";
    }
    unset($_SESSION['lieuASupprimer']);
    header('location:initialiserEditionLieu.php');
    die();
}
if (isset($_POST['toutes'])) {
    $erreur = false;
    $requete = "SELECT * FROM lieu WHERE idLieu='" . $_SESSION['lieuASupprimer'] . "'";
    $result = mysqli_query($link, $requete);
    if (mysqli_num_rows($result) == 1) {
        $requete1 = "DELETE FROM travailler WHERE idCreneau IN(SELECT idCreneau FROM creneau WHERE idLieu='" . $_SESSION['lieuASupprimer'] . "')";
        $result1 = mysqli_query($link, $requete1);
        if ($result1 == true) {
            $requete2 = "DELETE FROM assigner WHERE idCreneau IN(SELECT idCreneau FROM creneau WHERE idLieu='" . $_SESSION['lieuASupprimer'] . "')";
            $result2 = mysqli_query($link, $requete2);
            if ($result2 == true) {
                $requete3 = "DELETE FROM creneau WHERE idLieu='" . $_SESSION['lieuASupprimer'] . "'";
                $result3 = mysqli_query($link, $requete3);
                if ($result3 == true) {
                    $requete4 = "DELETE FROM lieu WHERE idLieu='" . $_SESSION['lieuASupprimer'] . "'";
                    $result4 = mysqli_query($link, $requete4);
                    if ($result4 == true) {
                        $requete5 = "DELETE FROM exister WHERE idLieu='" . $_SESSION['lieuASupprimer'] . "'";
                        $result5 = mysqli_query($link, $requete5);
                        if ($result5 == false) {
                            $erreur = true;
                        }
                    } else {
                        $erreur = true;
                    }
                } else {
                    $erreur = true;
                }
            } else {
                $erreur = true;
            }
        } else {
            $erreur = true;
        }
    } else {
        $erreur = true;
    }

    if ($erreur == true) {
        $_SESSION['error'] = "Le lieu n'a pas pu être supprimé.";
    } else {
        $_SESSION['success'] = "Le lieu " . $nomLieu . " a bien été supprimé de toutes les éditions.";
    }
    unset($_SESSION['lieuASupprimer']);
    header('location:initialiserEditionLieu.php');
    die();
}

/***** CREER UNE NOUVELLE EDITION *****/

if (isset($_POST['creerEdition'])) {
    /* mise à false du booléen erreur pour détecter s'il y a ou non une erreur */
    $erreur = false;
    $erreurModif = false;

    /* si les variables du form existent bien : enregistrement */
    if (isset($_POST['nomEdition']) && isset($_POST['dureeEdition']) && isset($_POST['dateEdition'])) {
        $nomEdition = $_POST['nomEdition'];
        $dureeEdition = $_POST['dureeEdition'];
        $dateEdition = $_POST['dateEdition'];

        if ($nomEdition == "" || $dureeEdition == "" || $dateEdition == "") {
            /* erreur si au moins 1 des champs est vide */
            $erreur = true;
            $_SESSION['error'] = "Tous les champs doivent être remplis.";
        } else if (!preg_match("/^[0-9]*$/", $dureeEdition)) {
            /* erreur si la durée de l'édition n'est pas au format nombre */
            $erreur = true;
            $_SESSION['error'] = "La durée de l'édition doit être un nombre.";
        }
    } else {
        /* erreur si tous les champs ne sont pas remplis */
        $erreur = true;
        $_SESSION['error'] = "Tous les champs doivent être remplis.";
    }

    if ($erreur == false) {
        /* si aucun erreur n'a été détectée : enregistrement */
        if (isset($_SESSION['edition'])) {
            $ancienneEdition = $_SESSION['edition'];
            /* requête pour obtenir les données de l'édition */
            $requete1 = "SELECT * FROM edition WHERE idEdition='" . $nomEdition . "'";
            $result1 = mysqli_query($link, $requete1);

            if (mysqli_num_rows($result1) > 0) {
                /* si on a trouvé au moins une édition avec ce nom */
                while ($row1 = mysqli_fetch_assoc($result1)) {
                    /* alerte en fontion du résultat de la requête */
                    if ($row1['idEdition'] != $ancienneEdition) {
                        $erreur = true;
                        $_SESSION['error'] = "Ce nom d'édition existe déjà.";
                    } else {
                    }
                }
            }

            if ($erreur == false) {
                /* si aucune erreur a été détctée : modif de la BDD */
                $requete3 = "UPDATE edition SET idEdition='" . $nomEdition . "', nbJour='" . $dureeEdition . "', dateJ1='" . $dateEdition . "', nbHeureBenevoleDefaut='" . $nombreHeureBenevolesDefaut . "' WHERE idEdition='" . $ancienneEdition . "'";
                $result3 = mysqli_query($link, $requete3);

                if ($result3 == true) {
                    /* si la requête a fonctionné : requête dans une autre table */
                    $requete4 = "UPDATE exister SET idEdition='" . $nomEdition . "' WHERE idEdition='" . $ancienneEdition . "'";
                    $result4 = mysqli_query($link, $requete4);

                    if ($result4 == true) {
                        /* si la requête a fonctionné : requête dans une autre table */
                        $requete5 = "UPDATE participer SET idEdition='" . $nomEdition . "' WHERE idEdition='" . $ancienneEdition . "'";
                        $result5 = mysqli_query($link, $requete5);

                        if ($result5 == true) {
                            /* si la requête a fonctionné : requête dans une autre table */
                            $requete6 = "UPDATE creneau SET idEdition='" . $nomEdition . "' WHERE idEdition='" . $ancienneEdition . "'";
                            $result6 = mysqli_query($link, $requete6);

                            if ($result6 == true) {
                                /* si la requête a fonctionné : requête dans une autre table */
                                $requete7 = "UPDATE configurer SET idEdition='" . $nomEdition . "' WHERE idEdition='" . $ancienneEdition . "'";
                                $result7 = mysqli_query($link, $requete7);

                                if ($result7 == false) {
                                    /* si la requête n'a pas foncitonné : alerte */
                                    $erreurModif = true;
                                    $erreur = true;
                                    $_SESSION['error'] = "Les informations n'ont pas pu être modifiés dans la base de données.";
                                }
                            } else {
                                /* si la requête n'a pas foncitonné : alerte */
                                $erreurModif = true;
                                $erreur = true;
                                $_SESSION['error'] = "Les informations n'ont pas pu être modifiés dans la base de données.";
                            }
                        } else {
                            /* si la requête n'a pas foncitonné : alerte */
                            $erreurModif = true;
                            $erreur = true;
                            $_SESSION['error'] = "Les informations n'ont pas pu être modifiés dans la base de données.";
                        }
                    } else {
                        /* si la requête n'a pas foncitonné : alerte */
                        $erreurModif = true;
                        $erreur = true;
                        $_SESSION['error'] = "Les informations n'ont pas pu être modifiés dans la base de données.";
                    }
                } else {
                    /* si la requête n'a pas foncitonné : alerte */
                    $erreurModif = true;
                    $erreur = true;
                    $_SESSION['error'] = "Les informations n'ont pas pu être modifiés dans la base de données.";
                }
            }

            if ($erreur == false) {
                /* si aucune erreur n'a été détectée */
                $_SESSION['success'] = "L'édition " . $nomEdition . " a bien été modifiée.";
                $_SESSION['edition'] = $nomEdition;
            }
        } else {
            $requete1 = "SELECT * FROM edition WHERE idEdition='" . $nomEdition . "'";
            $result1 = mysqli_query($link, $requete1);

            if (mysqli_num_rows($result1) > 0) {
                /* si plusieurs noms ont été trouvés */
                $erreur = true;
                $_SESSION['error'] = "Ce nom d'édition existe déjà.";
            } else {
                /* si aucun nom n'a été trouvé : on l'ajoute */
                $requete2 = "INSERT INTO edition VALUES('" . $nomEdition . "','" . $dureeEdition . "','" . $dateEdition . "','" . $nombreHeureBenevolesDefaut . "')";
                $result2 = mysqli_query($link, $requete2);

                /* alertes en fonction du résultat de la requête */
                if ($result2 == true) {
                    $_SESSION['success'] = "L'édition " . $nomEdition . " a bien été créé.";
                    $_SESSION['edition'] = $nomEdition;
                } else {
                    $erreur = true;
                    $_SESSION['error'] = "Les informations n'ont pas pu être insérées dans la base de données.";
                }
            }
        }
    }
    if ($erreur == true) {
        $_SESSION['nomEdition'] = $nomEdition;
        $_SESSION['dureeEdition'] = $dureeEdition;
        $_SESSION['dateEdition'] = $dateEdition;

        header('location:initialiserEditionInfo.php');
        die();
    }

    if ($erreurModif == false) {
        /* si aucune erreur n'a été détectée : enregistrement */
        $_SESSION['nomEdition'] = $nomEdition;
        $_SESSION['dureeEdition'] = $dureeEdition;
        $_SESSION['dateEdition'] = $dateEdition;

        header('location:initialiserEditionLieu.php');
        die();
    }

    /* redirection vers le form pour intialiser une édition */
    header('location:initialiserEditionInfo.php');
    die();
}

/***** AJOUT D'UN NOUVEAU LIEU *****/

else if (isset($_POST['nouveauLieu'])) {

    if (isset($_SESSION['edition'])) {
        /* redirection vers le form pour ajouter un lieu */
        header('location:ajouterLieu.php');
        die();
    } else {
        /* alertes d'erreur car infos pas enregistrées */
        $_SESSION['error'] = "Il faut enregistrer les informations de la première page pour pouvoir créer un nouveau lieu.";
        header('location:initialiserEditionInfo.php');
        die();
    }
}

/***** MODIFIER UN LIEU EXISTANT *****/

else if (isset($_POST['modifierLieu'])) {
    $_SESSION['lieuAModifier'] = $_POST['modifierLieu'];
    /* redirection vers le form pour modifier un lieu */
    header('location:modifierLieu.php');
    die();
}

/***** AJOUTER UNE NOUVELLE PERSONNE MANUELLEMENT *****/

else if (isset($_POST['ajouterUnePersonne'])) {
    /* mise à false du booléen erreur pour détecter s'il y a ou non une erreur */
    $erreur = false;

    if (!isset($_SESSION['edition'])) {
        /* si les infos de l'édition n'ont pas été enregistrée */
        $erreur = true;
        $_SESSION['error'] = "Il faut enregistrer les informations de la première page pour pouvoir ajouter des membres.";
    } else {
        /* si les infos de l'édition ont bien été enregistrées */
        if (isset($_POST['heuresDefaut'])) {
            /* requête pour mettre à jour la BDD avec le nb d'heures qu'un bénéole doit faire par défaut */
            $requete = "UPDATE edition SET nbHeureBenevoleDefaut='" . $_POST['heuresDefaut'] . "' WHERE idEdition='" . $_SESSION['edition'] . "'";
            $result = mysqli_query($link, $requete);
            /* alerte en fonction du résultat de la requête */
            if ($result == true) {
                $_SESSION['warning'] = "Le nombre d'heures par défaut a été mis à jour";
            }
        }

        if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['telephone']) && isset($_POST['type']) && isset($_POST['disponibilite'])) {
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
            $type = $_POST['type'];
            $disponibilite = $_POST['disponibilite'];

            if ($type == "Benevole") {
                $heuresTravail = $_POST['heuresTravail'];
            } else {
                $heuresTravail = 0;
            }
            $erreur = enregistrerPersonneDansBDD($link, $nom, $prenom, $email, $telephone, $informations, $type, $disponibilite, $heuresTravail);
        } else {
            /* si tous les champs obligatoires ne sont pas remplis */
            $erreur = true;
            $_SESSION['error'] = "Tous les champs marqués d'une * doivent être remplis.";
        }
    }

    if ($erreur == true) {
        /* si au moins une erreur est détectée */
        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;
        $_SESSION['email'] = $email;
        $_SESSION['telephone'] = $telephone;
        $_SESSION['informations'] = $informations;
        $_SESSION['type'] = $type;
        $_SESSION['disponibilite'] = $disponibilite;
        $_SESSION['heuresTravail'] = $heuresTravail;
    } else {
        /* sinon suppression des varibales de sessions + alerte */
        unset($_SESSION['nom']);
        unset($_SESSION['prenom']);
        unset($_SESSION['email']);
        unset($_SESSION['telephone']);
        unset($_SESSION['informations']);
        unset($_SESSION['type']);
        unset($_SESSION['disponibilite']);
        unset($_SESSION['heuresTravail']);
    }

    /* redirection vers le form pour initialiser une édition */
    header("location:initialiserEditionPersonne.php");
    die();
}

/* TELECHARGER UN MODELE CSV VIDE */ else if (isset($_POST['modeleCSV'])) {
    unset($_SESSION['listePersonnes']);
    $_SESSION['modeleVide'] = true;
    header('location:../gerer_mail_fichierPDF_fichierCSV/exportCSV.php');
    die();
}

/* AJOUT D'UNE LISTE DE PERSONNES AVEC CSV */ else if (isset($_POST['importerCSV'])) {
    /* mise à false du booléen erreur pour détecter s'il y a ou non une erreur */
    $erreur = false;

    $fileExtensionsAllowed = ['csv'];
    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileTmpName  = $_FILES['file']['tmp_name'];
    $fileType = $_FILES['file']['type'];
    $tabfileName = explode('.', $fileName);
    $fileExtension = strtolower(end($tabfileName));

    /* gestion des erreurs possibles à cause du fichier importé */
    if (!in_array($fileExtension, $fileExtensionsAllowed)) {
        $erreur = true;
        $_SESSION['error'] = "Le fichier sélectionné doit être un fichier CSV.";
    } else if ($fileSize > 4000000) {
        $erreur = true;
        $_SESSION['error'] = "Le fichier est trop volumineux (4MB max).";
    } else {
        /* si le fichier est bien importé */
        if (($fichierCSV = fopen($fileTmpName, "r")) !== FALSE) {

            /* mettre dans la BDD */

            $planning = array();

            /* lecture et analyse du fichier */
            while (($data = fgetcsv($fichierCSV, 1000, ";")) !== FALSE) {
                $ligne = array();
                foreach ($data as $case) {
                    array_push($ligne, $case);
                }
                array_push($planning, $ligne);
            }
            $numeroLigne = 10;
            foreach ($planning as $element) {
                if ($numeroLigne != 10) {
                    if (count($element) == 8) {
                        $_SESSION['csv'] = true;
                        $erreur = enregistrerPersonneDansBDD($link, $element[0], $element[1], $element[2], $element[3], $element[4], $element[7], $element[5], $element[6]);
                    } else {
                        $erreur = true;
                        $_SESSION['error'] = "Le fichier CSV doit contenir 8 colonnes.";
                    }
                } else {
                    /* pour la première ligne */
                }
                $numeroLigne++;
            }
            fclose($fichierCSV);
            if ($erreur == false) {
                $_SESSION['success'] = "Le fichier CSV a bien été importé.";
            }
        } else {
            /* erreur lors de l'import du fichier */
            $_SESSION['error'] = "Impossible d'ouvrir le fichier.";
        }
    }

    /* redirection vers le form pour innitialiser l'édition */
    header("location:initialiserEditionPersonne.php");
    die();
}

/***** LORSQU'ON CONTINNUE L'INITIALISATION D'EDITION *****/

else if (isset($_POST['continuer'])) {
    /* vérification des 1e données */
    if (isset($_SESSION['edition'])) {
        header('location:../planning/planningGeneral.php');
        die();
    } else {
        /* si les données entrées ne sont pas suffisantes */
        $_SESSION['error'] = "Vous devez créer une édition pour pouvoir continuer.";
        header('location:initialiserEditionInfo.php');
        die();
    }
}

/**
 * Enregistre une personne dans la base de donnée
 * @param { String } $link : lien pour se connecter à la BDD
 * @param { String } $nom : nom de la personne à ajouter
 * @param { String } $prenom : prénom de la personne à ajouter
 * @param { String } $email : email de la personne à ajouter
 * @param { String } $telephone : téléphone de la personne à ajouter
 * @param { String } $informations : informations supplémentaires de la personne à ajouter
 * @param { String } $type : membre ou bénévole
 * @param { String } $disponibilite : Heure de dispo de la personne à ajouter (lors du J1)
 * @param { Number } $heuresTravail : Nombre total d'heures que doit travailler la personne à ajouter
 * @return { Booleen } $erreur : false si c'est good / true si erreur
 */
function enregistrerPersonneDansBDD($link, $nom, $prenom, $email, $telephone, $informations, $type, $disponibilite, $heuresTravail)
{
    /* mise à false du booléen erreur pour détecter s'il y a ou non une erreur */
    $erreur = false;

    if (preg_match("/^[=][\"](0|\+33)[1-9][ .-]?[0-9]{2}[ .-]?[0-9]{2}[ .-]?[0-9]{2}[ .-]?[0-9]{2}[\"]$/", $telephone)) {
        $telephone = substr($telephone, 2, 10);
    }
    if (preg_match("/^([0][0-9]|[1][0-9]|[2][0-3]):00:00$/", $disponibilite)) {
        $disponibilite = substr($disponibilite, 0, 5);
    }

    if ($nom == "" || $prenom == "" || $email == "" || $telephone == "" || $type == "Sélectionner*" || $disponibilite == "") {
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
        /* si aucune erreur n'est détectée : requête pour sélectionner la personne */
        $requete3 = "SELECT * FROM personne WHERE mail='" . $email . "' AND telephone='" . $telephone . "'";
        $result3 = mysqli_query($link, $requete3);

        if (mysqli_num_rows($result3) == 0) {
            /* si elle n'existe pas encore */
            $requete4 = "SELECT dateJ1 FROM edition WHERE idEdition='" . $_SESSION['edition'] . "'";
            $result4 = mysqli_query($link, $requete4);

            if (mysqli_num_rows($result4) > 0) {
                /* ajout de la personne dans la BDD */
                $row4 = mysqli_fetch_assoc($result4);
                $nom = strtoupper($nom);
                $prenom = mb_strtolower($prenom, "UTF-8");
                $prenom = mb_strtoupper(mb_substr($prenom, 0, 1, "UTF-8"), "UTF-8") . mb_substr($prenom, 1, strlen($prenom) - 1, "UTF-8");
                $requete5 = "INSERT INTO personne (nom,prenom,telephone,mail,infoSupplementaires,typePersonne) VALUES ('" . $nom . "','" . $prenom . "','" . $telephone . "','" . $email . "','" . $informations . "','" . $type . "')";
                $result5 = mysqli_query($link, $requete5);
                $idPersonneCree = mysqli_insert_id($link);
                if ($result5 == false) {
                    $erreur = true;
                    $_SESSION['error'] = "Les informations n'ont pas pu être entré dans la base de donnnées.";
                } else {
                    if (!isset($_SESSION['csv'])) {
                        $_SESSION['success'] = $nom . " " . $prenom . " a bien été ajouté.";
                    } else {
                        unset($_SESSION['csv']);
                    }
                }
            } else {
                $erreur = true;
                $_SESSION['error'] = "Les informations n'ont pas pu être entré dans la base de donnnées.";
            }
        } else {
            $row3 = mysqli_fetch_assoc($result3);
            $idPersonneCree = $row3['idPersonne'];
            $requete8 = "SELECT * FROM personne WHERE idPersonne='" . $idPersonneCree . "'";
            $result8 = mysqli_query($link, $requete8);
            if (mysqli_num_rows($result8) == 1) {
                $row8 = mysqli_fetch_assoc($result8);
                if (!isset($_SESSION['csv'])) {
                    $_SESSION['success'] = $row8['nom'] . " " . $row8['prenom'] . " existait déjà dans une précédente édition et a été ajouté à cette édition.";
                } else {
                    unset($_SESSION['csv']);
                }
            } else {
                $erreur = true;
                $_SESSION['error'] = "Les informations n'ont pas pu être entré dans la base de donnnées.";
            }
        }

        if ($erreur == false) {
            /* ajout de la personne dans d'autres tables */
            $requete6 = "INSERT INTO participer (idEdition,idPersonne,heureDispoJ1,nbHeureAFaire,nbHeurePlanifiee) VALUES ('" . $_SESSION['edition'] . "','" . $idPersonneCree . "', '" . $disponibilite . ":00'," . $heuresTravail . ",0)";
            $result6 = mysqli_query($link, $requete6);

            if ($result6 == false) {
                $erreur = true;
                $_SESSION['error'] = "Les informations n'ont pas pu être entré dans la base de donnnées.";
            }
        }
    }
    return ($erreur);
}

/* insertion du footer qui comprend la question des cookies */
include("../include/footer.php"); ?>