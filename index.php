<?php
  session_start();
  if(!(isset($_SESSION['user']) && isset($_SESSION['grade']))){
    if(isset($_POST['keep'])){setcookie('user',$_POST['pseudo'],time()+(60*60*24*10));}//on garde le cookie 10 jours
    if(isset($_POST['pseudo']) && isset($_POST['pass']) && isset($_POST['challenge2'])){
      require_once 'inc/fonctionsBDD.php';
      $bdd=ConnexionBDD();
      $h1=hash('sha512',gethash($bdd,$_POST['pseudo']));
      $h2=hash('sha512',$_SESSION['ch1']);
      $h3=hash('sha512',$_POST['challenge2']);
      if($_POST['pseudo']==='azerty' && $_POST['pass']===hash('sha512',hash('sha512','azerty:1234').':'.$h2.':'.$h3)){
		$res=($bdd,$_POST['pseudo']);
		print_r($res);
		$_SESSION['user']='test user';
		$_SESSION['grade']=4;
		$_SESSION['user-agent']=$_SERVER['HTTP_USER_AGENT'];
		$_SESSION['IP']=$_SERVER['REMOTE_ADDR'];
		if(isset($_SESSION['erreur'])){unset($_SESSION['erreur']);}
		header('refresh: 0;');
      }
      else{
	require_once 'inc/fonctions_generales.php';
	redir('auth.php','Mauvais Utilisateur/mot de passe');
      }
    }
    else{
      require_once 'inc/fonctions_generales.php' ;
      redir('auth.php');
    }
  }
  else{
    if(!($_SESSION['IP']===$_SERVER['REMOTE_ADDR'])){
      unset($_SESSION['user']);
      unset($_SESSION['grade']);
      require_once 'inc/fonctions_generales.php';
      redir('auth.php','Tentative de connexion depuis '.$_SERVER['REMOTE_ADDR'].' avec le cookie de '.$_SESSION['IP']);
      exit();
      }
    if(!($_SESSION['user-agent']===$_SERVER['HTTP_USER_AGENT'])){
      unset($_SESSION['user']);
      unset($_SESSION['grade']);
      require_once 'fonctions_generales.php';
      redir('auth.php','Tentative de connexion depuis '.$_SERVER['REMOTE_ADDR'].' avec le cookie de '.$_SESSION['IP']);
      exit();
    }
    require_once 'inc/dhcp.php';
    if( isset($_GET['debug']) ){ $debug=true; }
    if( isset($_GET['page']) ){ $page=$_GET['page'];}
    else{ $page='home'; }
    if($page=='deco'){header('refresh: 5;');}
	include_once 'inc/head.php';
    switch( $page ){
	case 'home':
		echo '<article>ceci est la page d\'accueil</article>';
		break;
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
	case 'deco':
		unset($_SESSION['user'],$_SESSION['grade'],$_SESSION['user-agent'],$_SESSION['ip']);
		echo '<article>vous avez été déconnecté</article>';
		break;
	default:
		echo '<article>Cette page n\'est pas disponible!</article>';
		break;
    }
    include_once 'inc/foot.php';
  }
  session_write_close()
?>