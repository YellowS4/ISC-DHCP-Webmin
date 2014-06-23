<!--
 Code par Florian Audon & Jason Gantner
-->
<?php //code par Jason Gantner ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="initial-scale=0.5, user-scalable=yes, maximum-scale=1.0">
		<title>DHCP ADMIN</title>
		<link type="text/css" rel="stylesheet" href="styles/style.css">
		<?php
			  if($page==='plage') echo '<script  src="scripts/plage.js"></script>';
			  if($page==='modif_conf') echo '<script  src="scripts/modif_conf.js"></script>';
		?>
	</head>
	<body lang="fr">
		<div id="left">
			<header>
			  <img src="images/logo.png" alt="logo">
			</header>
			<nav>
				<?php 
				if(is_install()){
				  /*
				   * Si le server est installé on affiche le menu complet,
				   * on affiche les liens selons le grade de l'utilisateur.
				   * On remplace le href par un id="current" si il s'agit de la page active.
				   */
				  if($_SESSION['grade']>0){
					echo '<a ',(($page==='etat')?'id="current"':'href="index.php?page=etat"'),'>État du serveur</a><br>';
				  }
				  if($_SESSION['grade']>1){
					echo '<a ',(($page==='plage')?'id="current"':'href="index.php?page=plage"'),'>Générer une déclaration de subnet</a><br>';
					echo '<a ',(($page==='modif_conf')?'id="current"':'href="index.php?page=modif_conf"'),'>Modification d\'une configuration</a><br>';
				    echo '<a ',(($page==='modif_int')?'id="current"':'href="index.php?page=modif_int"'),'>Modification des interfaces d\'écoute</a><br>';
				  }
				  if($_SESSION['grade']>2){
					echo '<a ';
					$active=is_run();
					if($page==='active'||$page==='desactive')echo 'id="current"';
					else echo 'href="index.php?page=',($active?'desactive"':'active"');
					echo '>',($active?'Désactivation':'Activation'),' du serveur</a><br>';
					echo '<a ',(($page==='desintall')?'id="current"':'href="index.php?page=desinstall"'),'>Désinstallation du serveur</a><br>';
					echo '<hr>';
					echo '<a ',(($page==='utilisateurs')?'id="current"':'href="index.php?page=utilisateurs"'),'>Gérer les utilisateurs</a><br>';
				  }
				}
				else if($_SESSION['grade']>2){
				  echo '<a ',($page==='install')?'id="current"':'href="index.php?page=install"','>Installation du serveur</a><br>';
				  }
				?>
				<hr>
				<a href="index.php?page=deco">Déconnexion</a><br>
			</nav>
		</div>
		<section>