<article>
<?php
  require_once 'inc/fonctionsBDD.php';
  if(isset($_SESSION['grade'])&& $_SESSION['grade']>2){
    if(isset($_GET['nbuppage'])){
      $NBUsersParPage=$_GET['nbuppage'];
      settype($NBUsersParPage,'integer');
    }
    else $NBUsersParPage=10;
    if(isset($_GET['npage'])){
      $NPage=$_GET['npage'];
      settype($NPage,'integer');
    }
    else $Npage=0;
    echo '<table><th>Utilisateurs</th>';
    foreach(getUsers(ConnexionBDD(),$NBUsersParPage,$NPage) as $user){
      echo '<tr>',
	'<td>',$user['Nom'],'</td>',
	'<td>',$user['login'],'</td>',
	'<td>
	  <form action="?page=lister_utilisateurs&nbuppage=',$NBUsersParPage,'&npage=',$NPage,'&update=mail" method="POST">
	    <input type="email" name="email" value="',$user['email'],'">
	    <input type="hidden" name="id" value="',$user['iduser'],'">
	    <input type="submit" value="Modifier">
	   </form>
	 </td>
	 <td>
	  <form action="?page=lister_utilisateurs&nbuppage=',$NBUsersParPage,'&npage=',$NPage,'&update=etat" method="POST">
	    <input type="hidden" name="id" value="',$user['iduser'],'">';
	  if(!$user['actif']){
	    echo '<input type="hidden" name="actif" value="true">
	    <input type="submit" value="Activer">';
	  }
	  else{
	    echo '<input type="hidden" name="actif" value="false">
	    <input type="submit" value="Désactiver">';
	  }
	  echo '</form>';
    }
    
    echo '</table>';
  }
  else echo 'Vous n\'êtes pas assez gradé!';
?>
</article>