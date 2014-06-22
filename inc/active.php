<?php
////////////////////////////////////
//Projet 34 - AUDON Florian - B1  //
////////////////////////////////////
if(is_install()){
	if(!is_run()){
		ob_start(); /* On initialise le tampon. */
		// On desinstalle le serveur DHCP
		echo 'Serveur DHCP activé';
		$active=shell_exec("scripts/enable_dhcp.sh 2>&1");
		//echo $active;
		header('Location: index.php');
		ob_end_flush(); /* On vide le tampon et on retourne le contenu au client. */	
	}else{
		printErrors(Array("Le serveur est déjà activé"));
	}
}else{
	printErrors(Array("Le serveur n'est pas installé"));
}
?>