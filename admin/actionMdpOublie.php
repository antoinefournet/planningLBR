<!--                                * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *             Fichier PHP             *
                                    *     Envoi du mail si mdp oublié     *
                                    *                                     *
                                    * * * * * * * * * * * * * * * * * * * *    
-->

<!-- insertion du header avec le logo en haut à gauche -->
<?php include('../include/header.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

/* redirection si on ferme le modal */

if (isset($_POST['fermerMdpOublie'])) {
    unset($_SESSION['btnMdpOublie']);
    header('location:../accueil.php');
    die();
}

/* ouverture du modal */
if (isset($_POST['btnMdpOublie'])) {
    $_SESSION['btnMdpOublie'] = true;
    header('location:../accueil.php');
    die();
}

/* envoi du mail */
if (isset($_POST['envoiMdp'])) {
    require_once('../librairies/PHPMailer/src/Exception.php');
    require_once('../librairies/PHPMailer/src/PHPMailer.php');
    require_once('../librairies/PHPMailer/src/SMTP.php');

    /* données du mail pré-remlies */
    $nom_destinataire = $_POST['idMdpOublie'];
    $mail_destinataire = $_POST['idMdpOublie'];
    $objet_mail = $objetMdpOublie;
    $message_mail = $messageMdpOublie;
    $erreur = false;

    if ($nom_destinataire == "" || $mail_destinataire == "" || $message_mail == "" || $objet_mail == "") {
        $_SESSION["error"] = "Tous les champs doivent être remplis.";
        $erreur = true;
    } else if (!filter_var($mail_destinataire, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Vous devez entrer une adresse mail valide.";
        $erreur = true;
    }

    if ($erreur == false) {
        $comb = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array();
        $combLen = strlen($comb) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $combLen);
            $pass[] = $comb[$n];
        }
        $newPassword = implode($pass);
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $requete2 = "UPDATE admin SET mdp='" . $passwordHash . "' WHERE idAdmin='" . $nom_destinataire . "'";
        $result2 = mysqli_query($link, $requete2);
        if ($result2 == false) {
            $erreur = true;
            $_SESSION['error'] = "Les informations n'ont pas pu être inséré dans la base données.";
        }
    }

    if ($erreur == true) {
        $_SESSION['nomMdpOublie'] = $nom_destinataire;
        header('location:../accueil.php');
        die();
    } else {
        unset($_SESSION['nomMdpOublie']);
        unset($_SESSION['btnMdpOublie']);
        $message_mail = "Salut " . $nom_destinataire . ",\n\n" . $message_mail . $newPassword . ".";

        $email = new PHPMailer();
        $email->SetFrom($adresseEnvoiMail, 'Planning Les Briques Rouges Festival');
        $email->Subject = utf8_decode($objet_mail);
        $email->Body = utf8_decode($message_mail);
        $email->AddAddress($mail_destinataire, $nom_destinataire);
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
        header('location:../accueil.php');
        die();
    }
}

include('../include/footer.php');
