<!--                                * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *             Fichier CSS             *
                                    *   Export de fichier PDF pour mail   *
                                    *                                     *
                                    * * * * * * * * * * * * * * * * * * * *    
-->

<?php session_start();
include('../include/connexionBDD.php');
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
}

if (isset($_SESSION['planningPDFPersonne']) && isset($_SESSION['personne'])) {
    $data = $_SESSION['planningPDFPersonne'];
    $header2 = array_keys($data);
    $keys = array_keys($data[$header2['0']]);
    $header = array();
    array_push($header, ' ');
    foreach ($keys as $key) {
        array_push($header, $key);
    }
    $filename = "planningPDF.pdf";

    if (file_exists($filename)) {
        //supprimer ce fichier
        unlink('planningPDF.pdf');
    }
    $annee = explode(" ", $header[1]);
    $_SESSION['nomFichier'] = $filename;
    $pdf = new PDF();
    $pdf->remplirTabPersonne($header, $header2, $data, $link, $annee);
    $pdf->Output($filename, 'F');

    if (file_exists($filename)) {
        $_SESSION["success"] = "Le fichier PDF est prêt à être envoyé.";
    }
    header('location:envoiMail.php');
    die();
} else {
    include("../include/header.php"); ?>

    <div class='arrierePlanRectangle' style=" background-color: <?php echo $couleur7 ?>;">
        <h1 class="titrePrincipal">Préparation du mail</h1>
        <div class='rectangleJaune' style='background-color: <?php echo $couleur4 ?>; color: <?php echo $couleur7 ?>'>
            <div class="positionTxtPrincipal">
                <p class="sousTitreCentre sousTitre">Oups, une erreur s'est produite. </p>
                <div class="PetitTxtEtBouton">
                    <p>Le fichier PDF n'est pas prêt à être envoyé.</p>
                    <a href='../planning/planningPersonne.php' class='boutonRouge aSansFond' style='background-color: <?php echo $couleur2 ?>; color : <?php echo $couleur4 ?>;'>Retour</a>
                </div>
            </div>
        </div>
    </div>

<?php }
include("../include/footer.php"); ?>