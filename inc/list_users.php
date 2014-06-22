<article>
<?php
  require_once 'inc/fonctionsBDD.php';
  if(isset($_SESSION['grade'])&& $_SESSION['grade']>2){
    if(isset($_GET['update'])){
      require_once 'inc/update_users.php';
    }
    if(isset($_GET['nbuppage'])){
      $NBUsersParPage=$_GET['nbuppage'];
      settype($NBUsersParPage,'integer');
    }
    else $NBUsersParPage=10;
    if(isset($_GET['npage'])){
      $NPage=$_GET['npage'];
      settype($NPage,'integer');
    }
    else $NPage=0;
    echo '<h3>Liste des Utilisateurs</h3>
	  <table>
	    <thead>
	      <tr>
		<th>Nom</th>
		<th>login</th>
		<th>grade</th>
		<th>courriel</th>
		<th>Activation</th>
	      </tr>
	    </thead>
	    <tbody>';
    foreach(getUsers(ConnexionBDD(),$NBUsersParPage,$NPage) as $user){
      echo '<tr>',
	'<td>',$user['nomuser'],'</td>',
	'<td>',$user['login'],'</td>',
	'<td>
	  <form action="?page=utilisateurs&nbuppage=',$NBUsersParPage,'&npage=',$NPage,'&update=grade" method="POST">
	    <input type="number" name="grade" value="',$user['refgrade'],'" min="0" max="3">
	    <input type="hidden" name="id" value="',$user['iduser'],'">
	    <input type="submit" value="Modifier">
	   </form>
	 </td>
	 <td>
	  <form action="?page=utilisateurs&nbuppage=',$NBUsersParPage,'&npage=',$NPage,'&update=mail" method="POST">
	    <input type="email" name="email" value="',$user['email'],'">
	    <input type="hidden" name="id" value="',$user['iduser'],'">
	    <input type="submit" value="Modifier">
	   </form>
	 </td>
	 <td>
	  <form action="?page=utilisateurs&nbuppage=',$NBUsersParPage,'&npage=',$NPage,'&update=etat" method="POST">
	    <input type="hidden" name="id" value="',$user['iduser'],'">';
	  if(!$user['actif']){
	    echo '<input type="hidden" name="actif" value="true">
	    <input type="submit" value="Activer">';
	  }
	  else{
	    echo '<input type="hidden" name="actif" value="false">
	    <input type="submit" value="Désactiver">';
	  }
	  echo '</form></td></tr>';
    }
    echo '</tbody></table>';
  }
  else echo '<span class="error">Vous n\'êtes pas assez gradé pour accèder à cette partie du site!</span>';
?>
</article>
<article>
  <h3>Ajouter un utilisateur</h3>
  <?php 
    if ($_SERVER['HTTPS'] !== "on") echo '<span class="error"> Pour des raisons de sécurité, n\'ajoutez pas d\'utilisateur par cette interface si vous n\'êtes pas connecté via HTTPS!</span>';
  ?>
  <form id="adduser" method="POST" action="?page=utilisateurs&nbuppage=<?php echo $NBUsersParPage;?>&npage=<?php echo $NPage?>&update=user">
    Nom : <input type="text" name="nom" placeholder="Nom"><br>
    Login : <input type="text" name="pseudo" placeholder="pseudonyme"><br>
    Courriel : <input type="email" name="mail" placeholder="user@domain.tld"> <br>
    Mot de Passe : <input type="password" name="pass"><br>
    Grade :<input type="number" name="grade" value=0 min="0" max="3"><br>
    Autorisation de se connecter : <input type="checkbox" name="actif" checked="checked"><br>
    <input type="reset" value="Effacer">&nbsp;<input type="submit" value="Ajouter">
  </form>
</article>