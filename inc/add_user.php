<article>
  <?php 
  if(isset($_SESSION['grade']))if($_SESSION['grade']>2){
    /*
     * si l'utilisateur est autorisé à accèder à la page, on l'affiche,
     * sinon, on affiche un message d'erreur.
     */
    echo '<h3>Ajouter un utilisateur</h3>';
    if ($_SERVER['HTTPS'] !== "on") echo '<span class="error"> Pour des raisons de sécurité, n\'ajoutez pas d\'utilisateur par cette interface si vous n\'êtes pas connecté via HTTPS!</span>';
    //on affiche le message d'alerte si le server n'est pas configuré en HTTPS
    echo '<form id="adduser" method="POST" action="?page=utilisateurs&ampnbuppage=',$NBUsersParPage,'&amp;npage=',$NPage,'&amp;update=user">
    Nom : <input type="text" name="nom" placeholder="Nom"><br>
    Login : <input type="text" name="pseudo" placeholder="pseudonyme"><br>
    Courriel : <input type="email" name="mail" placeholder="user@domain.tld"> <br>
    Mot de Passe : <input type="password" name="pass"><br>
    Grade :<input type="number" name="grade" value=0 min="0" max="3"><br>
    Autorisation de se connecter : <input type="checkbox" name="actif" checked="checked"><br>
    <input type="reset" value="Effacer">&nbsp;<input type="submit" value="Ajouter">
  </form>'; //on affiche le formulaire
  }
  else{echo 'vous n\'êtes pas assez grader pour accèder à cette page';}
  ?>
</article>