<?php
$link = mysqli_connect("127.0.0.1", "root", "", "groupe20_planificateur_lbr");

if ($link == false) {
	echo "Erreur de connexion : " . mysqli_connect_errno();
	die();
}

if (!mysqli_set_charset($link, "utf8")) {
	printf("Error loading character set utf8 : %s\n", mysqli_error($link));
	exit();
}
