<?php include("../include/header.php");

if (!isset($_SESSION['username'])) {
    $_SESSION['error'] = "Vous avez été déconnecté.";
    header('location:../accueil.php');
    die();
}

if (isset($_POST['plusPersonne'])) {

    header('location:../edition/modifierEditionPersonne.php');
    die();
}
if (isset($_POST['supprimer'])) {
    $_SESSION['personneASupprimer'] = $_POST['supprimer'];
    $requete6 = "SELECT * FROM personne WHERE idPersonne='" . $_SESSION['personneASupprimer'] . "'";
    $result6 = mysqli_query($link, $requete6);
    if (mysqli_num_rows($result6) == 1) {
        $row6 = mysqli_fetch_assoc($result6); ?>
        <form action='listePersonnes.php' method='post'>
            <div id='alertSuppression' style='background-color: <?php echo $couleur7 ?>;'>
                <p style='text-align:center;'>Veux-tu vraiment supprimer la personne <?php echo $row6['nom'] . " " . $row6['prenom']; ?> ?</p>
                <input id='toutes' name='toutes' type='submit' value='Oui, supprimer de toutes les éditions' class='boutonConfirmSuppr' style='color: <?php echo $couleur4 ?>; border: 2px solid <?php echo $couleur2 ?>;margin: 4%;width: 90%;text-transform: none;'>
                <input id='seule' name='seule' type='submit' value='Oui, supprimer seulement dans cette édition' class='boutonConfirmSuppr' style='color: <?php echo $couleur4 ?>; border: 2px solid <?php echo $couleur2 ?>;margin: 4% 1%;width: 96%;text-transform: none;'>
                <input id='non' name='non' type='submit' value='Non' class='boutonConfirmSuppr' style='color: <?php echo $couleur4 ?>;border: 2px solid <?php echo $couleur2 ?>;margin: 4% 38%;text-transform:none;'>
            </div>
        </form>
<?php
    } else {
        $_SESSION['error'] = "Nous n'avons pas pu récupérer les informations dans la base de données.";
        header('location:listePersonnes.php');
        die();
    }
}

