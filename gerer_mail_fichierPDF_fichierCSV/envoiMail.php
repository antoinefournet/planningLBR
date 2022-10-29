<!--                                * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *             Fichier CSS             *
                                    *     Formulaire d'envoi de mail      *
                                    *                                     *
                                    * * * * * * * * * * * * * * * * * * * *    
-->

<?php include('../include/header.php'); ?>

<div class='arrierePlanRectangle' style=" background-color: <?php echo $couleur7 ?>;">
    <h1 class="titrePrincipal">Préparation du mail</h1>

    <div class='rectangleJaune' style='background-color: <?php echo $couleur4 ?>; color: <?php echo $couleur7 ?>'>
        <form id="contact" class="envoiMail" action="actionEnvoiMail.php" method="post">
            <div style="display : grid">
                <label for="name">Nom:</label>
                <input class="champModifierPersonne " style="border-color: <?php echo $couleur7 ?>;" id="name" type="text" name="name" width="250" size="35" value=<?php if (isset($_SESSION['personne'])) {
                                                                                                                                                        $requete1 = "SELECT * FROM personne WHERE idPersonne='" . $_SESSION['personne'] . "'";
                                                                                                                                                        $result1 = mysqli_query($link, $requete1);
                                                                                                                                                        if (mysqli_num_rows($result1) == 1) {
                                                                                                                                                            $row1 = mysqli_fetch_assoc($result1);
                                                                                                                                                            echo $row1['prenom'];
                                                                                                                                                        }
                                                                                                                                                    } else if (isset($_SESSION['personneDestinataire'])) {
                                                                                                                                                        echo $_SESSION['personneDestinataire'];
                                                                                                                                                    } ?>>

                <label for="email">Adresse Email:</label>
                <input class="champModifierPersonne " style="border-color: <?php echo $couleur7 ?>;" id="email" type="text" name="email" width="250" size="35" value=<?php
                                                                                                                                                        if (isset($_SESSION['personne'])) {
                                                                                                                                                            $requete2 = "SELECT * FROM personne WHERE idPersonne='" . $_SESSION['personne'] . "'";
                                                                                                                                                            $result2 = mysqli_query($link, $requete2);
                                                                                                                                                            if (mysqli_num_rows($result2) == 1) {
                                                                                                                                                                $row2 = mysqli_fetch_assoc($result2);
                                                                                                                                                                echo $row2['mail'];
                                                                                                                                                            }
                                                                                                                                                        } else if (isset($_SESSION['mail_destinataire'])) {
                                                                                                                                                            echo $_SESSION['mail_destinataire'];
                                                                                                                                                        } ?>>

                <label class="champModifierPersonne " for="subject">Objet du mail:</label>
                <input type="text" id="subject" name="subject" value=<?php if (isset($_SESSION['objet_mail'])) {
                                                                            echo $_SESSION['objet_mail'];
                                                                            unset($_SESSION['objet_mail']);
                                                                        } else {
                                                                            echo $objetMailDefaut;
                                                                        } ?>>

                <label for="message">Message du mail:</label>
                <textarea id="message" name="message" rows="4" cols="40"><?php if (isset($_SESSION['message_mail'])) {
                                                                                echo $_SESSION['message_mail'];
                                                                                unset($_SESSION['message_mail']);
                                                                            } else {
                                                                                echo $messageMailDefaut;
                                                                            } ?></textarea>
            </div>
            <div>

                <?php if (isset($_SESSION["nomFichier"])) {
                    echo '<a href=' . $_SESSION["nomFichier"] . ' class="btn btn-dark" style="margin-top:2%;" target="_blank">Visualiser la pièce jointe</a>';
                } ?>
                <input type="submit" class="btn boutonRouge" style="margin-top:2%;background-color: <?php echo $couleur2 ?>; color: <?php echo $couleur4 ?>;" value="Valider" id="submit" />
                <a href='../planning/planningPersonne.php' class='btn boutonRouge aSansFond' style='margin-top:2%;background-color: <?php echo $couleur3 ?>; color: <?php echo $couleur4 ?>;'>Retour à la page précédente</a>
            </div>
        </form>
    </div>



</div>
</div>
<?php
include('../include/footer.php');
?>