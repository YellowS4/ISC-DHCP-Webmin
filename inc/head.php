<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>DHCP ADMIN</title>
<link type="text/css" rel="stylesheet" href="styles/style.css">
<?php print ($page==="plage")?'<script  src="scripts/plage.js"></script>':'';?>
</head>
<body lang="fr">
<div id="left">
<header>
  Web Admin DHCP
</header>
<nav>
<a href="index.php?page=etat">État du serveur</a><br>
<?php 
if(is_install()){
  if($_SESSION['grade']>0){
    print '<a href="index.php?page=static">Ajouter des IPs statiques</a><br>';
  }
  if($_SESSION['grade']>1){
    print '<a href="index.php?page=plage">Ajouter une Plage</a><br>';
    print '<a href="index.php?page=modif_conf">Modification d\'une configuration</a><br>';
  }
  if($_SESSION['grade']>2){
    print '<a  href="index.php?page='.is_activate()?'desactive">Désactivation':'active">Activation'.' du serveur</a><br>';
    print '<a href="index.php?page=desinstall">Désinstallation du serveur</a><br>';
  }
}
else{
  print '<a href="index.php?page=install">Installation du serveur</a><br>';
  }
?>
<a href="index.php?page=deco">Déconnexion</a><br>
</nav>
</div>
<section>