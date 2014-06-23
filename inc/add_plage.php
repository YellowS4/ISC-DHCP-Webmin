<article>
<?php
if(isset($_SESSION['grade']) && $_SESSION['grade']>1){
  require_once 'inc/network.php';
  require_once 'inc/fonctions_generales.php';
  echo '<h3>Résultat de la génération de la plage</h3>';
  $error=Array();
  // verification des entrées
  $all_good = true;//variable pour savoir si il y a des erreurs ou pas
  if( isset($_POST['subnet']) && isset($_POST['debut']) && isset($_POST['fin']) && isset($_POST['mask']) ){
    //si les variables nécéssaires sont fournies
    if( preg_match($regIP4,$_POST['subnet'],$subnet) and preg_match($regIP4,$_POST['debut'],$debut) and preg_match($regIP4,$_POST['fin'],$fin) and settype($_POST['mask'],'int') ){
      $subnet = $subnet[0];
      $debut = $debut[0];
      $fin = $fin[0];
      if($_POST['mask']<0 || $_POST['mask']>32){
	// si le masque reçu n'est pas valide on ajoute une erreur au tableau
	$error[]='Le masque est invalide (non compris entre 0 et 32)';
	$all_good = false;
      }
      else{
	//sinon on le convertit
	$mask = $_POST['mask'];
	$maski = 0x0;
	for($i=0;$i<32;$i++){
	  //c'est ici que la magie opère
	  if($i<=$mask) $maski += 1;
	  $maski<<=1;
	}
      }
    }
    else{
      $error[] = 'Veuillez entrer correctement les données dans le formulaire!';
      $all_good = false;
    }
  }
  else{
    $error[] = 'Veuillez renseigner les champs Subnet, début et fin du <a href="?page=plage">formulaire</a>';
    $all_good = false;
  }
  if($all_good && isset($maski)){
    /*
     * si tout va bien et qu'on a calculé le masque, on verifie 
     * que les addresse soien dans le subnet
     */
    $debInSub=addr_in_subnet($debut,$subnet,$maski);
    if(!$debInSub){
      if(gettype($debInSub)==='boolean'){
	$error[]="Le début n'est pas dans le subnet avec un masque /".$mask;
	$all_good = false;
      }
      else printErrors($debInSub);
    }
    $finInSub=addr_in_subnet($fin,$subnet,$maski);
    if(!$finInSub){
      if(gettype($finInSub)==='boolean'){
	$error[]="La fin n'est pas dans le subnet avec un masque /".$mask;
	$all_good = false;
      }
      else  printErrors($finInSub);
    }
  }
  if(!$all_good){
    printErrors($error);
  }
  else{
    //on génère le début de la configuration si tout va bien
    echo 'Vous pouvez ajouter ou remplacer ceci dans la configuration actuelle du serveur:<br>';
    $rule='subnet '.$subnet.' netmask '. int2decPointIP($maski)." {\n";
    $rule.="\trange ".$debut.' '.$fin.";\n";
    if(isset($_POST['check_routers']))if ($_POST['check_routers']==='on'){
      //si l'option est cochée, on vérifie la variable reçue et on l'ajoute à la conf
      if(preg_match($regIP4,$_POST['routeur']))if(addr_in_subnet($_POST['routeur'],$subnet,$maski)){
	$rule.="\toption routeurs ".$_POST['routeur'].";\n";
      }
      else $error[]='l\'adresse du routeur n\'est pas valide';
    }
    if(isset($_POST['check_DNS']))if($_POST['check_DNS']==='on'){
      //si l'option est cochée, on vérifie la variable reçue et on l'ajoute à la conf
      if(preg_match('@(\h*,?\h*([0-2]?[0-9]{1,2}\.){3}[0-2]?[0-9]{1,2}\h*,?\h*)+\h*@',$_POST['DNS'],$DNS)){
	$rule.="\toption domain-name-servers ".$DNS[1].";\n";
      }
      else $error[]='l\'adresse du serveur de noms n\'est pas valide';
    }
    if(isset($_POST['check_domain']))if($_POST['check_domain']==='on'){
      //si l'option est cochée, on vérifie la variable reçue et on l'ajoute à la conf
      if(preg_match('@[a-z0-9\-\_]+(\.[a-z0-9\-\_]+)*@',$_POST['domain'],$matches)){
	$rule.="\toption domain-name ".$matches[0].";\n";
      }
      else $errors[]='le nom de domaine n\'est pas valide';
    }
    $rule.="}\n";
    echo '<pre>',$rule,'</pre>';//on affiche la règle une fois générée
    if(count($error)>0) printErrors($error);//on affiche les erreurs si il y en a eu
    echo 'Le bouton appliquer remplacera la configuration actuelle par celle ci-dessus :
    <form action="?page=plage&apply" method="POST" id="next">
      <input type="hidden" name="conf" value="',$rule,'">
      <input type="submit" value="Appliquer">
    </form>';//on affiche le bouton pour appliquer et un message de prévention
  }
}
else echo '<span class="error">Vous n\'êtes pas assez gradé pour accèder à cette partie du site!</span>';
?>
</article>