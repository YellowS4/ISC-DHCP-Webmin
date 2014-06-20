<article>
<?php
  echo '<pre>';
  print_r($_POST);
  echo '</pre>';
  require_once 'inc/network.php';
  require_once 'inc/fonctions_generales.php';
  $error=Array();
  // verification des entrées
  $all_good = true;//variable pour savoir si il y a des erreurs ou pas
  $regIP4 = "@^((25[0-5]|2[0-4][0-9]|[0-1]?[0-9]{1,2})\.){3}(25[0-5]|2[0-4][0-9]|[0-1]?[0-9]{1,2})$@"; //expression régulière pour une IPv4
  if( isset($_POST['subnet']) && isset($_POST['debut']) && isset($_POST['fin']) && isset($_POST['mask']) ){
    if( preg_match($regIP4,$_POST['subnet'],$subnet) and preg_match($regIP4,$_POST['debut'],$debut) and preg_match($regIP4,$_POST['fin'],$fin) and settype($_POST['mask'],'int') ){
      $subnet = $subnet[0];
      $debut = $debut[0];
      $fin = $fin[0];
      $mask = $_POST['mask'];
    }
    else{
      $error[] += 'Veuillez entrer correctement les données dans le formulaire!';
      $all_good = false;
    }
  }
  else{
    $error[] += 'Veuillez renseigner les champs Subnet, début et fin du <a href="?page=plage">formulaire</a>';
    $all_good = false;
  }
  if( isset($mask) && !(mask<0 || mask>32) ){
    $error[]+='Le masque est invalide (non compris entre 0 et 32)';
    $all_good = false;
  }
  else{
    $maski = 0x0;
    for($i=0;$i<32;$i++){
      if($i<$mask){
	$maski += 1;
      }
      $maski<<=1;
    }
  }
  if($all_good && isset($maski)){
    if(addr_in_subnet($debut,$subnet,$mask)){
      $error[]="Le début n'est pas dans le subnet avec un masque /".$mask;
      $all_good = false;
    }
    if(addr_in_subnet($fin,$subnet,$mask)){
      $error[]="La fin n'est pas dans le subnet avec un masque /".$mask;
      $all_good = false;
    }
  }
  if(!$all_good){
    printErrors($error);
  }
  else{
    $rule='subnet '.$subnet.' netmask '. int2decPointIP($maski)." {\n";
    $rule.="\trange ".$debut.' '.$fin.";\n";
    if(preg_match($regIP4,$_POST['routeur']) && addr_in_subnet($_POST['routeur'],$subnet,$mask)){
      $rule.="\toption routeurs ".$_POST['routeur'].";\n";
    }
    //TODO ajouter DNS et domaine
//    if(preg_match('@(\h*,?\h*([0-2]?[0-9]{1,2}\.){3}[0-2]?[0-9]{1,2}\h*,?\h*)+\h*@',$_POST['DNS'])
    $rule.="}\n";
    echo '<pre>',$rule,'</pre>';
  }
?>
</article>