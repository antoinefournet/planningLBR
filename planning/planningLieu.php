<?php include("../include/header.php");

if(!isset($_SESSION['username']))
{
    $_SESSION['error'] = "Vous avez été déconnecté.";
    header('location:../accueil.php');
    die();
} ?>

<style>
    table {
        border-collapse: collapse;
        border: 1px solid <?php echo $couleur7 ?>;
        background-color: <?php echo $couleur4 ?>;
        color: <?php echo $couleur7 ?>;
    }

    th,
    td {
        border: 2px solid <?php echo $couleur7 ?>;
        text-align: center;
        padding: 6px;
    }

    .tabTitre {
        background-color: <?php echo $couleur2 ?>;
        color: <?php echo $couleur4 ?>;
        position: sticky;
        border: 2px solid <?php echo $couleur7 ?>;
    }
</style>

<style>
    .lieuDeroulantBtn {
        cursor: pointer;
    }

    .lieuDeroulant {
        position: relative;
        display: inline-block;
    }

    .lieuDeroulant-content {
        display: none;
        position: absolute;
        overflow: auto;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    .lieuDeroulant-content a {
        text-decoration: none;
        display: block;
    }

    .onAffiche {
        display: block;
    }
</style>

<?php
if (isset($_POST['ajoutCreneauLieu'])) {
    $_SESSION['lieuAModifier'] = $_POST['ajoutCreneauLieu'];
    header('location:../edition/modifierLieu.php');
    die();
}

if (isset($_POST['versPersonne'])) {
    $_SESSION['personne'] = $_POST['versPersonne'];
    header('location:planningPersonne.php');
    die();
}

if (isset($_POST['telechargerPDFLieu'])) {
    header('location:../gerer_mail_fichierPDF_fichierCSV/exportPDF2.php');
    die();
} else {
    unset($_SESSION['planningPDFLieu']);
}
?>

<div class="arrierePlanRectangle">
    <?php include("tabLieu.php") ?>


    <script>
        function ouverture() {
            document.getElementById("lieuxQuiDeroulent").classList.toggle("onAffiche");
        }

        window.onclick = function(event) {
            if (!event.target.matches('.lieuDeroulantBtn')) {
                let dropdowns = document.getElementsByClassName("lieuDeroulant-content");
                let i;
                for (i = 0; i < dropdowns.length; i++) {
                    let openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('onAffiche')) {
                        openDropdown.classList.remove('onAffiche');
                    }
                }
            }
        }
    </script>

    <?php include("../include/footer.php"); ?>