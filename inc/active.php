<?php
////////////////////////////////////
//Projet 34 - AUDON Florian - B1  //
////////////////////////////////////
require_once 'inc/fonctionsBDD.php';
$connex=connexionBDD();
if($_SESSION['grade']>2){
	if(is_install()){
		if(!is_run()){
			
			$liste_conf=listerConf($connex);
			if($liste_conf->rowCount()!==0){
				// On active le serveur DHCP
				echo 'Serveur DHCP activé';
				$active=shell_exec("scripts/enable_dhcp.sh 2>&1");
				//echo $active;
				header('Location: index.php');
			}else{
				printErrors(Array("Le serveur ne peut être activé, il n'y a aucune configuration."));
			}
		}else{
			printErrors(Array("Le serveur est déjà activé"));
		}
	}else{
		printErrors(Array("Le serveur n'est pas installé"));
	}
}else{
	printErrors(Array("Vous n'avez pas un grade suffisant"));
}
?>