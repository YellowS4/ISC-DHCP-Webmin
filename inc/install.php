<?php
	if(!is_install()){
ob_start(); /* On initialise le tampon. */
		echo 'Serveur installe ';
		$apt_dhcp=shell_exec("scripts/install_dhcp.sh 2>&1");//2>&1 affiche les erreurs	
	header('Location: index.php');
	echo $apt_dhcp;	
	ob_end_flush(); /* On vide le tampon et on retourne le contenu au client. */	
	}else{
		printErrors(Array("Le serveur est déjà activé"));
	}
?>
