<?php
ob_start(); /* On initialise le tampon. */
// On desinstalle le serveur DHCP
	echo 'Serveur DHCP desinstalle';
	$desinstalle=shell_exec("../scripts/disabled_dhcp.sh 2>&1");
	header('Location: index.php');
		ob_end_flush(); /* On vide le tampon et on retourne le contenu au client. */	
			
?>