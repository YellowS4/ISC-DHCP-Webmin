<?php
if(is_install()){
	if(is_install()){
		echo 'Serveur installe';
		$apt_dhcp=shell_exec("../scripts/install_dhcp.sh 2>&1");//2>&1 affiche les erreurs
		
		header('Location: index.php');
	}else{
		printErrors(Array("Le serveur est déjà activé"));
	}
}else{
	printErrors(Array("Le serveur n'est pas installé"));
}
?>