<?php
/*
 * Code par Florian Audon
 */

/**
 * connexionBDD Connexion à la base de donnée
 * @return PDOOBJECT retourne la connexion à la BDD
 **/
function connexionBDD(){
	$connexion=NULL;
	
	$PARAM_hote='localhost'; // le chemin vers le serveur
	$PARAM_port='5432';
	$PARAM_nom_bd='projet34'; // le nom de votre base de données
	$PARAM_utilisateur='projet34'; // nom d'utilisateur pour se connecter
	$PARAM_mot_passe='SuperSecurePass'; // mot de passe de l'utilisateur pour se connecter
	
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
/**
 * listerConf Lister les configurations de la BDD
 * @param  PDOOBJECT $connex    Connexion retourné par connexionBDD()
 * @param  string $limit      	Facultatif - début de la selection
 * @param  string $offset 		Facultatif - fin de la selection
 * @param  string $date   		Facultatif - selectionne les resultats avant une certaines dates
 * @param  string $trie   		Facultatif - trie par ordre decroissant ou croissant
 * @return Array $resultats     Retourne les resultats de la BDD
 */
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

/**
 * listerConf_id recupere les informations d'une configuration de la BDD avec un id donné
 * @param  PDOOBJECT $connex 	 Connexion retourné par connexionBDD()
 * @param  Integer $id    		 Retourne la conf avec cette id
 * @return Array $resutats       Retourne le résultats de la BDD
 */
function listerConf_id($connex, $id){
	$resultats=$connex->prepare("SELECT idconf ,contenuconf,createurconf,creation,nomuser   FROM projet34_configurations INNER JOIN projet34_users ON createurconf=iduser WHERE idconf=? ORDER BY creation;");
	$resultats->execute(Array($id));
	return $resultats;
}
/**
 * rmConf_id supprime une configuration de la BDD avec un id donné
 * @param   PDOOBJECT $connex     Connexion retourné par connexionBDD()
 * @param   integer   $id         valeur de l'id de la configuration à supprimer
 * @return  Array     $resultats  
 */
function rmConf_id($connex, $id){
	$resultats=$connex->prepare("DELETE  FROM projet34_configurations  WHERE idconf=?;");
	$resultats->execute(Array($id));
	return $resultats;
}
/**
 * addConf Ajoute une configuration
 * @param  PDOOBJECT $connex   Connexion retourné par connexionBDD()
 * @param  String    $contenu  Contenu de la configuration
 * @param  Integer   $createur Id du createur de la configuration
 */
function addConf($connex, $contenu, $createur){
	//$resultats=$connex->exec("INSERT INTO projet34_configurations  (contenuconf,createurconf) VALUES ('".$_POST['contenuconf']."',1); ");
	$req=$connex->prepare('INSERT INTO projet34_configurations (contenuconf,createurconf) VALUES (?,?);');
    return $req->execute(Array($contenu,$createur));
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
  $res=$connex->prepare('SELECT COUNT(idUser) AS total FROM projet34_users;');
  //ne fonctionne pas avec query, je tente avec prepare execute fetch
  $res->execute();
  $res=$res->fetch()['total'];
  settype($res,'integer');
  return $res;
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

function rmUser($connex,$id){
  $req=$connex->prepare('DELETE FROM projet34_users WHERE iduser=?;');
  return $req->execute(Array($id));
}

?>