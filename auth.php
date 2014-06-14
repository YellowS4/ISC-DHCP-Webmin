<?php
  session_start();
  if(isset($_SESSION['user'])&&isset($_SESSION['grade'])){
    header('HTTP/1.1 307 Temporary Redirect');
    header('Location: index.php');
    header('Status: 307 Temporary Redirect');
    header('Content-Type: text/html; charset=UTF-8');
    header('refresh: 0;');
    exit();
  }
?>
<!--
  code par Jason Gantner
-->
<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8">
    <title> Authentification </title>
    <link type="text/css" rel="stylesheet" href="styles/auth.css">
    <script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha512.js">//inclusion du code pour le sha512</script>
    <script>
      function init(){
	document.getElementById("noJS").style.display="none";
      }
      function digest(){
	//initialisation de variables pour la lisibilité
	pseudo = document.getElementById("pseudo");
	password = document.getElementById("pass");
	chall1 = document.getElementById("ch1");
	chall2 = document.getElementById("ch2");
	//génération du challenge2 en hexa
	chall2.value = CryptoJS.lib.WordArray.random(16).toString();
	//hash de "user:pass"
	var h1 = CryptoJS.SHA512(pseudo.value+':'+password.value).toString(CryptoJS.enc.Hex);
	//hash de chall1 décodé de la base 64
	var h2 = CryptoJS.SHA512(chall1.value).toString(CryptoJS.enc.Hex);
	//hash de chall2 décodé de la base 64
	var h3 = CryptoJS.SHA512(chall2.value).toString(CryptoJS.enc.Hex);
	//on remplace le pass par le hash de h1:h2:h3 encodé en hexadécimal
	password.value = CryptoJS.SHA512(h1+":"+h2+":"+h3).toString(CryptoJS.enc.Hex);
	//on autorise l'envoie du formulaire
	return true;
      }
    </script>
  </head>
  <body lang="fr" onload="init();">
  <span class="error" id="noJS">L'Authentification sera impossible sans JavaScript!</span><br>
    <?php 
      if(isset($_SESSION['erreur'])){
	print '<span class="error">'.$_SESSION['erreur'].'</span><br>';
	unset($_SESSION['erreur']);
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