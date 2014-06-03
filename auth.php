<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8">
    <title> Authentification </title>
    <link type="text/css" rel="stylesheet" href="styles/auth.css">
    <script>
      //TODO Digest auth
    </script>
  </head>
  <body lang="fr">
    <?php 
      if(isset($_SESSION['erreur'])){
	print '<span class="error">'.$_SESSION['erreur'].'</span><br>';
	unset($_SESSION['erreur']);
      }
    ?>
    <form action="index.php" method="post">
      Veuillez vous connecter:
      <input type="text" name="pseudo" placeholder="Nom d'utilisateur" id="pseudo">
      <input type="password" name="pass" placeholder="Mot de passe">
      <input type="submit" value="Se connecter" onsubmit="digest();">
    </form>
  </body>
</html>