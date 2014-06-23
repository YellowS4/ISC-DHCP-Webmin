<?php
if($_SESSION['grade']>2){
	if(!is_install()){
		echo ' <div id="chargement"> <div></div> <div></div> <div></div> </div>';
		echo 'Serveur installé ';
		$apt_dhcp=shell_exec("scripts/install_dhcp.sh 2>&1");//2>&1 affiche les erreurs	
		header('Location: index.php');
		//echo $apt_dhcp;	
	}else{
		printErrors(Array("Le serveur est déjà activé"));
	}
}else{
	printErrors(Array("Vous n'avez pas un grade suffisant"));
}
?>
