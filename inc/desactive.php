<?php
////////////////////////////////////
//Projet 34 - AUDON Florian - B1  //
////////////////////////////////////
if($_SESSION['grade']>2){
	if(is_install()){
		if(is_run()){
			// On desinstalle le serveur DHCP
			echo 'Serveur DHCP desactive';
			$desactive=shell_exec("scripts/disabled_dhcp.sh 2>&1");
			//echo $desactive;
			header('Location: index.php');
			}else{
				printErrors(Array("Le serveur est déjà désactivé"));
			}
	}else{
		printErrors(Array("Le n'est pas installé"));
	}
}else{
	printErrors(Array("Vous n'avez pas un grade suffisant"));
}
			
?>
