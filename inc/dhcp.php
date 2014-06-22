<?php
////////////////////////////////////
//Projet 34 - AUDON Florian - B1  //
////////////////////////////////////

/**
 * Permet de savoir si le serveur est install� ou non
 *	@return true si le serveur est install� et false sinon
*/	
	function is_install(){
		//Pour eviter les long temps d'attente
		$aptitude_dhcp=shell_exec("aptitude show isc-dhcp-server");
		if(preg_match("/State: not installed/",$aptitude_dhcp,$matches)){//On verifie que le dhcp est install�
			$install=false;
		}else{
			$install=true;
		}
		return $install;
		//return true;
	}
/**
 * is_run Permet de savoir si le serveur est activ�
 * @return boolean true si le serveur est activ� et false sinon
 */
	function is_run(){
		//Pour eviter les long temps d'attente
		$aptitude_dhcp=shell_exec("/etc/init.d/isc-dhcp-server status");
		if(preg_match("/dhcpd is running/",$aptitude_dhcp,$matches)){//On verifie que le dhcp est install�
			$running=true;
		}else{
			$running=false;
		}
		return $running;
		//return true;
	}
?>
