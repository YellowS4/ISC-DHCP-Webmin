<?php
////////////////////////////////////
//Projet 34 - AUDON Florian - B1  //
////////////////////////////////////

/**
 * verif_conf verifie la presence d'erreur dans une configuration
 * @param  string $conf configuration à tester
 * @return string return "" si il n'y a pas d'erreur et sinon retourne l'erreur
 */
function verif_conf($conf){
	$return="";
	shell_exec("echo \"".$conf."\">/tmp/dhcpd.conf");
	$erreur=shell_exec("/usr/sbin/dhcpd -t -cf /tmp/dhcpd.conf 2>&1");
	//echo "erreur: ".$erreur;
	if(preg_match('@For info, please visit https://www.isc.org/software/dhcp/\n/tmp/dhcpd.conf(.*)\.@m',$erreur,$matches)){
		$return=$matches[1];
	}

	return $return;
}


/**
 * appliquer_conf Applique une configuration et retourne une erreur si il y en a une
 * @param  string $conf configuration à tester
 * @return string return "" si il n'y a pas d'erreur et sinon retourne l'erreur
 */
function appliquer_conf($conf){
	shell_exec("echo \"".$conf."\">/tmp/dhcpd.conf");
	$erreur=shell_exec("/usr/sbin/dhcpd -t -cf /tmp/dhcpd.conf 2>&1");
	//echo "erreur: ".$erreur;
	$erreur=verif_conf($conf);
	if($erreur!=""){
		return $erreur;
	}else{
		$sortie=shell_exec("sudo cp /tmp/dhcpd.conf /etc/dhcp/dhcpd.conf 2>&1");
	}
	$sortie=shell_exec("/etc/init.d/isc-dhcp-server reload");
	
}
?>