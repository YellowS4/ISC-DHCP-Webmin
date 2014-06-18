<?php
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

function generer_conf(){
	$conf="
		default-lease-time 600;\n
		max-lease-time 7200;\n
		\n
		#Pour envoyer un DHCPNAK \n
		authoritative;\n
		\n
		#Pour les logs\n
		log-facility local7;\n
		\n
		\n
		subnet 192.168.1.0 netmask 255.255.255.0 {\n
		  range 192.168.1.1 192.168.1.253;\n
		  option routers 192.168.1.254;\n
		  option broadcast-address 192.168.1.255;\n
		  option domain-name-servers 192.168.1.254;\n
		}";
	return $conf;
}

function appliquer_conf($conf,$interface){
	shell_exec("echo \"".$conf."\">/tmp/dhcpd.conf");
	$erreur=shell_exec("/usr/sbin/dhcpd -t -cf /tmp/dhcpd.conf 2>&1");
	//echo "erreur: ".$erreur;
	$erreur=verif_conf($conf);
	if(verif_conf($conf)!=""){
		return $erreur;
	}else{
		$sortie=shell_exec("sudo cp /tmp/dhcpd.conf /etc/dhcp/dhcpd.conf 2>&1");
	}
	$sortie=shell_exec("/etc/init.d/isc-dhcp-server restart");
	
}
?>