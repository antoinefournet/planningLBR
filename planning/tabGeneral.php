<!--                                * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *             Fichier PHP             *
                                    *  Contenu du tab - planning général  *
                                    *                                     *
                                    * * * * * * * * * * * * * * * * * * * *    
-->

<?php
/* initialisation du nombre d'heure */
$nbHeure = 24;
 ?>

<?php
  if (!isset($_SESSION['planningGeneral'])) {
    echo "<table id='tableau'>";
    $tableauGeneral = new TableauGeneral();
    $requete1 = "SELECT * FROM edition WHERE idEdition='" . $_SESSION['edition'] . "'";
    $result1 = mysqli_query($link, $requete1);
    if (mysqli_num_rows($result1) == 1) {
      $row1 = mysqli_fetch_assoc($result1);
      $dateJ1 = $row1['dateJ1'];
      $nbJour = $row1['nbJour'];
      $jours = array();

      echo "<tr>";
      echo "<th rowspan='2' class='tabTitre'></th>";

      for ($i = 0; $i < $nbJour; $i++) {

        /* parcours des jours de l'édition */
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
        array_push($jours, $jourActuel);
      }
      echo "</tr>";

      /* ligne contenant les heures */
      echo "<tr>";
      $heures = array();
      for ($jour = 0; $jour < $nbJour; $jour++) {
        for ($heure = 0; $heure < $nbHeure; $heure++) {
          array_push($heures, $heure . "h");
        }
      }
      for ($jour = 0; $jour < $nbJour; $jour++) {
        if ($jour % 2 == 0) {
          for ($heure = 0; $heure < $nbHeure; $heure++) {
            echo "<th style='top:36px;z-index:3;text-align:center;min-width:100px;' class='tabTitre'>" . $heure . "h</th>";
          }
        } else {
          for ($heure = 0; $heure < $nbHeure; $heure++) {
            echo "<th style='top:36px;z-index:3;text-align:center;min-width:100px;background-color : $couleur3'' class='tabTitre'>" . $heure . "h</th>";
          }
        }
      }
      echo "</tr>";

      /* * * * * * * * * * * * * * * REQUETE NUMERO 2 * * * * * * * * * * * * * * */

      /* sélectionner les infos des personnes participant à l'édition par ordre alphabétique */
      $erreur = false;
      $requete2 = "SELECT * FROM personne WHERE idPersonne IN(SELECT idPersonne FROM participer WHERE idEdition='" . $_SESSION['edition'] . "') ORDER BY typePersonne DESC, nom ASC, prenom ASC";
      $result2 = mysqli_query($link, $requete2);
      if (mysqli_num_rows($result2) > 0) {
        while ($row2 = mysqli_fetch_assoc($result2)) {
          $requete6 = "SELECT * FROM participer WHERE idEdition='" . $_SESSION['edition'] . "' AND idPersonne='" . $row2['idPersonne'] . "'";
          $result6 = mysqli_query($link, $requete6);
          if (mysqli_num_rows($result6) == 1) {
            if ($erreur == false) {
              $row6 = mysqli_fetch_assoc($result6);
              /* affichage des personnes et redirection */
              echo "<tr>";
              if ($row2['typePersonne'] == 'Membre') {
                echo "<td class='tabTitre' style='min-width:150px;left:0px;background-color :" . $couleur3 . ";'><button type='submit' name='personne' class='boutonSansFond' style='color:" . $couleur4 . ";' value='" . $row2['idPersonne'] . "'>" . $row2['nom'] . "  " . $row2['prenom'] . "</button></td>";
              } else {
                echo "<td class='tabTitre' style='min-width:150px;left:0px;'><button type='submit' name='personne' class='boutonSansFond' style='color:" . $couleur4 . ";' value='" . $row2['idPersonne'] . "'>" . $row2['nom'] . "  " . $row2['prenom'] . "</button></td>";
              }
              $lignePlanning = array();
              $lignePDF = array();

              /* parcours des heures et chaque jour */
              for ($jour = 0; $jour < $nbJour; $jour++) {
                $colonnePlanning = array();
                $colonnePDF = array();
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
                  } else {
                    $heureDebut = $heure . ":00:00";
                  }
                  $heureDispoJ1 = explode(":", $row6['heureDispoJ1']);

                  /* remplissage en bleu des cases si les personnes ne sont pas disponibles à ces horraires là */
                  if ($jour == 0 && $heure < intval($heureDispoJ1['0'])) {
                    $colonnePlanning[$heures[$heure]] = "Indisponible";
                    $colonnePDF[$heures[$heure]] = "Indisponible";
                    echo "<td style='padding:0;background-color:" . $couleur5 . ";'></td>";
                  } else {
                    $heuretab = explode(":", $heureDebut);

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


                    /* * * * * * * * * * * * * * * REQUETE NUMERO 3 * * * * * * * * * * * * * * */

                    /* sélectionner les infos des créneaux de l'édition à ce moment là */
                    $requete3 = "SELECT * FROM creneau WHERE dateDebut='" . $date . "' AND heureDebut='" . $heureDebut . "' AND idEdition='" . $_SESSION['edition'] . "'";
                    $result3 = mysqli_query($link, $requete3);
                    if (mysqli_num_rows($result3) > 0) {

                      /* on crée un booléen pour savoir si la case est parcourrue */
                      $caseOk = false;
                      while ($row3 = mysqli_fetch_assoc($result3)) {


                        /* * * * * * * * * * * * * * * REQUETE NUMERO 4 * * * * * * * * * * * * * * */

                        /* sélectionner le lieu où travaille la personne à ce moment là */
                        $requete4 = "SELECT * FROM travailler WHERE idPersonne='" . $row2['idPersonne'] . "' AND idCreneau='" . $row3['idCreneau'] . "'";
                        $result4 = mysqli_query($link, $requete4);
                        if (mysqli_num_rows($result4) == 1) {


                          /* * * * * * * * * * * * * * * REQUETE NUMERO 5 * * * * * * * * * * * * * * */

                          /* sélectionner les infos des lieux trouvés */
                          $requete5 = "SELECT * FROM lieu WHERE idLieu='" . $row3['idLieu'] . "'";
                          $result5 = mysqli_query($link, $requete5);
                          if (mysqli_num_rows($result5) == 1) {
                            /* on spécifie qu'on a parcourru la case */
                            $caseOk = true;
                            $row5 = mysqli_fetch_assoc($result5);

                            /* on affiche le bouton pour cliquer sur la case */
                            $colonnePlanning[$heures[$heure]] = $row5['nom'] . "_" . $row5['idCouleur'] . "_" . $jour . "_" . $heure . "_" . $row2['idPersonne'] . "_" . $row5['idLieu'];
                            $colonnePDF[$heures[$heure]] = $row5['nom'];
                            echo "<td style='padding:0;background-color:" . $row5['idCouleur'] . "'><button type='submit' name='caseRemplie' class='btnCaseRemplie boutonSansFond' value='" . $jour . "_" . $heure . "_" . $row2['idPersonne'] . "_" . $row5['idLieu'] . "' style='margin:0;width:100%;padding:10%;border:none;'>";
                            $nomCase = $row5['nom'];
                            if (strlen($nomCase) > 10) {
                              for ($i = 0; $i < strlen($row5['nom']); $i++) {
                                echo $nomCase[$i];
                                if ($i % 7 == 0 && $i != 0) {
                                  echo " ";
                                }
                              }
                            } else {
                              echo $row5['nom'];
                            }
                            echo "</button></td>";
                          }
                        }
                      }

                      /* si la case n'a pas été parcourrue : c'est que la personne est en pause */
                      if ($caseOk == false) {
                        $colonnePlanning[$heures[$heure]] = "Pause_" . $jour . "_" . $heure . "_" . $row2['idPersonne'];
                        $colonnePDF[$heures[$heure]] = "Pause";
                        echo "<td style='padding:0'><button type='submit' name='caseVide' class='btnCaseVide boutonSansFond' value='" . $jour . "_" . $heure . "_" . $row2['idPersonne'] . "_Pause'  style='margin:0;width:100%;padding:5%;'>Pause</button></td>";
                      }
                    } else {
                      $colonnePlanning[$heures[$heure]] = "AucunCreneau";
                      $colonnePDF[$heures[$heure]] = "AucunCreneau";
                      echo "<td style='padding:0;background-color:" . $couleur7 . ";border-color:" . $couleur4 . ";'>";
                      echo "<button type='submit' class='boutonSansFond tailleComplete' name='versAjoutCreneau' style='margin:0;padding:0;color:" . $couleur4 . ";'>Créneau inexistant</button>";
                      echo "</td>";
                    }
                  }
                }
                $lignePlanning[$jours[$jour]] = $colonnePlanning;
                $lignePDF[$jours[$jour]] = $colonnePDF;
              }
            }
            echo "</tr>";
            $planningATelecharger[$row2['nom'] . " " . $row2['prenom']] = $lignePDF;
            $tableauGeneral->planningGeneral[$row2['nom'] . "_" . $row2['prenom'] . "_" . $row2['idPersonne'] . "_" . $row2['typePersonne']] = $lignePlanning;
          } else {
            $erreur = true;
            $_SESSION['error'] = "Les informations n'ont pas pu être insérés dans la base de données.";
          }
        }
        unset($_SESSION['planningPDFPersonne']);
        unset($_SESSION['planningPDFLieu']);
        unset($_SESSION['planningPDFGeneral']);
        $_SESSION['planningPDFGeneral'] = $planningATelecharger;
        $_SESSION['redirectPDF'] = "planningGeneral";
      }
    } else {
      /* si on n'a pas trouvé la personne */
      $erreur = true;
      $_SESSION['error'] = "Nous n'avons pas pu récupérer les informations dans la base de données.";
    }
    $_SESSION['planningGeneral'] = serialize($tableauGeneral);
    echo "</table>";
  } else {
    $tableauGeneral = unserialize($_SESSION['planningGeneral']);
    echo "<table  id='tableau'>";
    /* récupération des données de la BDD */
    $data = $tableauGeneral->planningGeneral;

    /* $header2 stocke un tableau avec toutes les personnes (NOM Prénom) */
    $header2 = array_keys($data);
    $keys = array_keys($data[$header2['0']]);

    /* $header stocke un tableau avec toutes les dates (Lundi 01 Janvier 2022) */
    $header = array();
    foreach ($keys as $key) {
      array_push($header, $key);
    }
    foreach ($data as $row) {
      foreach ($row as $col) {
        $keys2 = array_keys($col);
      }
    }

    /* $header3 stocke un tableau de toutes les heures (0h 1h ... 23h) */
    $header3 = array();
    foreach ($keys2 as $key2) {
      array_push($header3, $key2);
    }

    $nbJour = 0;
    echo "<tr>";
    echo "<th rowspan='2' class='tabTitre'></th>";
    foreach ($header as $head) {
      if ($nbJour % 2 == 0) {
        echo "<th class='tabTitre' colspan='6' style='top:0px;text-align:center;z-index:3;'>" . $head . " - Nuit</th>";
        echo "<th class='tabTitre' colspan='6' style='top:0px;text-align:center;z-index:3;'>" . $head . " - Matin</th>";
        echo "<th class='tabTitre' colspan='6' style='top:0px;text-align:center;z-index:3;'>" . $head . " - Aprèm</th>";
        echo "<th class='tabTitre' colspan='6' style='top:0px;text-align:center;z-index:3;'>" . $head . " - Soir</th>";
      } else {
        echo "<th class='tabTitre' colspan='6' style='top:0px;text-align:center;background-color : $couleur3';z-index:3;>" . $head . " - Nuit</th>";
        echo "<th class='tabTitre' colspan='6' style='top:0px;text-align:center;background-color : $couleur3';z-index:3;>" . $head . " - Matin</th>";
        echo "<th class='tabTitre' colspan='6' style='top:0px;text-align:center;background-color : $couleur3';z-index:3;>" . $head . " - Aprèm</th>";
        echo "<th class='tabTitre' colspan='6' style='top:0px;text-align:center;background-color : $couleur3';z-index:3;>" . $head . " - Soir</th>";
      }
      $nbJour++;
    }
    echo "</tr>";

    echo "<tr>";
    for ($jour = 0; $jour < $nbJour; $jour++) {
      if ($jour % 2 == 0) {
        foreach ($header3 as $head3) {
          echo "<th style='top:36px;z-index:3;text-align:center;min-width:100px;' class='tabTitre'>" . $head3 . "</th>";
        }
      } else {
        foreach ($header3 as $head3) {
          echo "<th style='top:36px;z-index:3;text-align:center;min-width:100px;background-color : $couleur3'' class='tabTitre'>" . $head3 . "</th>";
        }
      }
    }
    echo "</tr>";

    $planningATelecharger = array();
    foreach ($tableauGeneral->planningGeneral as $cle1 => $value1) {
      echo "<tr>";
      $personne = explode("_", $cle1);
      if ($personne['3'] == 'Membre') {
        echo "<td class='tabTitre' style='min-width:150px;left:0px;background-color :" . $couleur3 . ";'><button type='submit' name='personne' class='boutonSansFond' style='color:" . $couleur4 . ";' value='" . $personne['2'] . "'>" . $personne['0'] . "  " . $personne['1'] . "</button></td>";
      } else {
        echo "<td class='tabTitre' style='min-width:150px;left:0px;'><button type='submit' name='personne' class='boutonSansFond' style='color:" . $couleur4 . ";' value='" . $personne['2'] . "'>" . $personne['0'] . "  " . $personne['1'] . "</button></td>";
      }
      $lignePlanning = array();
      foreach ($value1 as $cle2 => $value2) {
        $colonnePlanning = array();
        foreach ($value2 as $cle3 => $value3) {
          $value = explode("_", $value3);
          if ($value['0'] == "AucunCreneau") {
            echo "<td style='padding:0;background-color:" . $couleur7 . ";border-color:" . $couleur4 . ";'>";
            echo "<button type='submit' class='boutonSansFond tailleComplete' name='versAjoutCreneau' style='margin:0;padding:0;color:" . $couleur4 . ";'>Créneau inexistant</button>";
            echo "</td>";
          } else if ($value['0'] == "Indisponible") {
            echo "<td style='padding:0;background-color:" . $couleur5 . ";'></td>";
          } else if ($value['0'] == "Pause") {
            echo "<td style='padding:0'><button type='submit' name='caseVide' class='btnCaseVide boutonSansFond' value='" .  $value['1'] . "_" . $value['2'] . "_" . $value['3'] . "_Pause'  style='margin:0;width:100%;padding:5%;'>Pause</button></td>";
          } else {
            echo "<td style='padding:0;background-color:" . $value['1'] . "'><button type='submit' name='caseRemplie' class='btnCaseRemplie boutonSansFond' value='" . $value['2'] . "_" . $value['3'] . "_" . $value['4'] . "_" . $value['5'] . "' style='margin:0;width:100%;padding:10%;border:none;'>";
            $nomCase = $value['0'];
            if (strlen($nomCase) > 10) {
              for ($i = 0; $i < strlen($nomCase); $i++) {
                echo $nomCase[$i];
                if ($i % 7 == 0 && $i != 0) {
                  echo " ";
                }
              }
            } else {
              echo  $value['0'];
            }
            echo "</button></td>";
          }
          $colonnePlanning[$cle3] = $value['0'];
        }
        $lignePlanning[$cle2] = $colonnePlanning;
      }
      echo "</tr>";
      $planningATelecharger[$personne['0'] . " " . $personne['1']] = $lignePlanning;
    }
    echo "</table>";
    unset($_SESSION['planningPDFPersonne']);
    unset($_SESSION['planningPDFLieu']);
    unset($_SESSION['planningPDFGeneral']);
    $_SESSION['planningPDFGeneral'] = $planningATelecharger;
    $_SESSION['redirectPDF'] = "planningGeneral";
  }
 ?>

<!-- insertion du script pour les fonctions -->
<script type="text/javascript" src="../js/planningGeneral.js"></script>