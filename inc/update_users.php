<?php
switch($_GET['update']){
	case 'etat':
	  if($_POST['actif']==='true'||$_POST['actif']==='false'){
	    settype($_POST['id'],'integer');
	    if(changeActif(ConnexionBDD(),$_POST['id'],$_POST['actif'])) echo 'Changement effectué';
	    else printErrors('échec du changement d\'autorisation');
	    break;
	  }
	  else printErrors('Le formulaire reçu ne convient pas à celui attendu');
	case 'grade':
	  settype($_POST['grade'],'integer');
	  if($_POST['grade']>=0 && $_POST['grade']<=3){
	    settype($_POST['id'],'integer');
	    if(changeGrade(ConnexionBDD(),$_POST['id'],$_POST['grade'])) echo 'Changement effectué';
	    else printErrors('échec du changement de grade');
	    break;
	  }
	  else printErrors('Le formulaire reçu ne convient pas à celui attendu');
	  break;
	case 'mail':
	  $mail=filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
	  if(filter_var($mail, FILTER_VALIDATE_EMAIL)){
	    settype($_POST['id'],'integer');
	    if(changeMail(ConnexionBDD(),$_POST['id'],$mail)) echo 'Changement effectué';
	    else printErrors('échec du changement de l\'adresse de courriel');
	    break;
	  }
	  else printErrors('L\'adresse de courriel reçue n\'est pas reconnue comme valide.');
	  break;
	case 'user':
	  $all_good=true;
	  $errors=Array();
	  if(preg_match("@((\w+\s)+\w+)@",ucwords($_POST['nom']),$matches)) $nom=$matches[1];
	  else {
	    $all_good=false;
	    $errors[]='Le nom fourni n\'est pas valide.';
	  }
	  $login=htmlspecialchars($_POST['pseudo']);
	  $mail=filter_var($_POST['mail'], FILTER_SANITIZE_EMAIL);
	  if(!filter_var($mail, FILTER_VALIDATE_EMAIL)){
	    $all_good=false;
	    $errors[]='L\'adresse de courriel reçue n\'est pas reconnue comme valide.';
	  }
	  $hash=hash('sha512',$login.':'.$_POST['pass']);
	  settype($_POST['grade'],'integer');
	  if(!($_POST['grade']<0||$_POST['grade']>3)) $grade=$_POST['grade'];
	  else {
	    $all_good=false;
	    $errors[]='Le grade reçu n\'est pas reconnu comme valide.';
	  }
	  if($_POST['actif']==='on') $actif='true';
	  else{
	    echo '<span class="error">Attention, le compte est créé désactivé!<br>
	    Il ne sera pas possible de se connecter avec.</span>';
	    $actif='false';
	  }
	  if($all_good){
	    if(addUser(ConnexionBDD(),$nom,$login,$mail,$hash,$grade,$actif)) echo 'Utilisateur ',$login,' créé.';
	    else printErrors('échec de l\'enregistrement');
	  }
	  else printErrors($errors);
	  break;
	default:
	  echo 'Cette action n\'est pas disponible';
	  break;
      }
      echo '</article><article>';
?>