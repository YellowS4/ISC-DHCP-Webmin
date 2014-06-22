<?php
/*
 * Code par Florian Audon
 */
function connexionBDD(){
	$connexion=NULL;
	
	$PARAM_hote='localhost'; // le chemin vers le serveur
	$PARAM_port='5432';
	$PARAM_nom_bd='projet34'; // le nom de votre base de données
	$PARAM_utilisateur='projet34'; // nom d'utilisateur pour se connecter
	$PARAM_mot_passe='GXnyxX'; // mot de passe de l'utilisateur pour se connecter
	
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
			$resultats=$connex->prepare("SELECT idconf ,contenuconf,createurconf,creation,nomuser   FROM projet34_configurations INNER JOIN projet34_users ON createurconf=iduser ORDER BY creation ".$trie.";");
		    $resultats->execute();
		
		}else{
			//$resultats=$connex->query("SELECT idconf ,contenuconf,createurconf,creation,nomuser   FROM projet34_configurations INNER JOIN projet34_users ON createurconf=iduser WHERE  creation>date('".$date."') ORDER BY creation ".$trie.";");
			$resultats=$connex->prepare("SELECT idconf ,contenuconf,createurconf,creation,nomuser   FROM projet34_configurations INNER JOIN projet34_users ON createurconf=iduser WHERE  creation>date('?') ORDER BY creation ".$trie.";");
			$resultats->execute(Array($date));
		}
	}else{
		if($date==="0"){
			//$resultats=$connex->query("SELECT idconf ,contenuconf,createurconf,creation,nomuser   FROM projet34_configurations INNER JOIN projet34_users ON createurconf=iduser ORDER BY creation ".$trie." LIMIT ".$limit." OFFSET ".$offset.";");
			$resultats=$connex->prepare("SELECT idconf ,contenuconf,createurconf,creation,nomuser   FROM projet34_configurations INNER JOIN projet34_users ON createurconf=iduser ORDER BY creation ".$trie." LIMIT ? OFFSET ?;");
			$resultats->execute(Array($limit, $offset));
		
		}else{
			//$resultats=$connex->query("SELECT idconf ,contenuconf,createurconf,creation,nomuser   FROM projet34_configurations INNER JOIN projet34_users ON createurconf=iduser WHERE creation>date('".$date."')  ORDER BY creation ".$trie." LIMIT ".$limit." OFFSET ".$offset.";");
			$resultats=$connex->prepare("SELECT idconf ,contenuconf,createurconf,creation,nomuser   FROM projet34_configurations INNER JOIN projet34_users ON createurconf=iduser WHERE creation>date('?') ORDER BY creation ".$trie." LIMIT ? OFFSET ?;");
			$resultats->execute(Array($date,$limit, $offset));
		}
	}
	return $resultats;
}

function listerConf_id($connex, $id){
	$resultats=$connex->prepare("SELECT idconf ,contenuconf,createurconf,creation,nomuser   FROM projet34_configurations INNER JOIN projet34_users ON createurconf=iduser FROM dhcp_test WHERE id=? ORDER BY creation;");
	$resultats->execute(Array($id));
	return $resultats;
}


/*
 * Code par Jason Gantner
 */
function getHash($connex,$user){
  $req=$connex->prepare('SELECT h1 FROM projet34_users WHERE login=? AND actif=true;');
  $req->execute(Array($user));
  return $req->fetch();
}

function getUser($connex,$user){
  $req=$connex->prepare('SELECT * FROM projet34_users WHERE login=?;');
  $req->execute(Array($user));
  return $req->fetch();
}

function getUsers($connex,$nombre,$offset){
  $req=$connex->prepare('SELECT * FROM projet34_users ORDER BY actif DESC LIMIT ? OFFSET ?;');
  $req->execute(Array($nombre,$offset));
  return $req->fetchAll();
}

function totalUsers($connex){
  $res=$connex->query('SELECT COUNT(idUser) AS total FROM projet34_users;');
  return $res-fetch()['total'];
}

function changeActif($connex,$id,$actif){
  $req=$connex->prepare('UPDATE projet34_users SET actif=? WHERE idUser=?;');
  return $req->execute(Array($actif,$id));
}

function changeGrade($connex,$id,$grade){
  $req=$connex->prepare('UPDATE projet34_users SET refGrade=? WHERE idUser=?;');
  return $req->execute(Array($grade,$id));
}

function changeMail($connex,$id,$mail){
  $req=$connex->prepare('UPDATE projet34_users SET email=? WHERE idUser=?;');
  return $req->execute(Array($mail,$id));
}

function addUser($connex,$name,$login,$mail,$h1,$grade,$actif){
  $req=$connex->prepare('INSERT INTO projet34_users (nomuser,login,email,h1,refgrade,actif) VALUES (?,?,?,?,?,?);');
  return $req->execute(Array($name,$login,$mail,$h1,$grade,$actif));
}

?>