<?php session_start();

if (isset($_POST['lieuChoisi'])) {
    $_SESSION['versLieu'] = $_POST['lieuChoisi'];
    header('location:planningLieu.php');
    die();
}