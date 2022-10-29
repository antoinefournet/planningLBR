<!--                                * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *             Fichier PHP             *
                                    *          Menu des éditions          *
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
unset($_SESSION['edition']);
unset($_SESSION['editionAModifier']);
unset($_SESSION['nomEdition']);
unset($_SESSION['dureeEdition']);
unset($_SESSION['dateEdition']);

if (isset($_POST['modifier'])) {
    $_SESSION['edition'] = $_POST['modifier'];
    $_SESSION['editionAModifier'] = $_POST['modifier'];
    header('location:modifierEditionInfo.php');
    die();
}

if (isset($_POST['supprimer'])) {
    $_SESSION['editionASupprimer'] = $_POST['supprimer']; ?>
    <form action='menuEdition.php' method='post'>
        <div id='alertSuppression' style='background-color: <?php echo $couleur7 ?>;'>
            <p style='text-align:center'>Veux-tu vraiment supprimer l'édition <span style='color: <?php echo $couleur2 ?>'><?php echo $_POST['supprimer']  ?></span> ?</p>
            <input id='oui' name='oui' type='submit' value='Oui' class='boutonConfirmSuppr' style='margin: 4% 20%; color: <?php echo $couleur4 ?>; border: 2px solid <?php echo $couleur2 ?>;'>
            <input id='non' name='non' type='submit' value='Non' class='boutonConfirmSuppr' style='color: <?php echo $couleur4 ?>;border: 2px solid <?php echo $couleur2 ?>;'>
        </div>
    </form>
<?php }

