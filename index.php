<?php
  session_start();
function redir_auth($err){
  if($err!=null){$_SESSION['erreur']=$err;}
  header('HTTP/1.1 307 Temporary Redirect');
  header('Location: auth.php');
  header('Status: 307 Temporary Redirect');
  header('Content-Type: text/html; charset=UTF-8');
  header('refresh: 0;');
  echo '<!DOCTYPE html>
  <html>
    <head>
      <meta charset="utf-8">
      <meta http-equiv="refresh" content="0; url=auth.php">
    </head>
    <body>
      Vous devez être authentifié!
    </body>
  </html>';
  }
  if(!(isset($_SESSION['user']) && isset($_SESSION['grade']))){
    if(isset($_POST['keep'])){setcookie('user',$_POST['pseudo'],time()+(60*60*24*10));}//on garde le cookie 10 jours
    if(isset($_POST['pseudo']) && isset($_POST['pass']) && isset($_POST['challenge2'])){
      $h1=hash('sha512','azerty:1234');
      $h2=hash('sha512',$_SESSION['ch1']);
      $h3=hash('sha512',$_POST['challenge2']);
      if($_POST['pseudo']==='azerty' && $_POST['pass']===hash('sha512',hash('sha512','azerty:1234').':'.hash('sha512',$_SESSION['ch1']).':'.hash('sha512',$_POST['challenge2']))){
		$_SESSION['user']='test user';
		$_SESSION['grade']=4;
		$_SESSION['user-agent']=$_SERVER['HTTP_USER_AGENT'];
		$_SESSION['IP']=$_SERVER['REMOTE_ADDR'];
		if(isset($_SESSION['erreur'])){unset($_SESSION['erreur']);}
		header('refresh: 0;');
      }
      else{
	redir_auth('Mauvais Utilisateur/mot de passe');
      }
    }
    else{
      redir_auth();
    }
  }
  else{
    if(!($_SESSION['IP']===$_SERVER['REMOTE_ADDR'])){
      unset($_SESSION['user']);
      unset($_SESSION['grade']);
      redir_auth($_SESSION['erreur']='Tentative de connexion depuis '.$_SERVER['REMOTE_ADDR'].' avec le cookie de '.$_SESSION['IP']);
      exit();
      }
    if(!($_SESSION['user-agent']===$_SERVER['HTTP_USER_AGENT'])){
      unset($_SESSION['user']);
      unset($_SESSION['grade']);
      redir_auth($_SESSION['erreur']='Tentative de connexion depuis '.$_SERVER['REMOTE_ADDR'].' avec le cookie de '.$_SESSION['IP']);
      exit();
    }
  	require 'inc/dhcp.php';
    if( isset($_GET['debug']) ){ $debug=true; }
    if( isset($_GET['page']) ){ $page=$_GET['page'];}
    else{ $page='home'; }
    if($page=='deco'){header('refresh: 5;');}
	include 'inc/head.php';
    switch( $page ){
	case 'home':
		echo '<article>ceci est la page d\'accueil</article>';
		break;
	case 'install':
		include 'inc/install.php';
		break;
	case 'plage':
		include 'inc/plage.php';
		break;
	case 'static':
		include 'inc/static.php';
		break;
	case 'nouvelle_plage':
		include 'inc/add_plage.php';
		break;
	case 'modif_conf':
		include 'inc/modif_conf.php';
		break;
	case 'etat':
		include 'inc/etat.php';
		break;
	case 'desactive':
		include 'inc/desactive.php';
		break;
	case 'active':
		include 'inc/active.php';
		break;
	case 'desinstall':
		include 'inc/desinstall.php';
		break;
	case 'deco':
		unset($_SESSION['user'],$_SESSION['grade'],$_SESSION['user-agent'],$_SESSION['ip']);
		echo('<article>vous avez été déconnecté</article>');
		break;
	default:
		echo '<article>Cette page n\'est pas disponible!</article>';
		break;
    }
    include 'inc/foot.php';
  }
  session_write_close()
?>