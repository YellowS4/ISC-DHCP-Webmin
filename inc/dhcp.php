<?php
////////////////////////////////////
//Projet 34 - AUDON Florian - B1  //
////////////////////////////////////

/**
 * Permet de savoir si le serveur est installé ou non
 *	@return true si le serveur est installé et false sinon
*/	
	function is_install(){
		//Pour eviter les long temps d'attente
		$aptitude_dhcp=shell_exec("aptitude show isc-dhcp-server");
		if(preg_match("/State: not installed/",$aptitude_dhcp,$matches)){//On verifie que le dhcp est installé
			$install=false;
		}else{
			$install=true;
		}
		return $install;
		//return true;
	}
/**
 * is_run Permet de savoir si le serveur est activé
 * @return boolean true si le serveur est activé et false sinon
 */
	function is_run(){
		//Pour eviter les long temps d'attente
		$aptitude_dhcp=shell_exec("/etc/init.d/isc-dhcp-server status");
		if(preg_match("/dhcpd is running/",$aptitude_dhcp,$matches)){//On verifie que le dhcp est installé
			$running=true;
		}else{
			$running=false;
		}
		return $running;
		//return true;
	}
?>
