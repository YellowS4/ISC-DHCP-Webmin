<?php
// On desinstalle le serveur DHCP
	echo 'Serveur DHCP desinstalle';
	$desinstalle=shell_exec("../scripts/uninstall_dhcp.sh 2>&1");
	header('Location: index.php');
	
			
?>