<!--
 Code par Jason Gantner
-->
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>DHCP ADMIN</title>
		<link type="text/css" rel="stylesheet" href="styles/style.css">
		<?php 
			  echo ($page==="plage")?'<script  src="scripts/plage.js"></script>':'';
			  echo ($page==="modif_conf")?'<script  src="scripts/modif_conf.js"></script>':'';
		?>
	</head>
	<body lang="fr">
		<div id="left">
			<header>
			  <img src="images/logo.png">
			</header>
			<nav>
				<?php 
				if(is_install()){
				  if($_SESSION['grade']>0){
					echo '<a ',(($page=='etat')?'id="current"':'href="index.php?page=etat"'),'>État du serveur</a><br>';
				  }
				  if($_SESSION['grade']>1){
					echo '<a ',(($page=='plage')?'id="current"':'href="index.php?page=plage"'),'">Ajouter une Plage</a><br>';
					echo '<a ',(($page=='modif_conf')?'id="current"':'href="index.php?page=modif_conf"'),'>Modification d\'une configuration</a><br>';
				  }
				  if($_SESSION['grade']>2){
					echo '<a ',(($page=='active'||$page=='desactive')?'id="current"':('href="index.php?page="'.(is_activate()?'desactive">Désactivation':'active">Activation'))),' du serveur</a><br>';
					echo '<a ',(($page=='desintall')?'id="current"':'href="index.php?page=desinstall"'),'>Désinstallation du serveur</a><br>';
					echo '<hline>';
					
				  }
				}
				else if($_SESSION['grade']>2){
				  echo '<a ',($page=='install')?'id="current"':'href="index.php?page=install"','>Installation du serveur</a><br>';
				  }
				?>
				<a href="index.php?page=deco">Déconnexion</a><br>
			</nav>
		</div>
		<section>