if (isset($_POST['oui'])) {
    $requete1 = "DELETE FROM edition WHERE idEdition='" . $_SESSION['editionASupprimer'] . "'";
    $result1 = mysqli_query($link, $requete1);
    if ($result1 == true) {
        $requete2 = "DELETE FROM assigner WHERE idCreneau IN (SELECT idCreneau FROM creneau WHERE idEdition='" . $_SESSION['editionASupprimer'] . "')";
        $result2 = mysqli_query($link, $requete2);
        if ($result2 == true) {
            $requete3 = "DELETE FROM travailler WHERE idCreneau IN (SELECT idCreneau FROM creneau WHERE idEdition='" . $_SESSION['editionASupprimer'] . "')";
            $result3 = mysqli_query($link, $requete3);
            if ($result3 == true) {
                $requete4 = "DELETE FROM creneau WHERE idEdition='" . $_SESSION['editionASupprimer'] . "'";
                $result4 = mysqli_query($link, $requete4);
                if ($result4 == true) {
                    $requete5 = "DELETE FROM exister WHERE idEdition='" . $_SESSION['editionASupprimer'] . "'";
                    $result5 = mysqli_query($link, $requete5);
                    if ($result5 == true) {
                        $requete6 = "DELETE FROM participer WHERE idEdition='" . $_SESSION['editionASupprimer'] . "'";
                        $result6 = mysqli_query($link, $requete6);
                        if ($result6 == true) {
                            $_SESSION['success'] = "L'édition " . $_SESSION['editionASupprimer'] . " a bien été supprimée.";
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
    unset($_SESSION['editionASupprimer']);
}

if (isset($_POST['nouvelleEdition'])) {
    unset($_SESSION['nomEdition']);
    unset($_SESSION['dureeEdition']);
    unset($_SESSION['dateEdition']);
    header('location:initialiserEditionInfo.php');
    die();
}

if (isset($_POST['choixEdition'])) {
    $_SESSION['edition'] = $_POST['choixEdition'];
    header('location:../planning/planningGeneral.php');
}
?>

<!-- PARTIE VISUELLE DE LA PAGE -->

<div class="arrierePlanCarrousel">

    <div classe="arrierePlanCarrousel">
        <h1 class='titrePrincipal'>Gérer mes éditions</h1>

        <div class="carrouselInterieur">

            <?php
            $requete = "SELECT idEdition FROM edition ORDER BY idEdition ASC";
            $result = mysqli_query($link, $requete);
            echo "<form action='menuEdition.php' method='post'>";
            if (mysqli_num_rows($result) > 0) {
                echo "<div class='slide slideAdmin'>";
                echo "<div style='margin:auto'>";
                echo "<button type='submit' name='nouvelleEdition' class='boutonSansFond boutonSelectAdmin' style='background: " . $couleur2 . ";left: 5%;top: 110px;'>";
                echo "<i class='fa fa-plus iconePlusAdmin' style='color: " . $couleur1 . ";'></i>";
                echo "</button>";
                echo "</div>";
                $i = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($i == 3) {
                        $i = 1;
                        echo "</div>";
                        echo "<div class='slide slideAdmin'>";
                    } else {
                        $i++;
                    } ?>
                    <div style='margin:auto'>
                        <div class='boutonSelectAdmin boutonSansFond' style='text-align:center;background: <?php echo $couleur2 ?>;'>
                            <div class='containerBoutonAdmin'>
                                <button type='submit' name='modifier' value='<?php echo htmlspecialchars($row['idEdition'], ENT_QUOTES, 'UTF-8') ?>' class="boutonSansFond" style='color: <?php echo $couleur1 ?>;'>
                                    <i class='fa fa-edit'></i>
                                </button>
                                <button type='submit' class='supprimer boutonSansFond' name='supprimer' value='<?php echo htmlspecialchars($row['idEdition'], ENT_QUOTES, 'UTF-8') ?>' class="supprimer boutonSansFond" style='color: <?php echo $couleur1 ?>;'>
                                    <i class='fa fa-trash'></i>
                                </button>
                            </div>
                            <button type='submit' name='choixEdition' class='ecritureAdmin boutonSansFond tailleComplete' style='position:relative;top:0px;color: <?php echo $couleur4 ?>;' value='<?php echo htmlspecialchars($row['idEdition'], ENT_QUOTES, 'UTF-8') ?>'>
                                <?php $nomEdition = $row['idEdition'];
                                echo $nomEdition;
                                ?>
                            </button>
                        </div>
                    </div>
            <?php }
            } else {
                echo "<div style='margin:auto'>";
                echo "<button type='submit' name='nouvelleEdition' class='boutonSansFond boutonSelectAdmin' style='background: " . $couleur2 . ";left: 5%;top: 110px;'>";
                echo "<i class='fa fa-plus iconePlusAdmin' style='color: " . $couleur1 . ";'></i>";
                echo "</button>";
                echo "</div>";
            } ?>
            </form>
        </div>

        <a class="prev" onclick="plusSlides(-1)">❮</a>
        <a class="next" onclick="plusSlides(1)">❯</a>
    </div>
</div>

<!-- insertion du footer qui comprend la question des cookies -->
<?php include("../include/footer.php"); ?>

<!-- insertion du script pour les fonctions du carrousel -->
<script type="text/javascript">
    document.querySelectorAll(".boutonSelectAdmin").forEach(element => {
        element.addEventListener("mouseenter", function(event) {
            let childrens = event.target.children
            for (let i = 0; i < childrens.length; i++) {
                if (childrens[i].classList.contains("containerBoutonAdmin")) {
                    childrens[i].style.display = 'block';
                }
            }
        });

        element.addEventListener("mouseleave", function(event) {
            let childrens = event.target.children
            for (let i = 0; i < childrens.length; i++) {
                if (childrens[i].classList.contains("containerBoutonAdmin")) {
                    childrens[i].style.display = 'none';
                }
            }
        });
    });


    let slideIndex = 1;
    showSlides(slideIndex);

    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    function showSlides(n) {
        let i;
        let slides = document.getElementsByClassName("slide");
        if (n > slides.length) {
            slideIndex = 1
        }
        if (n < 1) {
            slideIndex = slides.length
        }
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slides[slideIndex - 1].style.display = "flex";
    }
</script>