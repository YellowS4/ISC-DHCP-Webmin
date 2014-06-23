<?php
////////////////////////////////////
//Projet 34 - AUDON Florian - B1  //
////////////////////////////////////
require_once 'inc/fonctionsBDD.php';
$connex=connexionBDD();
if($_SESSION['grade']>2){
	?>
	<article>
	<?php
	if(is_install()){//Si le serveur n'est pas installé
		if(!is_run()){//Si le serveur n'est pas lancé
			
			$liste_conf=listerConf($connex);
			if($liste_conf->rowCount()!==0){//Si il y a aucune configuration
				// On active le serveur DHCP
				$active=shell_exec("scripts/enable_dhcp.sh 2>&1");
				if(preg_match("/failed/",$active,$matches)){//On regarde si il y a une erreur
					printErrors(Array($active));
				}
				else{//Sinon on redirige vers l'état
					header('Location: index.php');
				}
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
</article>