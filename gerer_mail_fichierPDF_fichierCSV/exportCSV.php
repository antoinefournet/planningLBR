<?php session_start();

if (isset($_SESSION['listePersonnes'])) {
    $data = $_SESSION['listePersonnes'];
    $filename = 'listePersonnes';
    $delimiter = ';';
    $enclosure = '"';

    header("Content-disposition: attachment; filename=$filename.csv");
    header("Content-Type: text/csv");

    $fp = fopen("php://output", 'w');
    fputs($fp, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
    fputcsv($fp, array_keys($data[0]), $delimiter, $enclosure);
    foreach ($data as $fields) {
        fputcsv($fp, $fields, $delimiter, $enclosure);
    }
    fclose($fp);
    unset($_SESSION['listePersonnes']);
    die();
} else if (isset($_SESSION['modeleVide'])) {
    $data = array();
    $ligne1 = array();
    $ligne1['NOM'] = "Dupont";
    $ligne1['PRENOM'] = "Dupont";
    $ligne1['MAIL'] = "dupont.dupont@gmail.com";
    $ligne1['TELEPHONE'] = '="0101010101"';
    $ligne1['INFOS'] = "informations";
    $ligne1['DISPOJ1'] = "00:00:00";
    $ligne1['HEUREAFAIRE'] = "0";
    $ligne1['TYPE'] = "Membre";
    array_push($data, $ligne1);

    $ligne2 = array();
    $ligne2['NOM'] = "Dupond";
    $ligne2['PRENOM'] = "Dupond";
    $ligne2['MAIL'] = "dupond.dupond@gmail.com";
    $ligne2['TELEPHONE'] = '="0202020202"';
    $ligne2['INFOS'] = "informations";
    $ligne2['DISPOJ1'] = "00:00:00";
    $ligne2['HEUREAFAIRE'] = "1";
    $ligne2['TYPE'] = "Benevole";
    array_push($data, $ligne2);

    $filename = 'modeleCSV';
    $delimiter = ';';
    $enclosure = '"';

    header("Content-disposition: attachment; filename=$filename.csv");
    header("Content-Type: text/csv");

    $fp = fopen("php://output", 'w');
    fputs($fp, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
    fputcsv($fp, array_keys($data[0]), $delimiter, $enclosure);
    foreach ($data as $fields) {
        fputcsv($fp, $fields, $delimiter, $enclosure);
    }
    fclose($fp);
    unset($_SESSION['modeleVide']);
    die();
} else {
    include("../include/header.php"); ?>

    <div class='arrierePlanRectangle' style=" background-color: <?php echo $couleur7 ?>;">
        <h1 class="titrePrincipal">Exportation en CSV</h1>
        <div class='rectangleJaune' style='background-color: <?php echo $couleur4 ?>; color: <?php echo $couleur7 ?>'>
            <div class="positionTxtPrincipal">
                <p class="sousTitreCentre sousTitre">Oups, une erreur s'est produite. </p>
                <div class="PetitTxtEtBouton">
                    <p> Le fichier CSV n'a pas pu être créé.</p>
                    <a href='../personnes/listePersonnes.php' class='boutonRouge aSansFond' style='background-color: <?php echo $couleur2 ?>; color : <?php echo $couleur4 ?>;'>Retour</a>
                </div>
            </div>
        </div>
    </div>

<?php }
include("../include/footer.php"); ?>