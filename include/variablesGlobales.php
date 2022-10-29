<!--                                * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *             Fichier CSS             *
                                    *   Ce que vous avez besoin de modif  *
                                    *                                     *
                                    * * * * * * * * * * * * * * * * * * * *    
-->

<?php
/* Le nombre d'heure que doit faire un bénévole par défaut */
$nombreHeureBenevolesDefaut = 6;

/* Variables globales pour envoyer un mail à un membre ou un bénévole contenant son planning en PDF */
$objetMailDefaut = "Planning Briques Rouges"; /* L'objet du mail */
$messageMailDefaut = "Voici votre planning pour le festival des Briques Rouges !"; /* Le message du mail */
$adresseEnvoiMail = "uneadressemail99@gmail.com"; /* L'adresse mail qui envoie la mail */
$mdpApplication = "vavrnpdusjaocmgu"; /* Le mot de passe d'application permettant de se connecter à l'adresse mail ci-dessus */

/* Variables globales pour envoyer un mail à un admin s'il a oublié son mot de passe */
$objetMdpOublie = "Réinitiliser mot de passe"; /* L'objet du mail */
$messageMdpOublie = "Vous avez demandé à réinitialiser votre mot de passe. Votre mot de passe temporaire est "; /* Le message du mail */

/* Nom par défaut des fichiers PDF que l'on peut télécharger */
$planningPDFLieu = "planningPDFLieu.pdf";
$planningPDFPersonne = "planningPDFPersonne.pdf";
$planningPDFGeneral = "planningPDFGeneral.pdf";


// pallette de couleur
$couleur1 = "#AF001E";  // pourpre
$couleur2 = "#EA001C";  // rouge
$couleur3 = "#FF890A";  // orange
$couleur4 = "#FFFEE6";  // jaune
$couleur5 = "#BDE4FC";  // bleu clair
$couleur6 = "#3671B3";  // bleu
$couleur7 = "#161920";  // noir
?>