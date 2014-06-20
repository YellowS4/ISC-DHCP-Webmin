<?php
  require_once 'inc/fonctions_generales.php';
  ob_start();
  session_start();
  if(!(isset($_SESSION['user']) && isset($_SESSION['grade']))){
    if(isset($_POST['keep'])){setcookie('user',$_POST['pseudo'],time()+(60*60*24*10));}//on garde le cookie 10 jours
    if(isset($_POST['pseudo']) && isset($_POST['pass']) && isset($_POST['challenge2'])){
      require_once 'inc/fonctionsBDD.php';
      $bdd=ConnexionBDD();
      $hres=getHash($bdd,$_POST['pseudo'])['h1'];
      if(isset($hres['h1'])) $h1=$hres['h1'];
      else{
	redir('auth.php','Mauvais Utilisateur/mot de passe');
	exit();
      }
      $h2=hash('sha512',$_SESSION['ch1']);
      $h3=hash('sha512',$_POST['challenge2']);
      if($_POST['pass']===hash('sha512',$h1.':'.$h2.':'.$h3)){
		$res=getUser($bdd,$_POST['pseudo']);
		$_SESSION['user']=$res['nomuser'];
		$_SESSION['grade']=$res['refgrade'];
		$_SESSION['id']=$res['id'];
		$_SESSION['user-agent']=$_SERVER['HTTP_USER_AGENT'];
		$_SESSION['IP']=$_SERVER['REMOTE_ADDR'];
		if(isset($_SESSION['erreur'])){unset($_SESSION['erreur']);}
		session_regenerate_id()
		header('refresh: 0;');
      }
      else{
	redir('auth.php','Mauvais Utilisateur/mot de passe');
      }
    }
    else{
      redir('auth.php');
    }
  }
  else{
    if(!($_SESSION['IP']===$_SERVER['REMOTE_ADDR'])){
      unset($_SESSION['user']);
      unset($_SESSION['grade']);
      redir('auth.php','Tentative de connexion depuis '.$_SERVER['REMOTE_ADDR'].' avec le cookie de '.$_SESSION['IP']);
      exit();
      }
    if(!($_SESSION['user-agent']===$_SERVER['HTTP_USER_AGENT'])){
      unset($_SESSION['user']);
      unset($_SESSION['grade']);
      redir('auth.php','Tentative de connexion depuis '.$_SERVER['REMOTE_ADDR'].' avec le cookie de '.$_SESSION['IP']);
      exit();
    }
    require_once 'inc/dhcp.php';
    $page=isset($_GET['page'])?$_GET['page']:'etat';
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
	case 'lister_utilisateurs':
		include_once 'inc/list_users.php';
		break;
	case 'deco':
		echo session_destroy()?'<article>vous avez été déconnecté</article>':'<article>Échec de la déconnexion, veuillez rééssayer</article>';
		header('refresh: 3;');
		break;
	default:
		echo '<article>Cette page n\'est pas disponible!</article>';
		break;
    }
    include_once 'inc/foot.php';
  }
  session_write_close();
  ob_end_flush();
?>