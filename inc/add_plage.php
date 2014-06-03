<article>
<?php
  require 'inc/network.php';
  $error=Array();
  print '<pre>';
  print_r($_POST);
  print '</pre>';
  function printErrors($errors){
    settype($errors,'array');
    switch( count($errors)){
      case 0:
	print "il n'y a pas eu d'erreurs.";
	break;
      case 1:
	print "Erreur:".$errors[0];
	break;
      default:
	print 'Erreurs :<ul class="error">';
	foreach($errors as $error){
	  print '<li>'.$error.'</li>';
	}
	print '</ul>';
	break;
    }
  }
  // verification des entrées
  $error=[];
  $all_good = true;
  $regIP4 = "@([0-2]?[0-9]{1,2}\.){3}[0-2]?[0-9]{1,2}@";
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
  if($all_good){
    printErrors($error);
  }
  else{
    $rule='subnet '.$subnet.' netmask '. int2decPointIP($maski)." {\n";
    $rule.="\trange ".$debut." ".$fin.";\n";
    if(preg_match('@^([0-2]?[0-9]?[0-9].){3}[0-2]?[0-9]?[0-9]$@',preg_replace('@\h@','',$_GET['routeur'])&&addr_in_subnet($_GET['routeur'],$subnet,$mask)){
      $rule.="\toption routeurs ".$_GET['routeur'].";\n";
    }
    //TODO ajouter DNS et domaine
    //if(preg_match('@(\h*,?\h*([0-2]?[0-9]{1,2}\.){3}[0-2]?[0-9]{1,2}\h?,?\h?)+\h*@',$_GET['DNS'])
    $rule.="}\n";
    print "<pre>.$rule.</pre>";
  }
?>
</article>