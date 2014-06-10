<?php
function appliquer_conf($conf,$interface){
	shell_exec("echo \"".$conf."\">/tmp/dhcpd.conf");
	$erreur=shell_exec("/usr/sbin/dhcpd -t -cf /tmp/dhcpd.conf 2>&1");
	//echo "erreur: ".$erreur;
	if(preg_match('@For info, please visit https://www.isc.org/software/dhcp/\n/tmp/dhcpd.conf(.*)\.@m',$erreur,$matches)){
		return $matches[1];
	}else{
		$sortie=shell_exec("sudo cp /tmp/dhcpd.conf /etc/dhcp/dhcpd.conf 2>&1");
	}
	
}
?>