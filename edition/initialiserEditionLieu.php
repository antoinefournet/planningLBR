<!--                                * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *             Fichier PHP             *
                                    *  Form pour initialiser une édition  *
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
unset($_SESSION['nomLieu']);
unset($_SESSION['choixCouleur']);
unset($_SESSION['creneauCree']);
unset($_SESSION['idLieuCree']); ?>

<!-- PARTIE VISUELLE DE LA PAGE -->

<form action='actionInitialiserEdition.php' method='post'>
    <?php
    if (isset($_SESSION['lieuASupprimer'])) {
        echo "<form action='initialiserEditionLieu.php' method='post'>";
        echo "<div id='alertSuppression' style='position: absolute;margin: 15% 30%;z-index: 4;width: 40%;background-color: ". $couleur7 .";height: 45%;padding: 2%;'>";
        echo "<p style='text-align:center;'>Veux-tu vraiment supprimer ce lieu ?</p>";
        echo "<input id='oui' name='toutes' type='submit' value='Oui, supprimer le lieu de toutes les éditions' class='boutonSansFond boutonConfirmSuppr' style='color: " . $couleur4 . "; border: 2px solid " . $couleur2 . ";margin: 4% 16%;width: 65%;text-transform: none;'>";
        echo "<input id='oui' name='seule' type='submit' value='Oui, supprimer le lieu uniquement dans cette édition' class='boutonSansFond boutonConfirmSuppr' style='color: " . $couleur4 . "; border: 2px solid " . $couleur2 . ";margin: 4% 12%;width: 75%;text-transform: none;'>";
        echo "<input id='non' name='non' type='submit' value='Non' class='boutonSansFond boutonConfirmSuppr' style='color: " . $couleur4 . ";border: 2px solid " . $couleur2 . ";margin: 4% 40%;text-transform:none; width:15%'>";
        echo "</div>";
        echo "</form>";
    }
    ?>
    <div class="arrierePlanRectangle">
        <h1 class="titrePrincipal">INITIALISER MON éDITION</h1>

        <!-- Bouttons flèches-->
        <button class="prev boutonSansFond" type="submit" name="versInfo" value="1"><i class="fa fa-chevron-left" style="color: <?php echo $couleur4 ?>;"></i></button>
        <button class="next boutonSansFond" type="submit" name="versPersonne" value="1"><i class="fa fa-chevron-right" style="color: <?php echo $couleur4 ?>;"></i></button>
        <!-- Deuxième slide lieux ---------------------------------------------------------------------------------------------->
        <div class="rectangleJaune" style="background-color: <?php echo $couleur4 ?>; color: <?php echo $couleur7 ?>;">
            <h4 class="sousTitre sousTitreCentre">RéCAPITULATIF DES LIEUX</h4>

            <!-- Petit carrousel lieu -->
            <div class="petitCarrouselInterieur">
                <?php echo "<div class='petiteSlide' >";
                if (isset($_SESSION['edition'])) {
                    $requete2 = "SELECT * FROM lieu WHERE idLieu IN (SELECT idLieu FROM exister WHERE idEdition='" . $_SESSION['edition'] . "') ORDER BY nom ASC";
                    $result2 = mysqli_query($link, $requete2);
                    if (mysqli_num_rows($result2) > 0) {

                        $i = 0;

                        while ($row2 = mysqli_fetch_assoc($result2)) {
                            echo "<div class='divBoutonLieu'>";
                            echo "<button class='boutonSansFond boutonRouge boutonSelectLieu' style='border : 2px solid " . $couleur4 . ";background-color:" . $row2['idCouleur'] . " ' type='submit' name='modifierLieu' value=" . $row2['idLieu'] . ">";
                            echo $row2['nom'];
                            echo "</button>";
                            echo "<button type='submit' name='supprimer' value='" . $row2['idLieu'] . "' class='supprimerLieu'><i class='fa fa-trash' style='color:" . $couleur7 . "'></i></button>";
                            echo "</div>";
                            if ($i == 9) {
                                echo "</div>";
                                echo "<div class='petiteSlide' '>";
                                $i = 0;
                            } else {
                                $i++;
                            }
                        }
                        echo "<button class='boutonSansFond boutonRouge boutonSelectLieu boutonPlus' style='background-color:" . $couleur2 . ";color :" . $couleur4 . " ' type='submit' id='ajouterLieu' name='nouveauLieu' ><i class='fa fa-plus iconePlusLieu' style='color:" . $couleur1 . "';></i></button>";
                        echo "</div>";
                        echo "</div>";
                    } else {
                        echo "<button class='boutonSansFond boutonRouge boutonSelectLieu boutonPlus' style='background-color:" . $couleur2 . ";color :" . $couleur4 . " ' type='submit' id='ajouterLieu' name='nouveauLieu' ><i class='fa fa-plus iconePlusLieu' style='color:" . $couleur1 . "';></i></button>";
                        echo "</div>";
                    }
                } else {
                    echo "<button class='boutonSansFond boutonRouge boutonSelectLieu boutonPlus' style='background-color:" . $couleur2 . ";color :" . $couleur4 . " ' type='submit' id='ajouterLieu' name='nouveauLieu' ><i class='fa fa-plus iconePlusLieu' style='color:" . $couleur1 . "';></i></button>";
                    echo "</div>";
                }
                ?>

                <!-- Bouttons flèches du petit carroussel-->
                <a class="petitePrev" onclick="plusPetitesSlides(-1)" style="color: <?php echo $couleur7 ?>;left:0%;"><i class="fa fa-caret-left"></i></a>
                <a class="petiteNext" onclick="plusPetitesSlides(1)" style="color: <?php echo $couleur7 ?>;right: 0%;"><i class="fa fa-caret-right"></i></a>
            </div>
        </div>
        <button type='submit' name='annulerCreation' class="boutonAnnulerInitialisation boutonSansFond" style='color: <?php echo $couleur4 ?>;' type="submit">Annuler la création d'édition</button>
    </div>
</form>

<script>
    /***** PETIT CARROUSEL *****/

    let slidePetitIndex = 1;
    showPetitesSlides(slidePetitIndex);

    function plusPetitesSlides(n) {
        showPetitesSlides(slidePetitIndex += n);
    }

    function currentSlide(n) {
        showPetitesSlides(slidePetitIndex = n);
    }

    function showPetitesSlides(n) {
        let i;
        let slides = document.getElementsByClassName("petiteSlide");
        if (n > slides.length) {
            slidePetitIndex = 1
        }
        if (n < 1) {
            slidePetitIndex = slides.length
        }
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slides[slidePetitIndex - 1].style.display = "grid";
    }

    /***** HOVER DES BOUTONS *****/

    document.querySelectorAll(".divBoutonLieu").forEach(element => {
        element.addEventListener("mouseenter", function(event) {
            let childrens = event.target.children
            for (let i = 0; i < childrens.length; i++) {
                if (childrens[i].classList.contains("supprimerLieu")) {
                    childrens[i].style.display = 'block';
                }
            }
        });

        element.addEventListener("mouseleave", function(event) {
            let childrens = event.target.children
            for (let i = 0; i < childrens.length; i++) {
                if (childrens[i].classList.contains("supprimerLieu")) {
                    childrens[i].style.display = 'none';
                }
            }
        });
    });
</script>

<!-- insertion du footer qui comprend la question des cookies -->
<?php include("../include/footer.php"); ?>