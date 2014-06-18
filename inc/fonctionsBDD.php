<?php
function connexionBDD(){
	$connexion=NULL;
	
	$PARAM_hote='localhost'; // le chemin vers le serveur
	$PARAM_port='5432';
	$PARAM_nom_bd='projet34'; // le nom de votre base de données
	$PARAM_utilisateur='projet34'; // nom d'utilisateur pour se connecter
	$PARAM_mot_passe='SuperSecurePassword'; // mot de passe de l'utilisateur pour se connecter
	
	try
	{
		$connexion = new PDO('pgsql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);
		$connexion -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // on active la levée d'exceptions pour la base
	}
	 
	catch(Exception $e)
	{
			echo 'Erreur : '.$e->getMessage().'<br />';
			echo 'N° : '.$e->getCode();
	}
	return $connexion;

}

function listerConf($connex,$limit="0", $offset="0",$date="0",$trie="ASC"){
	if($limit==="0" && $offset==="0"){//On doit tout affichier
		if($date==="0"){
		echo "ok";
			$resultats=$connex->query("SELECT id,conf_contenu, interface, nom_conf, date_creation  FROM dhcp_test ORDER BY date_creation ".$trie.";");
		
		}else{
			echo "ok";
			$resultats=$connex->query("SELECT id,conf_contenu, interface, nom_conf, date_creation  FROM dhcp_test  WHERE  date_creation>date('".$date."') ORDER BY date_creation ".$trie.";");
			
		}
	}else{
		if($date==="0"){
				echo "ok";
			$resultats=$connex->query("SELECT id,conf_contenu, interface, nom_conf, date_creation  FROM dhcp_test ORDER BY date_creation ".$trie." LIMIT ".$limit." OFFSET ".$offset.";");
			
		
		}else{
			echo "ok";
			$resultats=$connex->query("SELECT id,conf_contenu, interface, nom_conf, date_creation FROM dhcp_test  WHERE date_creation>date('".$date."')  ORDER BY date_creation ".$trie." LIMIT ".$limit." OFFSET ".$offset.";");
			
		}
	}
	return $resultats;
}

function listerConf_id($connex, $id){
	$resultats=$connex->query("SELECT id,conf_contenu, interface, nom_conf, date_creation  FROM dhcp_test WHERE id=".$id." ORDER BY date_creation;");
	return $resultats;
}



?>