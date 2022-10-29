<!--                                * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *             Fichier PHP             *
                                    *          Générer les 3 PDF          *
                                    *                                     *
                                    * * * * * * * * * * * * * * * * * * * *    
-->

<?php session_start();
include('../include/connexionBDD.php');
include('../include/variablesGlobales.php');
ob_start();
require('../librairies/FPDF/fpdf.php');

/* fonction pour convertir une couleur en hexa vers RVB */
function hex2rgb($hex)
{
    $hex = str_replace("#", "", $hex);
    if (strlen($hex) == 3) {
        $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    $rgb = array($r, $g, $b);
    return $rgb;
}

/* classe pour générer chacun des PDF */
class PDF extends FPDF
{
    public $y;

    function footer()
    {
        $this->AliasNbPages();
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    /* remplir pour remplir le PDF du planning d'une personne */
    function remplirTabPersonne($header, $header2, $data, $link, $annee)
    {
        /* palette de couleur */
        $rouge = hex2rgb('#EA001C');
        $jaune = hex2rgb('#FFFEE6');
        $bleuClair = hex2rgb('#BDE4FC');
        $noir = hex2rgb('#161920');

        /* calcul du nombre de page */
        $nombrePage = 0;
        for ($i = 1; $i < count($header); $i++) {
            if (($i - 1) % 3 == 0) {
                $nombrePage++;
            }
        }

        /* parcours de chaque page */
        for ($numero = 0; $numero < $nombrePage; $numero++) {

            /* paramètres initiaux du PDF */
            $this->SetMargins(10, 40, 5);
            $this->SetFont('Arial', '', 14);

            /* Couleurs, épaisseur du trait et police grasse */
            $this->SetFillColor($rouge[0], $rouge[1], $rouge[2]);
            $this->SetTextColor($noir[0], $noir[1], $noir[2]);
            $this->SetLineWidth(.3);
            $this->SetFont('', 'B', 14);

            /* création d'une nouvelle page */
            $this->AddPage();

            /* en tête du PDF avec images et phrases */
            $this->remplirEntetePersonne($link, $annee);

            /* cellule vide en haut à gauche du PDF */
            $this->Cell(25, 7, '', 1, 0, 'C', true);

            /* si ce n'est pas la dernière page */
            if ($numero != $nombrePage) {

                /* parcours des 3 jours à afficher en une page */
                for ($i = 1; $i < 4; $i++) {

                    /* si cela correspond à la bonne date */
                    if ($i + ($numero * 3) < (count($header))) {

                        /* mise en forme de la date */
                        $explode = explode(' ', $header[$i + ($numero * 3)]);
                        $mois = 0;
                        switch ($explode[2]) {
                            case "Janvier":
                                $mois = '01';
                                break;
                            case "Février":
                                $mois = '02';
                                break;
                            case "Mars":
                                $mois = '03';
                                break;
                            case "Avril":
                                $mois = "04";
                                break;
                            case "Mai":
                                $mois = "05";
                                break;
                            case "Juin":
                                $mois = "06";
                                break;
                            case "Juillet":
                                $mois = "07";
                                break;
                            case "Août":
                                $mois = "08";
                                break;
                            case "Septembre":
                                $mois = "09";
                                break;
                            case "Octobre":
                                $mois = "10";
                                break;
                            case "Novembre":
                                $mois = "11";
                                break;
                            case "Décembre":
                                $mois = "12";
                                break;
                        }

                        /* affichage de la cellule */
                        $this->Cell(52, 7, $explode[0] . ' ' . $explode[1] . '/' . $mois, 1, 0, 'C', true);
                    }
                }
            }

            /* retour à la ligne */
            $this->Ln();

            /* remplissage du tableau avec les données */
            $fill = true;
            $i = 0;

            /* parcours des données */
            foreach ($data as $row) {

                /* paramètres des cellules titre contenant l'heure */
                $this->SetFillColor($rouge[0], $rouge[1], $rouge[2]);
                $this->SetTextColor($noir[0], $noir[1], $noir[2]);
                $this->SetLineWidth(.3);
                $this->SetFont('', '', 12);
                $this->Cell(25, 7, $header2[$i] . '00', 'LRTB', 0, 'C', true);

                /* parcours des 3 jours présents en une page */
                for ($k = 1; $k < 4; $k++) {

                    /* si cela correspond à la bonne date */
                    if ($k + ($numero * 3) < (count($header))) {

                        /* mise en forme des cellules de l'intérieur du tableau */
                        $this->SetTextColor($noir[0], $noir[1], $noir[2]);
                        $this->SetFont('', 'B', 12);

                        /* mise en forme des cellules vides */
                        if ($row[$header[$k + ($numero * 3)]] == 'Indisponible') {
                            $row[$header[$k + ($numero * 3)]] = '';
                            $this->SetFillColor($bleuClair[0], $bleuClair[1], $bleuClair[2]);
                            $this->Cell(52, 6, utf8_decode($row[$header[$k + ($numero * 3)]]), 'LRTB', 0, 'C', $fill);
                        }

                        /* cellules vides quand la personne n'est pas dispo */ else if ($row[$header[$k + ($numero * 3)]] == 'Pause' || $row[$header[$k + ($numero * 3)]] == 'AucunCreneau') {
                            $row[$header[$k + ($numero * 3)]] = '';
                            $this->SetFillColor($jaune[0], $jaune[1], $jaune[2]);
                            $this->Cell(52, 6, utf8_decode($row[$header[$k + ($numero * 3)]]), 'LRTB', 0, 'C', $fill);
                        }

                        /* mise en forme des cellules avec donnée */ else {

                            /* requête pour trouver la couleur associée au lieu que l'on veut afficher */
                            $requete1 = "SELECT * FROM lieu WHERE nom='" . $row[$header[$k + ($numero * 3)]] . "'";
                            $result1 = mysqli_query($link, $requete1);
                            if (mysqli_num_rows($result1) == 1) {
                                $row1 = mysqli_fetch_assoc($result1);
                                $couleur = hex2rgb($row1['idCouleur']);
                                $this->SetFillColor($couleur[0], $couleur[1], $couleur[2]);
                                $this->Cell(52, 6, utf8_decode($row[$header[$k + ($numero * 3)]]), 'LRTB', 0, 'C', $fill);
                            }
                        }
                    }
                }

                /* retour à la ligne */
                $this->Ln();
                $i++;
            }

            /* bas de page avec les phrases */
            $this->remplirBasPersonne();
        }
    }

    /* méthode pour l'entête du PDF Planning Personne */
    function remplirEntetePersonne($link, $annee)
    {
        /* logo */
        $this->Image("../images/logoLong.png", 60, 0, 90, 30);

        /* phrase titre avec l'année de l'édition et le nom de la personne */
        $requete1 = "SELECT * FROM personne WHERE idPersonne='" . $_SESSION['personne'] . "'";
        $result1 = mysqli_query($link, $requete1);
        if (mysqli_num_rows($result1) == 1) {
            $row1 = mysqli_fetch_assoc($result1);
            $text = stripslashes("Planning LBR " . $annee[3] . " de " . $row1['nom'] . " " . $row1['prenom']);
            $text = iconv('UTF-8', 'windows-1252', $text);
            $this->SetFont('Arial', 'B', 18);
            $this->Cell(0, 5, $text, 0, 0, 'C');
            $this->Write(7, "\n\n");
            $this->Ln();
            $this->Ln();
        }
    }

    /* méthode pour le bas de page du PDF Planning Personne */
    function remplirBasPersonne()
    {
        $this->Write(3, "\n");
        $this->Ln();

        /* texte demandé par le client */
        $text = stripslashes("\nNB : L’heure sur le planning correspond à l’heure de début de service. Merci d’arriver 5 minutes avant.\n");
        $text = iconv('UTF-8', 'windows-1252', $text);
        $this->Write(8, $text);

        /* affichage de l'heure où le pDF a été généré */
        $currentDateTime = date('Y-m-d H:i:s');
        $text2 = stripslashes("\nPlanning édité le : ");
        $text2 = iconv('UTF-8', 'windows-1252', $text2);
        $this->Write(5, $text2);
        $infoDatePDF = explode(' ', $currentDateTime);
        $datePDF = explode('-', $infoDatePDF[0]);
        $heurePDF = explode(':', $infoDatePDF[1]);
        $this->Write(5, $datePDF[2] . '/' . $datePDF[1] . '/' . $datePDF[0] . ' ' . utf8_decode('à') . ' ' . $heurePDF[0] . 'h' . $heurePDF[1]);
    }

    /* méthode pour remplir le PDF du planning Lieu */
    function remplirTabLieu($header, $header2, $data, $link, $annee)
    {
        /* palette de couleur */
        $rouge = hex2rgb('#EA001C');
        $orange = hex2rgb('#FF890A');
        $jaune = hex2rgb('#FFFEE6');
        $bleuClair = hex2rgb('#BDE4FC');
        $noir = hex2rgb('#161920');

        /* parcours de tous les jours de l'édition */
        for ($numero = 1; $numero < count($header); $numero++) {

            /* initilisation des paramètres du PDF */
            $this->SetMargins(10, 40, 5);
            $this->SetFont('Arial', '', 14);

            /* Couleurs, épaisseur du trait et police grasse */
            $this->SetFillColor($rouge[0], $rouge[1], $rouge[2]);
            $this->SetTextColor($noir[0], $noir[1], $noir[2]);
            $this->SetLineWidth(.3);
            $this->SetFont('', 'B', 14);

            /* nouvelle page */
            $this->AddPage();

            /* en tête du PDF */
            $this->remplirEnteteLieu($link, $annee);

            /* affichage de la cellule vide en haut à gauche */
            $this->Cell(40, 7, '', 1, 0, 'C', true);

            /* affichage de la date */
            $explode = explode(' ', $header[$numero]);
            $mois = 0;
            switch ($explode[2]) {
                case "Janvier":
                    $mois = '01';
                    break;
                case "Février":
                    $mois = '02';
                    break;
                case "Mars":
                    $mois = '03';
                    break;
                case "Avril":
                    $mois = "04";
                    break;
                case "Mai":
                    $mois = "05";
                    break;
                case "Juin":
                    $mois = "06";
                    break;
                case "Juillet":
                    $mois = "07";
                    break;
                case "Août":
                    $mois = "08";
                    break;
                case "Septembre":
                    $mois = "09";
                    break;
                case "Octobre":
                    $mois = "10";
                    break;
                case "Novembre":
                    $mois = "11";
                    break;
                case "Décembre":
                    $mois = "12";
                    break;
            }
            $this->Cell(130, 7, $explode[0] . ' ' . $explode[1] . '/' . $mois, 1, 0, 'C', true);

            /* partie contenant les données */
            $fill = true;
            $i = 0;
            $height = 7;
            $this->y = $this->GetY() + 7;

            /* parcours des données du tableau */
            foreach ($data as $row) {

                /* si cela correspond au bon jour */
                if (($numero) < (count($header))) {

                    /* si la cellule est vide */
                    if ($row[$header[($numero)]] == 'Pause' || $row[$header[($numero)]] == 'Indisponible' || $row[$header[($numero)]] == 'AucunCreneau') {
                        $row[$header[($numero)]] = '';
                        $aAfficher = '';

                        /* paramètres des cellules titres */
                        $this->SetFillColor($rouge[0], $rouge[1], $rouge[2]);
                        $this->SetTextColor($noir[0], $noir[1], $noir[2]);
                        $this->SetLineWidth(.3);
                        $this->SetFont('', 'B', 12);
                        $cell = utf8_decode($aAfficher);
                        $nbLines = substr_count($cell, "\n") + 1;
                        $entete = $header2[$i] . '00';
                        for ($m = 0; $m < $nbLines; $m++) {
                            $entete = $entete . "\n";
                        }
                        $this->SetXY(10, $this->y);
                        $this->MultiCell(40, 7, $header2[$i] . '00', 'LRTB', 0, 'C', true);

                        /* paramètres des cellules des données */
                        $this->SetTextColor($noir[0], $noir[1], $noir[2]);
                        $this->SetFont('', 'B', 12);
                        $this->SetFillColor($jaune[0], $jaune[1], $jaune[2]);
                        $this->SetXY(50, $this->y - 7 * $nbLines);
                        $this->MultiCell(130, 6, utf8_decode($aAfficher), 'LRTB', 0, 'C', $fill);
                    }

                    /* si la cellule est remplie de nom de personne */ else {
                        $case = explode("/", $row[$header[($numero)]]);
                        $compteur = 0;
                        $aAfficher = '';
                        /* tant qu'il exoste une personne après */
                        while (isset($case[$compteur + 1])) {

                            /* s'il existe une personne encore après dans la liste de ce créneau là */
                            if (isset($case[$compteur + 2])) {

                                /* PROBLEME le retour à la ligne ne fonctionne pas */
                                $aAfficher = $aAfficher . $case[$compteur] . "\n";
                            } else {
                                $aAfficher = $aAfficher . $case[$compteur];
                            }
                            $compteur++;
                        }
                        $this->SetFillColor($rouge[0], $rouge[1], $rouge[2]);
                        $this->SetTextColor($noir[0], $noir[1], $noir[2]);
                        $this->SetLineWidth(.3);
                        $this->SetFont('', 'B', 12);
                        $cell = utf8_decode($aAfficher);
                        $nbLines = substr_count($cell, "\n") + 1;
                        $entete = $header2[$i] . '00';
                        for ($m = 0; $m < $nbLines; $m++) {
                            $entete = $entete . "\n";
                        }
                        $this->SetXY(10, $this->y);
                        $this->MultiCell(40, $height,  $entete, 'LRTB', 0, 'C', $fill);
                        /* paramètres des cellules des données */
                        $this->SetTextColor($noir[0], $noir[1], $noir[2]);
                        $this->SetFont('', 'B', 12);
                        $this->SetFillColor($bleuClair[0], $bleuClair[1], $bleuClair[2]);
                        if ($aAfficher != '') {
                            /* changement de couleur si la case est pleine */
                            $this->SetFillColor($orange[0], $orange[1], $orange[2]);
                        }
                        $this->SetXY(50, $this->y - 7 * $nbLines);
                        $this->MultiCell(130, $height, $cell, 'LRTB', 0, 'C', $fill);
                    }
                    $this->y = $this->GetY();
                }
                $i++;
            }
        }
    }

    /* méthode pour remplir l'entête du PDF */
    function remplirEnteteLieu($link, $annee)
    {
        /* image */
        $this->Image("../images/logoLong.png", 60, 0, 90, 30);

        /* phrase contenant le nom du lieu */
        $requete1 = "SELECT * FROM lieu WHERE idLieu='" . $_SESSION['versLieu'] . "'";
        $result1 = mysqli_query($link, $requete1);
        if (mysqli_num_rows($result1) == 1) {
            $row1 = mysqli_fetch_assoc($result1);
            $text = stripslashes("Planning LBR " . $annee[3] . "  -  " . $row1['nom']);
            $text = iconv('UTF-8', 'windows-1252', $text);
            $this->SetFont('Arial', 'B', 18);
            $this->Cell(0, 5, $text, 0, 0, 'C');
            $this->Write(7, "\n");
            $this->Ln();
        }
    }

    /* méthode pour remplir le PDF Plannign Général */
    function remplirTabGeneral($header, $header2, $header3, $data, $link, $annee)
    {
        /* palette de couleur */
        $rouge = hex2rgb('#EA001C');
        $jaune = hex2rgb('#FFFEE6');
        $bleuClair = hex2rgb('#BDE4FC');
        $noir = hex2rgb('#161920');

        /* calcul du nombre de page pour afficher 70 personnes sur une page */
        $nombrePage = 0;
        for ($i = 0; $i < count($header2); $i++) {
            if (($i) % 70 == 0) {
                $nombrePage++;
            }
        }

        /* parcours de tous les jours de l'édition */
        for ($numero = 1; $numero < count($header); $numero++) {

            /* paramètres initiaux du PDF */
            $this->SetMargins(3, 3, 3);
            $this->SetFont('Arial', '', 10);

            /*ajout d'une page à l'horizontal */
            $this->AddPage('L');
            /* mise en forme de la date */
            $explode = explode(' ', $header[$numero]);
            $mois = 0;
            switch ($explode[2]) {
                case "Janvier":
                    $mois = '01';
                    break;
                case "Février":
                    $mois = '02';
                    break;
                case "Mars":
                    $mois = '03';
                    break;
                case "Avril":
                    $mois = "04";
                    break;
                case "Mai":
                    $mois = "05";
                    break;
                case "Juin":
                    $mois = "06";
                    break;
                case "Juillet":
                    $mois = "07";
                    break;
                case "Août":
                    $mois = "08";
                    break;
                case "Septembre":
                    $mois = "09";
                    break;
                case "Octobre":
                    $mois = "10";
                    break;
                case "Novembre":
                    $mois = "11";
                    break;
                case "Décembre":
                    $mois = "12";
                    break;
            }

            /* en tête du PDF avec la date du jour */
            $text = stripslashes("Planning LBR - " . $explode[0] . " " . $explode[1] . "/" . $mois . "/" . $explode[3]);
            $text = iconv('UTF-8', 'windows-1252', $text);
            $this->SetTextColor($noir[0], $noir[1], $noir[2]);
            $this->SetFont('Arial', 'B', 7);
            $this->Cell(0, 4, $text, 0, 0, 'C');
            $this->Write(5, "\n");
            $this->Ln();

            /* Couleurs, épaisseur du trait et police grasse */
            $this->SetFillColor($rouge[0], $rouge[1], $rouge[2]);
            $this->SetTextColor($noir[0], $noir[1], $noir[2]);
            $this->SetLineWidth(.3);
            $this->SetFont('', 'B', 6);

            /* affichage de toutes les heures sur la 1e ligne */
            $this->Cell(15, 4, 'Heures : ', 1, 0, 'C', true);
            for ($i = 1; $i < count($header3); $i++) {
                $this->Cell(11.5, 4, $header3[$i], 1, 0, 'C', true);
            }

            /* retour à la ligne */
            $this->Ln();

            /* partie du tableau avec les données */
            $fill = true;
            $i = 0;

            /* parcours des données */
            foreach ($data as $row) {

                /* paramètres des cellules titres */
                $this->SetFillColor($rouge[0], $rouge[1], $rouge[2]);
                $this->SetTextColor($noir[0], $noir[1], $noir[2]);
                $this->SetLineWidth(.3);
                $this->SetFont('', '', 6);

                /* sélection du nom sans le prénom de la personne */
                $nom = explode(' ', $header2[$i]);

                /* affichage du nom au début de la ligne */
                $this->Cell(15, 4, $nom[0], 'LRBT', 0, 'C', true);

                $case = $row[$header[$numero]];

                /* parcours de toutes les personnes d'une édition */
                for ($num = 1; $num < count($header3); $num++) {
                    $aAfficher = $case[$header3[$num]];

                    /* cas d'exception de la case vide */
                    if ($aAfficher == 'Pause' || $aAfficher == 'AucunCreneau') {
                        $aAfficher = '';
                        $this->SetFillColor($jaune[0], $jaune[1], $jaune[2]);
                        $this->Cell(11.5, 4, utf8_decode($aAfficher), 'LRBT', 0, 'C', $fill);
                    }

                    /* case vide si la personne n'est pas disponible */ else if ($aAfficher == 'Indisponible') {
                        $aAfficher = '';
                        $this->SetFillColor($bleuClair[0], $bleuClair[1], $bleuClair[2]);
                        $this->Cell(11.5, 4, utf8_decode($aAfficher), 'LRBT', 0, 'C', $fill);
                    }

                    /* case remplie avec le nom d'un lieu */ else {
                        if ($aAfficher != '') {

                            /* requête pour obtenir la couleur associée au lieu */
                            $requete1 = "SELECT * FROM lieu WHERE nom='" . $aAfficher . "'";
                            $result1 = mysqli_query($link, $requete1);
                            if (mysqli_num_rows($result1) == 1) {
                                $row1 = mysqli_fetch_assoc($result1);
                                $couleur = hex2rgb($row1['idCouleur']);
                                $this->SetFillColor($couleur[0], $couleur[1], $couleur[2]);
                            }
                        }
                        $this->Cell(11.5, 4, utf8_decode($aAfficher), 'LRBT', 0, 'C', $fill);
                    }
                }
                /* retour à la ligne */
                $this->Ln();
                $i++;
            }
        }
    }
}

/* on veut afficher le planning d'une personne */
if (isset($_SESSION['planningPDFPersonne']) && isset($_SESSION['personne'])) {

    /* récupération des données de la BDD */
    $data = $_SESSION['planningPDFPersonne'];

    $header2 = array_keys($data);
    $keys = array_keys($data[$header2['0']]);

    $header = array();
    array_push($header, ' ');
    foreach ($keys as $key) {
        array_push($header, $key);
    }

    /* sélection de l'année de l'édition */
    $annee = explode(" ", $header[1]);

    /* création du PDF vide */
    $pdf = new PDF();

    /* appel de la méthode pour remplir le tableau */
    $pdf->remplirTabPersonne($header, $header2, $data, $link, $annee);

    /* génération du PDF plein */
    $pdf->Output($planningPDFPersonne, 'I');
    ob_end_flush();
}

/* on veut afficher le planning d'un lieu */ else if (isset($_SESSION['planningPDFLieu']) && isset($_SESSION['versLieu'])) {

    /* récupération des données de la BDD */
    $data = $_SESSION['planningPDFLieu'];

    $header2 = array_keys($data);
    $keys = array_keys($data[$header2['0']]);

    $header = array();
    array_push($header, ' ');
    foreach ($keys as $key) {
        array_push($header, $key);
    }


    /* sélection de l'année de l'édition */
    $annee = explode(" ", $header[1]);

    /* création du PDF vide */
    $pdf = new PDF();

    /* initialisation des paramètres initiaux des pages du PDF */
    $pdf->SetMargins(10, 40, 5);
    $pdf->SetFont('Arial', '', 14);

    /* appel de la méthode pour remplir le tableau */
    $pdf->remplirTabLieu($header, $header2, $data, $link, $annee);

    /* génération du PDF plein */
    $pdf->Output($planningPDFLieu, 'I');
    ob_end_flush();
}

/* on veut afficher le planning général */ else if (isset($_SESSION['planningPDFGeneral'])) {

    /* récupération des données de la BDD */
    $data = $_SESSION['planningPDFGeneral'];

    /* $header2 stocke un tableau avec toutes les personnes (NOM Prénom) */
    $header2 = array_keys($data);
    $keys = array_keys($data[$header2['0']]);

    /* $header stocke un tableau avec toutes les dates (Lundi 01 Janvier 2022) */
    $header = array();
    array_push($header, ' ');
    foreach ($keys as $key) {
        array_push($header, $key);
    }
    foreach ($data as $row) {
        foreach ($row as $col) {
            $keys2 = array_keys($col);
        }
    }

    /* sélection de l'année de l'édition */
    $annee = explode(" ", $header[1]);

    /* $header3 stocke un tableau de toutes les heures (0h 1h ... 23h) */
    $header3 = array();
    array_push($header3, ' ');
    foreach ($keys2 as $key2) {
        array_push($header3, $key2);
    }

    /* création du PDF vide */
    $pdf = new PDF();

    /* appel de la méthode pour remplir le tableau */
    $pdf->remplirTabGeneral($header, $header2, $header3, $data, $link, $annee);

    /* génération du PDF plein */
    $pdf->Output($planningPDFGeneral, 'I');
    ob_end_flush();
} else {

    /* si le PDF ne se génère pas */
    include('../include/header.php'); ?>

    <!-- PARTIE VISUELLE DU SITE -->

    <div class='arrierePlanRectangle' style=" background-color: <?php echo $couleur7 ?>;">
        <h1 class="titrePrincipal">téléchargement du PDF</h1>
        <div class='rectangleJaune' style='background-color: <?php echo $couleur4 ?>; color: <?php echo $couleur7 ?>'>
            <div class="positionTxtPrincipal">
                <p class="sousTitreCentre sousTitre">Oups, une erreur s'est produite. </p>
                <div class="PetitTxtEtBouton">
                    <p>Le fichier PDF n'a pas pu être téléchargé.</p>
                    <a href='../planning/<?php echo $_SESSION['redirectPDF'] ?>.php' class='boutonRouge aSansFond' style='background-color: <?php echo $couleur2 ?>; color : <?php echo $couleur4 ?>;'>Retour</a>
                </div>
            </div>
        </div>
    </div>
<?php }
include("../include/footer.php"); ?>