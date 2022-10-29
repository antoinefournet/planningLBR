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

/* suppression des variables de session */
unset($_SESSION['planningGeneral']);
unset($_SESSION['nomLieu']);
unset($_SESSION['choixCouleur']);
unset($_SESSION['creneauCree']);
unset($_SESSION['idLieuCree']); ?>

<!-- PARTIE VISUELLE DE LA PAGE -->

<form action='actionModifierEdition.php' method='post' enctype='multipart/form-data'>

  <div class="arrierePlanRectangle">
    <h1 class="titrePrincipal">MODIFIER MON éDITION</h1>

    <!-- Bouttons flèches-->
    <button class="prev boutonSansFond" type="submit" name="versLieu" value="1"><i class="fa fa-chevron-left" style="color: <?php echo $couleur4 ?>;"></i></button>
    <!-- Troisième slide bénévoles ------------------------------------------------------------------------------------------>
    <div class="rectangleJaune" style="background-color: <?php echo $couleur4 ?>; color: <?php echo $couleur7 ?>;">

      <h4 class="sousTitre">LES MEMBRES ET BéNéVOLES</h4>

      <label class="labelForm">Nombre d'heures de travail par défaut pour les bénévoles :</label>
      <input class="inputForm" style='width:40%;margin-left:2%;' id="heuresDefaut" name="heuresDefaut" type="number" placeholder="Entrer un nombre d'heure" value=<?php
                                                                                                                                                                  if (isset($_SESSION['edition'])) {
                                                                                                                                                                    $requete = "SELECT nbHeureBenevoleDefaut FROM edition WHERE idEdition='" . $_SESSION['edition'] . "'";
                                                                                                                                                                    $result = mysqli_query($link, $requete);
                                                                                                                                                                    if (mysqli_num_rows($result) == 1) {
                                                                                                                                                                      $row = mysqli_fetch_assoc($result);
                                                                                                                                                                      echo $row['nbHeureBenevoleDefaut'];
                                                                                                                                                                    } else {
                                                                                                                                                                      echo $nombreHeureBenevolesDefaut;
                                                                                                                                                                    }
                                                                                                                                                                  } else {
                                                                                                                                                                    echo $nombreHeureBenevolesDefaut;
                                                                                                                                                                  }
                                                                                                                                                                  ?>>

      <label type="text" class="labelForm moyenLabelFormInitialisationSlide3" style='margin-top:1%;'>Ajouter une personne manuellement en entrant toutes ses informations :</label>
      <div class="ajoutManuelPosition">
        <div class="ligneInfoPersonne" style="border-color:<?php echo $couleur7 ?>;margin-top:1%;">
          <input class="champInitialisationPersonne" type="text" placeholder="Saisir un nom*;" name="nom" id="nom" value=<?php if (isset($_SESSION['nom'])) echo $_SESSION['nom']; ?>><br>
          <input class="champInitialisationPersonne" type="text" placeholder="un prénom*;" name="prenom" id="prenom" value=<?php if (isset($_SESSION['prenom'])) echo $_SESSION['prenom']; ?>><br>

          <input class="champInitialisationPersonne champInitialisationMail" type="text" placeholder="une adresse mail*; " name="email" id="email" value=<?php if (isset($_SESSION['email'])) echo $_SESSION['email']; ?>><br>
          <input class="champInitialisationPersonne" type="text" placeholder="un n° de tel*" name="telephone" id="telephone" value=<?php if (isset($_SESSION['telephone'])) echo $_SESSION['telephone']; ?>><br>


          <select name="type" id="type" class="champInitialisationPersonne">
            <option>Sélectionner*</option>
            <option id="membre" value="Membre" <?php if (isset($_SESSION['type'])) {
                                                  if ($_SESSION['type'] == "Membre") {
                                                    echo "selected=selected";
                                                  }
                                                } ?>>Membre</option>
            <option id="benevole" value="Benevole" <?php if (isset($_SESSION['type'])) {
                                                      if ($_SESSION['type'] == "Benevole") {
                                                        echo "selected=selected";
                                                      }
                                                    } ?>>Bénévole</option>
          </select>


          <label class="champInitialisationPersonne" for="disponibilite">H dispo J1*</label><br>
          <input class="champInitialisationPersonne" type="time" name="disponibilite" id="disponibilite" value=<?php if (isset($_SESSION['disponibilite'])) echo $_SESSION['disponibilite']; ?>><br>

          <div id="nombreHeuresTravail" hidden>
            <label for="heuresTravail">Nb d'heures à effectuer</label>
            <input class="champInitialisationPersonne" type="number" name="heuresTravail" id="heuresTravail" value=<?php
                                                                                                                    if (isset($_SESSION['edition'])) {
                                                                                                                      $requete = "SELECT nbHeureBenevoleDefaut FROM edition WHERE idEdition='" . $_SESSION['edition'] . "'";
                                                                                                                      $result = mysqli_query($link, $requete);
                                                                                                                      if (mysqli_num_rows($result) == 1) {
                                                                                                                        $row = mysqli_fetch_assoc($result);
                                                                                                                        echo $row['nbHeureBenevoleDefaut'];
                                                                                                                      } else {
                                                                                                                        echo $nombreHeureBenevolesDefaut;
                                                                                                                      }
                                                                                                                    } else {
                                                                                                                      if (isset($_SESSION['heuresTravail'])) echo $_SESSION['heuresTravail'];
                                                                                                                      else {
                                                                                                                        echo $nombreHeureBenevolesDefaut;
                                                                                                                      }
                                                                                                                    } ?>><br>
          </div>
        </div>
        <div class="positionInfoComplementaire">
          <label class=" tailleInfo" for="informations">Informations complémentaires :</label><br>
          <textarea class="champInitialisationPersonne boxInfoComplementaire" style=" border-color:<?php echo $couleur7 ?>;" type="text" name="informations" id="informations" rows="4" cols="50"><?php if (isset($_SESSION['informations'])) echo $_SESSION['informations']; ?></textarea><br>
          <input class="boutonSansFond boutonRouge boutonAjouterPersonne" style='background-color: <?php echo $couleur2 ?>; color: <?php echo $couleur4 ?>;' type="submit" name="ajouterUnePersonne" value="Ajouter cette personne"><br>
          <a class="champsObligatoires" type="text">* : champs obligatoires</a>
        </div>
      </div>

      <div class="labelForm formImporterFichier">
        <label class="moyenLabelFormInitialisationSlide3" type="text">Importer un fichier CSV :</label>
        <input class="tailleInfo" style="color: <?php echo $couleur7 ?>;" type="file" name="file" id="fileToUpload">
        <?php $listePersonnes = array();
        $lignePersonne = array();
        $lignePersonne['NOM'] = "";
        $lignePersonne['PRENOM'] = "";
        $lignePersonne['MAIL'] = "";
        $lignePersonne['TELEPHONE'] = "";
        $lignePersonne['INFOS'] = "";
        $lignePersonne['DISPOJ1'] = "";
        $lignePersonne['HEUREAFAIRE'] = "";
        $lignePersonne['TYPE'] = "";
        array_push($listePersonnes, $lignePersonne);
        $_SESSION['listePersonnes'] = $listePersonnes; ?>
        <input class="boutonSansFond boutonRouge" style='background-color: <?php echo $couleur2 ?>; color: <?php echo $couleur4 ?>' type="submit" name="importerCSV" value="Importer">
        <button type="submit" name="modeleCSV" class="boutonSansFond petiteEcritureOmbre" style="color: <?php echo $couleur7 ?>;">Télécharger un modèle CSV vide</button>
      </div>
      <input class="boutonSansFond boutonRouge boutonEnregistrerEdition" style='color: <?php echo $couleur4 ?>; background-color :<?php echo $couleur3 ?>;' type="submit" name="continuer" value="Enregistrer l'édition">
    </div>
  </div>
</form>

<!-- insertion du script pour les fonctions du carrousel et autres -->
<script type="text/javascript">
  changerAffichage();
  document.getElementById("type").addEventListener("change", () => {
    console.log("changement");
    changerAffichage();
  });

  function changerAffichage() {
    if (document.getElementById("type").value == "Benevole") {
      document.getElementById("nombreHeuresTravail").hidden = false;
    } else {
      document.getElementById("nombreHeuresTravail").hidden = true;
    }
  }

  document.getElementById("heuresDefaut").addEventListener("change", function() {
    document.getElementById("heuresTravail").value = document.getElementById("heuresDefaut").value;
  });
</script>

<!-- insertion du footer qui comprend la question des cookies -->
<?php include("../include/footer.php"); ?>