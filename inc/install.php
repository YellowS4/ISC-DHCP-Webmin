<?php
	echo 'Serveur installe';
	$apt_dhcp=shell_exec("../scripts/install_dhcp.sh 2>&1");//2>&1 affiche les erreurs
	
	header('Location: index.php');
?>