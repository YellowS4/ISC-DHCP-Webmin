<?php
/*
 * Code par Jason Gantner
 */
  require_once('inc/fonctions_generales.php');
  session_start();
  // si l'utilisateur est connecté, on le renvoie sur l'index
  if(isset($_SESSION['user'])&&isset($_SESSION['grade'])){
    redir('index.php');
    exit();
  }
?>
<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8">
    <title> Authentification </title>
    <link type="text/css" rel="stylesheet" href="styles/auth.css">
    <script type="application/javascript" src="https://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha512.js">
	//inclusion du code pour le sha512 et la génération de nimbre aléatoire
    </script>
    <script src="scripts/auth.js">
	//inclusion du script pour l'authentification
    </script>
  </head>
  <body lang="fr">
  <noscript class="error" id="noJS">L'authentification sera impossible sans JavaScript!</noscript><br>
    <?php 
      if(isset($_SESSION['erreur'])){
	print '<span class="error">'.$_SESSION['erreur'].'</span><br>';
      }
    ?>
    <form action="index.php" method="post" onsubmit="digest();">
      Veuillez vous connecter:
      <input type="text" name="pseudo" placeholder="Nom d'utilisateur" id="pseudo" <?php print isset($_COOKIE['user'])?'value="'.$_COOKIE['user'].'"':'';?> >
      <input type="password" name="pass" placeholder="Mot de passe" id="pass">
      <input type="hidden" name="challenge1" value="<?php
      $_SESSION['ch1']=base64_encode(openssl_random_pseudo_bytes(16));
      print $_SESSION['ch1'];
      ?>" id="ch1">
      <input type="hidden" name="challenge2" id="ch2">
      <input type="submit" value="Se connecter"><br>
      <input type="checkbox" name="keep"> Mémoriser l'utilisateur
    </form>
  </body>
</html>
<?php  session_write_close(); ?>