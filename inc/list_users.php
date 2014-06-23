<?php 
  require_once 'inc/fonctionsBDD.php';
  if(isset($_GET['update'])) require_once 'inc/update_users.php';
?>
<article>
<?php
  if(isset($_SESSION['grade']))if($_SESSION['grade']>2){
    /*
     * Si la personne as un grade suffisament élevé on affiche la page
     * sinon on affiche un message d'erreur.
     */
    if(isset($_GET['nbuppage'])&&$_GET['nbuppage']>0){
      // on récupère le nombre max d'utilisateurs par page si supérieur à zéro
      $NBUsersParPage=$_GET['nbuppage'];
      settype($NBUsersParPage,'integer');
    }
    else $NBUsersParPage=10; //valeur par défaut si non fourni
    $conn=ConnexionBDD(); // on se connecte à la base de données
    $maxPages=floor(totalUsers($conn)/$NBUsersParPage); // on calcule le noumbre de pages
    if(isset($_GET['npage']))if($_GET['npage']>=0&&$_GET['npage']<=$maxPages){
     //on récupère le numéro de la page demandée si compris entre 0 et le nombre max
      $NPage=$_GET['npage'];
      settype($NPage,'integer');
    }
    else $NPage=0;// valeur par défaut si non fournie
    echo '<h3>Liste des Utilisateurs</h3>
	  <form action="index.php" method="GET" id="prev">
	    <input type="hidden" name="page" value="utilisateurs">
	    <input type="hidden" name="npage" value="',$NPage,'">
	    Nombre d\'utilisateurs par pages:
	    <input type="number" name="nbuppage" value="',$NBUsersParPage,'" min="1">
	    <input type="submit" value="Actualiser">
	  </form>
	  <table>
	    <thead>
	      <tr>
		<th>Nom</th>
		<th>login</th>
		<th>grade</th>
		<th>courriel</th>
		<th>Activation</th>
		<th>Suppression</th>
	      </tr>
	    </thead>
	    <tbody>';//on affiche le haut du tableau
    foreach(getUsers($conn,$NBUsersParPage,$NPage*$NBUsersParPage) as $user){
      // pour chaque utilisateur on affiche  une ligne du tableau
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
	  echo '</form>
	      </td>
	      <td>
		<form action="?page=utilisateurs&nbuppage=',$NBUsersParPage,'&npage=',$NPage,'&update=rmuser" method="POST">
		  <input type="hidden" name="id" value="',$user['iduser'],'">
		  <input type="submit" value="Supprimer">
		</form>
	  </tr>';
    }
    echo '</tbody>
	</table>
	<div id="navigation">';
    if($NPage>0) echo '<form action="index.php" method="GET" id="prev">
	    <input type="hidden" name="page" value="utilisateurs">
	    <input type="hidden" name="npage" value="',$NPage-1,'">
	    <input type="hidden" name="nbuppage" value="',$NBUsersParPage,'">
	    <input type="submit" value="Page précédente">
	  </form>';// si on n'est pas sur la première page, on affiche le bouton page précédente.
    echo '<form action="index.php" method="GET">
	    <input type="hidden" name="page" value="utilisateurs">
	    Page :
	    <input type="number" name="npage" value="',$NPage,'" min="0" max="',$maxPages,'">/',$maxPages,'
	    <input type="hidden" name="nbuppage" value="',$NBUsersParPage,'">
	    <input type="submit" value="Aller">
	  </form>';// input pour selectionner la page désirée
    if($NPage<$maxPages) echo '<form action="index.php" method="GET" id="next">
	    <input type="hidden" name="page" value="utilisateurs">
	    <input type="hidden" name="npage" value="',$NPage+1,'">
	    <input type="hidden" name="nbuppage" value="',$NBUsersParPage,'">
	    <input type="submit" value="Page suivante">
	  </form>';//si on est pas sur la dernière page, on affiche le bonton page suivante.
    echo '</div>';
  }
  else echo '<span class="error">Vous n\'êtes pas assez gradé pour accèder à cette partie du site!</span>';
?>
</article>
<?php include_once('inc/add_user.php');// on inclue l'article ajout d'utilisateur?>