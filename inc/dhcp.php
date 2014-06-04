<?php
/**
 * Permet de savoir si le serveur est activé ou non
 *	@return true si le serveur est active et false sinon
*/
	function is_activate(){
		$fichier_demarrage=shell_exec("ls /etc/rc*/*isc*");
		if(strstr($fichier_demarrage,"/etc/rc2.d/S18isc-dhcp-server")){//S'il est active on propose de le desactivé
			$active=true;
		}else{ 
			$active=false;
		}
		return $active;
	}
/**
 * Permet de savoir si le serveur est installé ou non
 *	@return true si le serveur est installé et false sinon
*/	
	function is_install(){
		//Pour eviter les long temps d'attente
		/*$aptitude_dhcp=shell_exec("aptitude show isc-dhcp-server");
		if(!preg_match("/State: not installed/",$aptitude_dhcp,$matches)){//On verifie que le dhcp est installé
			$install=false;
		}else{
			$install=true;
		}
		return $install;*/
		return true;
	}
?>