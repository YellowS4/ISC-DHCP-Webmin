<?php
/*
 * Code par Jason Gantner
 */

//fonction de redirection prenant la page de destination en argument et l'erreur provoquant la redirection si c'est le cas
function redir($page,$err){
  //si besoin, on stocke l'erreur dans la session
  if($err!==null){$_SESSION['erreur']=$err;}
  //on envoie une réponse de redirection HTTP au client
  header('HTTP/1.1 307 Temporary Redirect');
  header('Location: '.urlencode($page));
  header('Status: 307 Temporary Redirect');
  header('Content-Type: text/html; charset=UTF-8');
  header('refresh: 0;'); //permet de faire la redirection de manière transparente
  //on renvoie un document au client permettant de faire une redirection par le HTML si la redirection HTTP n'est pas honorée
  echo '<!DOCTYPE html>
  <html>
    <head>
      <meta charset="utf-8">
      <meta http-equiv="refresh" content="0; url=',$page,'">
    </head>
    <body>
      Vous devez être authentifié!
    </body>
  </html>';
}

//fonction pour afficher une ou plusieurs erreurs, prend un tableau en argument
function printErrors($errors){
  settype($errors,'array');
  echo '<span class="error">';
  switch( count($errors)){
    case 0:
      echo 'il n\'y a pas eu d\'erreurs.';
      break;
    case 1:
      echo 'Erreur:',$errors[0];
      //on sépare les éléments à afficher avec une virgule, plus rapide que de les concaténer
      break;
    default:
      //on affiche les erreurs dans une liste
      echo 'Erreurs :<ul class="error">';
      foreach($errors as $error){
	echo '<li>',$error,'</li>';
      }
      echo '</ul>';
      break;
  }
  echo '</span>';
}

//expression régulière pour une IPv4
$regIP4 = "@^((25[0-5]|2[0-4][0-9]|[0-1]?[0-9]{1,2})\.){3}(25[0-5]|2[0-4][0-9]|[0-1]?[0-9]{1,2})$@";
?>