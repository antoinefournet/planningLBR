<!--                                * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *             Fichier CSS             *
                                    *     Toutes les alertes du site      *
                                    *                                     *
                                    * * * * * * * * * * * * * * * * * * * *    
-->

<style>
    .bandeauHaut {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 7;
    }
</style>

<?php
if (isset($_SESSION['success'])) {
    echo "<div class='bandeauHaut alert alert-success alert-dismissible fade show' style='margin:0'>";
    echo "<button type='button' class='close' data-dismiss='alert'>&times;</button>";
    echo "<strong>Succès !</strong> " . $_SESSION['success'];
    echo "</div>";
    unset($_SESSION['success']);
}
if (isset($_SESSION['warning'])) {
    echo "<div class='bandeauHaut alert alert-warning alert-dismissible fade show' style='margin:0'>";
    echo "<button type='button' class='close' data-dismiss='alert'>&times;</button>";
    echo "<strong>Attention !</strong> " . $_SESSION['warning'];
    echo "</div>";
    unset($_SESSION['warning']);
}
if (isset($_SESSION['error'])) {
    echo "<div class='bandeauHaut alert alert-danger alert-dismissible fade show' style='margin:0'>";
    echo "<button type='button' class='close' data-dismiss='alert'>&times;</button>";
    echo "<strong>Erreur !</strong> " . $_SESSION['error'];
    echo "</div>";
    unset($_SESSION['error']);
}
?>