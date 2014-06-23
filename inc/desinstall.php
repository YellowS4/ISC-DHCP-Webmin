<?php
if($_SESSION['grade']>2){
	if(is_install()){
		// On desinstalle le serveur DHCP
		echo 'Serveur DHCP desinstalle';
		$desinstalle=shell_exec("scripts/uninstall_dhcp.sh 2>&1");
		header('Location: index.php');
		//echo $desinstalle;	
	}else{
		printErrors(Array("Le serveur n'est pas installé"));
	}
}else{
	printErrors(Array("Vous n'avez pas un grade suffisant"));
}
?>
