<article>
<?php
  if($_SESSION['grade']>1){
    require_once 'inc/appliquer_conf.php';
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
		\n".$_POST['conf'];
    $res=appliquer_conf($conf,NULL);
    if($res!==NULL) echo '<span class="error">
	    Impossible d\'appliquer la configuration<br>
	    Erreur du serveur:',$res,'</span>';
    else echo 'L\'application de la configuration s\'est bien déroulée.';
  }
 else echo 'Vous n\'êtes pas assez gradé pour accèder à cette page!';
?>
</article>