<?php
  require_once 'inc/fonctions_generales.php';
  ob_start();// tout les affichages seront d'abord stockés dans un buffer
  session_start();
  if(!(isset($_SESSION['user']) && isset($_SESSION['grade']))){
    /*
     * on estime que l'utilisateur n'est pas connecté si son grade et son
     * login ne sont pas enregistrés dans la session
     */
    if(isset($_POST['keep'])) setcookie('user',$_POST['pseudo'],time()+(60*60*24*10));
    /* 
     * on retient le nom d'utilisateur dans un cookie pendant 10 jours
     * si la case prévue à cet effet est cochée
     */
    if(isset($_POST['pseudo']) && isset($_POST['pass']) && isset($_POST['challenge2'])){
      /*
       * on vérifie que tous les champs dont on ai besoin pour la 
       * vérification soit envoyés; si il ne le sont pas on redirige
       * sur la page d'authentification avec un message d'erreur.
       */
      require_once 'inc/fonctionsBDD.php';
      $bdd=ConnexionBDD();//on se connecter à la base de données
      $hres=getHash($bdd,$_POST['pseudo']);
      //on tente de récupérer le hash 1 dans la BDD pour le login envoyé
      if(isset($hres['h1'])){
	// on vérifie qu'il y en ai bien un
      	$h1=$hres['h1'];
      }
      else{
		// on redirige si on ne réussit pas à récuperer le hash 1.
		redir('auth.php','Mauvais Utilisateur/mot de passe');
		exit();
      }
      $h2=hash('sha512',$_SESSION['ch1']);
      // on calcule le hash 2 avec le nombre aléatoire généré par le serveur.
      $h3=hash('sha512',$_POST['challenge2']);
      //on fait de même avec celui généré par le client.
      if($_POST['pass']===hash('sha512',$h1.':'.$h2.':'.$h3)){
		/* 
		 * on compare le résultat du hash de h1:h2:h3.
		 * si ils sont identiques, l'authentification est réussie
		 * et on stocke dans la session les informations dont on se 
		 * servira après les avoir récupérées dans la base de données.
		 */
		$res=getUser($bdd,$_POST['pseudo']);
		$_SESSION['user']=$res['nomuser'];
		// nom de l'utilisateur
		$_SESSION['grade']=$res['refgrade'];// son grade
		$_SESSION['id']=$res['iduser'];// son id
		$_SESSION['user-agent']=$_SERVER['HTTP_USER_AGENT'];
		// les informations sur le navigateur et le pc client
		$_SESSION['IP']=$_SERVER['REMOTE_ADDR'];// l'ip du client
		if(isset($_SESSION['erreur'])) unset($_SESSION['erreur']);
		//on supprime l'erreur si il y en a une.
		header('refresh: 0;');// on recharge la page pour afficher l'index.
      }
      else{
	redir('auth.php','Mauvais Utilisateur/mot de passe');
      }
    }
    else{
      redir('auth.php','Tous les champs ne sont pas remplis!');
    }
  }
  else{
    if(!($_SESSION['IP']===$_SERVER['REMOTE_ADDR'])){
      /*si l'IP qui se connecte avec ce cookie de session ne correspond pas à 
      * celle qui de l'authentification, on déconnecte la session et on déconnecte la 
      * session et on redirige sur la page d'authentification en affichant l'IP de la 
      * session et celle de l'attaquant présumé dans un message d'erreur.
      */
      unset($_SESSION['user']);
      unset($_SESSION['grade']);
      $erreur='Tentative de connexion depuis '.$_SERVER['REMOTE_ADDR'].' avec le cookie de '.$_SESSION['IP'];
      redir('auth.php',$erreur);
      exit();
      }
    else if(!($_SESSION['user-agent']===$_SERVER['HTTP_USER_AGENT'])){
      //Même fonctionnement qu'au dessus mais avec les User Agent.
      unset($_SESSION['user']);
      unset($_SESSION['grade']);
      redir('auth.php','Tentative de connexion depuis '.$_SERVER['HTTP_USER_AGENT'].' avec le cookie de '.$_SESSION['user-agent']);
      exit();
    }
    require_once 'inc/dhcp.php';
    $page=isset($_GET['page'])?$_GET['page']:'etat';
    /*
     * si aucune page n'est demandée, on envoie sur la page d'état du
     * serveur, sinon on envoie sur la page demandée
     */
	include_once 'inc/head.php';
    switch( $page ){
	case 'install':
		include_once 'inc/install.php';
		break;
	case 'plage':
		include_once 'inc/plage.php';
		break;
	case 'static':
		include_once 'inc/static.php';
		break;
	case 'nouvelle_plage':
		include_once 'inc/add_plage.php';
		break;
	case 'modif_conf':
		include_once 'inc/modif_conf.php';
		break;
	case 'etat':
		include_once 'inc/etat.php';
		break;
	case 'desactive':
		include_once 'inc/desactive.php';
		break;
	case 'active':
		include_once 'inc/active.php';
		break;
	case 'desinstall':
		include_once 'inc/desinstall.php';
		break;
	case 'utilisateurs':
		include_once 'inc/list_users.php';
		break;
	case 'deco':
		echo session_destroy()?'<article>vous avez été déconnecté</article>':'<article>Échec de la déconnexion, veuillez rééssayer</article>';
		/*
		 * on essaye de détruire les informations enregistrées dans
		 * la session, on affiche un message de réussite ou d'échec
		 * le cas échéant.
		 */
		header('refresh: 3;');
		/*
		 * on rafraichit la page au bout de trois secondes, on sera 
		 * redirigé vers la page d'authentification en cas de réussite
		 * ou une nouvelle tentative de déconnexion sera effectuée.
		 */
		break;
	default:
		//si la page demandée n'existe pas, on affiche un message d'erreur.
		echo '<article>Cette page n\'est pas disponible!</article>';
		break;
    }
    include_once 'inc/foot.php';
  }
  session_write_close();
  ob_end_flush(); // on vide le buffer
?>
