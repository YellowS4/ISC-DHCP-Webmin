<?php
function redir_auth($err){
  header('HTTP/1.1 307 Temporary Redirect');
  header('Location: auth.php'.($err!='')?'?erreur='.$err:'');
  header('Status: 307 Temporary Redirect');
  header('Content-Type: text/html; charset=UTF-8');
  print '<!DOCTYPE html>
  <html>
    <head>
      <meta charset="utf-8">
      <meta http-equiv="refresh" content="0; url=auth.php'.(($err!='')?'?erreur='.$err:'').'">
    </head>
    <body>
      Vous devez être authentifié!
    </body>
  </html>';
  }
  session_start();
  if(!(isset($_SESSION['user']) && isset($_SESSION['grade']))){
    if(isset($_POST['pseudo']) && isset($_POST['pass']) ){
      if($_POST['pseudo']==="azerty" && $_POST['pass']==="1234"){
	$_SESSION['user']='test user';
	$_SESSION['grade']=4;
	header("refresh: 0;");
      }
      else{
	redir_auth("Mauvais Utilisateur/mot de passe");
      }
    }
    else{
      redir_auth(null);
    }
  }
  else{
    include 'inc/head.php';
    if( isset($_GET['debug']) ){ $debug=true; }
    if( isset($_GET['page']) ){ $page=$_GET['page'];}
    else{ $page='home'; }
    switch( $page ){
      case 'home':
	print 'ceci est la page d\'accueil';
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
      case 'desactive':
	include 'inc/desactive.php';
	break;
      case 'desinstall':
	include 'inc/desinstall.php';
	break;
      case 'etat':
	include 'inc/etat.php';
	break;
      case 'deco':
	unset($_SESSION['user'],$_SESSION['grade'],$_SESSION['user-agent'],$_SESSION['ip']);
	print('vous avez été déconnecté');
	break;
      default:
	print 'not available';
	break;
    }
    include 'inc/foot.php';
  }
  session_write_close()
?>