if (isset($_POST['toutes'])) {
    $erreur = false;
    $requete2 = "SELECT * FROM personne WHERE idPersonne='" . $_SESSION['personneASupprimer'] . "'";
    $result2 = mysqli_query($link, $requete2);
    if (mysqli_num_rows($result2) == 1) {
        $row2 = mysqli_fetch_assoc($result2);
        $requete3 = "DELETE FROM personne WHERE idPersonne='" . $_SESSION['personneASupprimer'] . "'";
        $result3 = mysqli_query($link, $requete3);
        if ($result3 == true) {
            $requete4 = "DELETE FROM travailler WHERE idPersonne='" . $_SESSION['personneASupprimer'] . "'";
            $result4 = mysqli_query($link, $requete4);
            if ($result4 == true) {
                $requete5 = "DELETE FROM participer WHERE idPersonne='" . $_SESSION['personneASupprimer'] . "'";
                $result5 = mysqli_query($link, $requete5);
                if ($result5 == true) {
                    $_SESSION['success'] = "La personne " . $row2['nom'] . " " . $row2['prenom'] . " a bien été supprimé.";
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
        $_SESSION['error'] = "Les informations n'ont pas pu être entré dans la base de données.";
    }
    unset($_SESSION['personneASupprimer']);
    header('location:listePersonnes.php');
    die();
}
if (isset($_POST['seule'])) {
    $erreur = false;
    $requete2 = "SELECT * FROM personne WHERE idPersonne='" . $_SESSION['personneASupprimer'] . "'";
    $result2 = mysqli_query($link, $requete2);
    if (mysqli_num_rows($result2) == 1) {
        $requete4 = "DELETE FROM travailler WHERE idPersonne='" . $_SESSION['personneASupprimer'] . "' AND idCreneau IN(SELECT idCreneau WHERE idEdition='" . $_SESSION['edition'] . "')";
        $result4 = mysqli_query($link, $requete4);
        if ($result4 == true) {
            $requete5 = "DELETE FROM participer WHERE idPersonne='" . $_SESSION['personneASupprimer'] . "' AND idEdition='" . $_SESSION['edition'] . "'";
            $result5 = mysqli_query($link, $requete5);
            if ($result5 == true) {
                $_SESSION['success'] = "La personne " . $row2['nom'] . " " . $row2['prenom'] . " a bien été supprimé.";
            } else {
                $erreur = true;
            }
        } else {
            $erreur = true;
        }
    }

    if ($erreur == true) {
        $_SESSION['error'] = "Les informations n'ont pas pu être entré dans la base de données.";
    }
    unset($_SESSION['personneASupprimer']);
    header('location:listePersonnes.php');
    die();
}
if (isset($_POST['non'])) {
    unset($_SESSION['personneASupprimer']);
    header('location:listePersonnes.php');
    die();
}
if (isset($_POST['listePersonnes'])) {
    header('location:../gerer_mail_fichierPDF_fichierCSV/exportCSV.php');
    die();
}
if (isset($_POST['versPersonne'])) {
    $_SESSION['personne'] = $_POST['versPersonne'];
    header('location:../planning/planningPersonne.php');
    die();
}
?>

<style>
    ::placeholder {
        color: <?php echo $couleur7 ?>;
        opacity: 1;
    }

    #myInput {
        width: 40%;
        font-size: 16px;
        padding: 0.5% 2%;
        margin: 0 0.5%;
        border-radius: 30px;
        BORDER: none;
        background: rgba(255, 137, 10, .4);
    }

    .myUL {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .filterDiv {
        float: left;
        width: 14%;
        text-align: center;
        margin: 1%;
        display: block;
    }

    .container {
        margin: 3% auto;
        overflow: hidden;
    }

    /* Style the buttons */
    .btn {
        border: none;
        outline: none;
        cursor: pointer;
        border-radius: 30px;
        width: 10%;
        margin: 0 0.5%;
        text-transform: uppercase;
        color: <?php echo $couleur4 ?>;
        font-weight: 550;
    }

    .btn:hover {
        text-decoration: wavy underline;

    }

    .btn.active {
        text-decoration: underline;
    }

    .benevole {
        background-color: <?php echo $couleur2 ?>;
    }

    .membre {
        background-color: <?php echo $couleur3 ?>;
    }
</style>

<div class="arrierePlanRectangle">
    <h1 class="titrePrincipal">LISTE DES PERSONNES</h1>

    <div class="rectangleJaune" style="background-color: <?php echo $couleur4 ?>; color: <?php echo $couleur7 ?>;">
        <form action="listePersonnes.php" method="post">
            <div id="myBtnContainer" style="margin:2%;">

                <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Rechercher des noms">

                <?php if (isset($_POST['afficheMembre'])) { ?>
                    <button class="btn" style="background: rgba(234, 0, 28, .4);">Tous</button>
                    <button class="btn active" type='submit' name='afficheMembre' style="background-color: <?php echo $couleur3 ?>;">Membres</button>
                    <button class="btn" type='submit' name='afficheBenevole' style="background-color: <?php echo $couleur2 ?>;">Bénévoles</button>
                    <button class="btn" type='submit' name='afficheBenevoleDispo' style="background-color: <?php echo $couleur2 ?>;width:15%">Bénévoles Dispos</button>
                <?php } else if (isset($_POST['afficheBenevole'])) { ?>
                    <button class="btn" style="background: rgba(234, 0, 28, .4);">Tous</button>
                    <button class="btn" type='submit' name='afficheMembre' style="background-color: <?php echo $couleur3 ?>;">Membres</button>
                    <button class="btn active" type='submit' name='afficheBenevole' style="background-color: <?php echo $couleur2 ?>;">Bénévoles</button>
                    <button class="btn" type='submit' name='afficheBenevoleDispo' style="background-color: <?php echo $couleur2 ?>;width:15%">Bénévoles Dispos</button>
                <?php } else if (isset($_POST['afficheBenevoleDispo'])) { ?>
                    <button class="btn" style="background: rgba(234, 0, 28, .4);">Tous</button>
                    <button class="btn" type='submit' name='afficheMembre' style="background-color: <?php echo $couleur3 ?>;">Membres</button>
                    <button class="btn" type='submit' name='afficheBenevole' style="background-color: <?php echo $couleur2 ?>;">Bénévoles</button>
                    <button class="btn active" type='submit' name='afficheBenevoleDispo' style="background-color: <?php echo $couleur2 ?>;width:15%">Bénévoles Dispos</button>
                <?php } else { ?>
                    <button class="btn active" style="background: rgba(234, 0, 28, .4);">Tous</button>
                    <button class="btn" type='submit' name='afficheMembre' style="background-color: <?php echo $couleur3 ?>;">Membres</button>
                    <button class="btn" type='submit' name='afficheBenevole' style="background-color: <?php echo $couleur2 ?>;">Bénévoles</button>
                    <button class="btn" type='submit' name='afficheBenevoleDispo' style="background-color: <?php echo $couleur2 ?>;width:15%">Bénévoles Dispos</button>
                <?php } ?>
            </div>
            <ul id="myUL" class="myUL container">
                <div class="slide">
                    <li class="filterDiv">
                        <button type='submit' name='plusPersonne' class="boutonSansFond tailleComplete" style="margin:0;padding: 0;display: block; background-color: <?php echo $couleur2 ?>;">
                            <i class='fa fa-plus' style='color: <?php echo $couleur1 ?>;font-size: 119px;margin: 5% 3% 2% 3%;'></i>
                        </button>
                    </li>
                    <?php
                    $listePersonnes = array();
                    $requete1 = "SELECT * FROM personne WHERE idPersonne IN(SELECT idPersonne FROM participer WHERE idEdition='" . $_SESSION['edition'] . "') order by typePersonne DESC, nom ASC, prenom ASC";
                    $result1 = mysqli_query($link, $requete1);
                    $compteur = 1;
                    if (mysqli_num_rows($result1) > 0) {
                        $lignePersonne = array();
                        while ($row1 = mysqli_fetch_assoc($result1)) {
                            $idPersonne = $row1['idPersonne'];
                            $requete7 = "SELECT * FROM participer WHERE idEdition='" . $_SESSION['edition'] . "' AND idPersonne='" . $idPersonne . "'";
                            $result7 = mysqli_query($link, $requete7);
                            if (mysqli_num_rows($result7) == 1) {
                                $row7 = mysqli_fetch_assoc($result7);
                                $lignePersonne['NOM'] = $row1['nom'];
                                $lignePersonne['PRENOM'] = $row1['prenom'];
                                $lignePersonne['MAIL'] = $row1['mail'];
                                $lignePersonne['TELEPHONE'] = '="' . $row1['telephone'] . '"';
                                $lignePersonne['INFOS'] = $row1['infoSupplementaires'];
                                $lignePersonne['DISPOJ1'] = $row7['heureDispoJ1'];
                                $lignePersonne['HEUREAFAIRE'] = $row7['nbHeureAFaire'];
                                $lignePersonne['TYPE'] = $row1['typePersonne'];
                                array_push($listePersonnes, $lignePersonne);
                                if (isset($_POST['afficheMembre'])) {
                                    if ($compteur % 12 == 0 && $compteur != 0) {
                                        echo "</div>";
                                        echo "<div class='slide'>";
                                    }
                                    if ($row1['typePersonne'] == "Membre") { ?>
                                        <li class="filterDiv membre">
                                            <button type='submit' name='supprimer' value='<?php echo $row1['idPersonne']; ?>' class="fa fa-trash boutonSansFond" style="display:flex;color :<?php echo $couleur4 ?>;"></button>
                                            <button type='submit' name='versPersonne' class="boutonSansFond tailleComplete" value='<?php echo $idPersonne ?>' style="text-decoration:none;margin:0;padding: 5px 5px 12px 5px;display: block">
                                                <p class='fiche' style="text-transform: uppercase;color: <?php echo $couleur4 ?>;font-weight: 550;margin:0;"> <?php echo $row1['nom'] ?></p>
                                                <p class='fiche' style="color: <?php echo $couleur7 ?>;MARGIN:0"> <?php echo $row1['prenom'] ?> </p>
                                                <p class='fiche' style="color: <?php echo $couleur7 ?>;MARGIN:0"> <?php echo $row1['telephone'] ?> </p>
                                                <br>
                                            </button>
                                        </li>
                                    <?php $compteur++;
                                    }
                                } else if (isset($_POST['afficheBenevole'])) {
                                    if ($compteur % 12 == 0 && $compteur != 0) {
                                        echo "</div>";
                                        echo "<div class='slide'>";
                                    }
                                    if ($row1['typePersonne'] == "Benevole") { ?>
                                        <li class="filterDiv benevole">
                                            <button type='submit' name='supprimer' value='<?php echo $row1['idPersonne']; ?>' class="fa fa-trash boutonSansFond" style="display:flex;color :<?php echo $couleur4 ?>;"></button>
                                            <button type='submit' name='versPersonne' class="boutonSansFond tailleComplete" value='<?php echo $idPersonne ?>' style="text-decoration:none;margin:0;padding: 5px 5px 12px 5px;display: block;height:100%">
                                                <p class='fiche' style="text-transform: uppercase;color: <?php echo $couleur4 ?>;font-weight: 550;margin:0;"> <?php echo $row1['nom'] ?></p>
                                                <p class='fiche' style="color: <?php echo $couleur7 ?>;MARGIN:0"> <?php echo $row1['prenom'] ?> </p>
                                                <p class='fiche' style="color: <?php echo $couleur7 ?>;MARGIN:0"> <?php echo $row1['telephone'] ?> </p>
                                                <?php if (intval($row7['nbHeureAFaire'] - $row7['nbHeurePlanifiee']) > 0) { ?>
                                                    <p style="color: <?php echo $couleur7 ?>;MARGIN:0"> Reste <span style="color: <?php echo $couleur4 ?>"><?php echo intval($row7['nbHeureAFaire'] - $row7['nbHeurePlanifiee']) ?>h</span> à faire </p>
                                                <?php } else { ?>
                                                    <br>
                                                <?php } ?>
                                            </button>
                                        </li>
                                        <?php $compteur++;
                                    }
                                } else if (isset($_POST['afficheBenevoleDispo'])) {
                                    if ($compteur % 12 == 0 && $compteur != 0) {
                                        echo "</div>";
                                        echo "<div class='slide'>";
                                    }
                                    if ($row1['typePersonne'] == "Benevole") {
                                        if (intval($row7['nbHeureAFaire'] - $row7['nbHeurePlanifiee']) > 0) { ?>
                                            <li class="filterDiv benevole">
                                                <button type='submit' name='supprimer' value='<?php echo $row1['idPersonne']; ?>' class="fa fa-trash boutonSansFond" style="display:flex;color :<?php echo $couleur4 ?>;"></button>
                                                <button type='submit' name='versPersonne' class="boutonSansFond tailleComplete" value='<?php echo $idPersonne ?>' style="text-decoration:none;margin:0;padding: 5px 5px 12px 5px;display: block;height:100%">
                                                    <p class='fiche' style="text-transform: uppercase;color: <?php echo $couleur4 ?>;font-weight: 550;margin:0;"> <?php echo $row1['nom'] ?></p>
                                                    <p class='fiche' style="color: <?php echo $couleur7 ?>;MARGIN:0"> <?php echo $row1['prenom'] ?> </p>
                                                    <p class='fiche' style="color: <?php echo $couleur7 ?>;MARGIN:0"> <?php echo $row1['telephone'] ?> </p>
                                                    <p class='fiche' style="color: <?php echo $couleur7 ?>;MARGIN:0"> Reste <span style="color: <?php echo $couleur4 ?>"><?php echo intval($row1['nbHeureAFaire'] - $row1['nbHeurePlanifiee']) ?>h</span> à faire </p>
                                                </button>
                                            </li>
                                        <?php $compteur++;
                                        }
                                    }
                                } else {
                                    if ($compteur % 12 == 0 && $compteur != 0) {
                                        echo "</div>";
                                        echo "<div class='slide'>";
                                    }
                                    if ($row1['typePersonne'] == "Benevole") { ?>
                                        <li class="filterDiv benevole">
                                            <button type='submit' name='supprimer' value='<?php echo $row1['idPersonne']; ?>' class="fa fa-trash boutonSansFond" style="display:flex;color :<?php echo $couleur4 ?>;"></button>
                                            <button type='submit' name='versPersonne' class="boutonSansFond tailleComplete" value='<?php echo $idPersonne ?>' style="text-decoration:none;margin:0;padding: 5px 5px 12px 5px;display: block;height:100%">
                                                <p class='fiche' style="text-transform: uppercase;color: <?php echo $couleur4 ?>;font-weight: 550;margin:0;"> <?php echo $row1['nom'] ?></p>
                                                <p class='fiche' style="color: <?php echo $couleur7 ?>;MARGIN:0"> <?php echo $row1['prenom'] ?> </p>
                                                <p class='fiche' style="color: <?php echo $couleur7 ?>;MARGIN:0"> <?php echo $row1['telephone'] ?> </p>
                                                <?php if (intval($row7['nbHeureAFaire'] - $row7['nbHeurePlanifiee']) > 0) { ?>
                                                    <p style="color: <?php echo $couleur7 ?>; MARGIN:0"> Reste <span style="color: <?php echo $couleur4 ?>"><?php echo intval($row7['nbHeureAFaire'] - $row7['nbHeurePlanifiee']) ?>h</span> à faire </p>
                                                <?php } else { ?>
                                                    <br>
                                                <?php } ?>
                                            </button>
                                        </li>
                                    <?php } else { ?>
                                        <li class="filterDiv membre">
                                            <button type='submit' name='supprimer' value='<?php echo $row1['idPersonne']; ?>' class="fa fa-trash boutonSansFond" style="display:flex;color :<?php echo $couleur4 ?>;"></button>
                                            <button type='submit' name='versPersonne' class="boutonSansFond tailleComplete" value='<?php echo $idPersonne ?>' style="text-decoration:none;margin:0;padding: 5px 5px 12px 5px;display: block">
                                                <p class='fiche' style="text-transform: uppercase;color: <?php echo $couleur4 ?>;font-weight: 550;margin:0;"> <?php echo $row1['nom'] ?></p>
                                                <p class='fiche' style="color: <?php echo $couleur7 ?>;MARGIN:0"> <?php echo $row1['prenom'] ?> </p>
                                                <p class='fiche' style="color: <?php echo $couleur7 ?>;MARGIN:0"> <?php echo $row1['telephone'] ?> </p>
                                                <br>
                                            </button>
                                        </li>
                    <?php }
                                    $compteur++;
                                }
                            } else {
                                $erreur = true;
                                $_SESSION['error'] = "Les informations n'ont pas pu être récupéré de la base de données.";
                            }
                        }
                    } ?>

                    <?php $_SESSION['listePersonnes'] = $listePersonnes; ?>
                </div>
            </ul>
    </div>
    <button type='submit' name='listePersonnes' style="background: none; border: none; font-weight: 780;font-size: 18px;color:<?php echo $couleur4 ?>; text-shadow: -5px 8px 6px rgba(234, 0, 28, 0.72);text-transform:uppercase;margin: 0 0.5%;position:absolute;margin: 32% 5%; left: 5%;">Télécharger la liste</button>
    </form>
</div>
</div>

<!-- Bouttons flèches-->
<a class="prev" onclick="plusSlides(-1)"><i class="fa fa-chevron-left"></i></a>
<a class="next" onclick="plusSlides(1)"><i class="fa fa-chevron-right"></i></a>
</div>


<script>
    // Add active class to the current button (highlight it)
    let btnContainer = document.getElementById("myBtnContainer");
    let btns = btnContainer.getElementsByClassName("btn");
    for (let i = 0; i < btns.length; i++) {
        btns[i].addEventListener("click", function() {
            let current = document.getElementsByClassName("active");
            current[0].className = current[0].className.replace(" active", "");
            this.className += " active";
        });
    }
</script>

<!-- Script GRAND carrousel -->
<script>
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
        slides[slideIndex - 1].style.display = "block";
    }
</script>

<script>
    function myFunction() {
        let input, filter, ul, li, a, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        ul = document.getElementById("myUL");
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            a = li[i].getElementsByClassName("fiche")[0];
            txtValue = a.textContent || a.innerText;
            console.log(a);
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "block";
            } else {
                li[i].style.display = "none";
            }
        }
    }
</script>

<?php include("../include/footer.php"); ?>