<!--                                * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *             Fichier CSS             *
                                    *     Gestions des envois de mail     *
                                    *                                     *
                                    * * * * * * * * * * * * * * * * * * * *    
-->

<?php include('../include/header.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once('../librairies/PHPMailer/src/Exception.php');
require_once('../librairies/PHPMailer/src/PHPMailer.php');
require_once('../librairies/PHPMailer/src/SMTP.php');

$nom_destinataire = $_POST['name'];
$mail_destinataire = $_POST['email'];
$message_mail = $_POST['message'];
$objet_mail = $_POST['subject'];
$erreur = false;

if ($nom_destinataire == "" || $mail_destinataire == "" || $message_mail == "" || $objet_mail == "") {
    $_SESSION["error"] = "Tous les champs doivent être remplis.";
    $erreur = true;
} else if (!preg_match("/^[a-zA-Z-'éèàïäëöêîôç]*$/", $nom_destinataire)) {
    $_SESSION['error'] = "Le nom ne doit contenir que des lettres et/ou des accents et/ou des traits d'unions et/ou des apostrophes.";
    $erreur = true;
} else if (!filter_var($mail_destinataire, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Vous devez entrer une adresse mail valide.";
    $erreur = true;
}

if ($erreur == true) {
    $_SESSION['nom_destinataire'] = $nom_destinataire;
    $_SESSION['mail_destinataire'] = $mail_destinataire;
    $_SESSION['objet_mail'] = $objet_mail;
    $_SESSION['message_mail'] = $message_mail;
    header('location:envoiMail.php');
    die();
} else {
    if (isset($_SESSION['nomFichier'])) {
        unset($_SESSION['nom_destinataire']);
        unset($_SESSION['mail_destinataire']);
        unset($_SESSION['objet_mail']);
        unset($_SESSION['message_mail']);
        $filename = $_SESSION['nomFichier'];
        $message_mail = "Salut " . $nom_destinataire . ",\n\n" . $_POST['message'];

        $email = new PHPMailer();
        $email->SetFrom($adresseEnvoiMail, 'Les Briques Rouges');
        $email->AddBCC($adresseEnvoiMail);
        $email->Subject = $objet_mail;
        $email->Body = $message_mail;
        $email->AddAddress($mail_destinataire, $nom_destinataire);
        $email->AddAttachment($filename);
        $email->IsSMTP();
        $email->Host = "smtp.gmail.com";
        $email->Port = 587;
        $email->SMTPAuth = true;
        $email->Mailer = "smtp";
        $email->SMTPSecure = 'tls';
        $email->Username = $adresseEnvoiMail;
        $email->Password = $mdpApplication;

        if (!$email->Send()) {
            $_SESSION["error"] = "L'email n'a pas pu être envoyé.";
        } else {
            $_SESSION["success"] = "L'email a bien été envoyé.";
        }
        unset($_SESSION['nomFichier']);
        unlink($filename);
    } else {
        $_SESSION['error'] = "L'email n'a pas pu être envoyé.";
    }
}

header('location:../planning/planningPersonne.php');
include('../include/footer.php');
