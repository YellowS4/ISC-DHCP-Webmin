<?php
  /*
    Code par Jason Gantner
  */
  require_once 'inc/fonctions_generales.php';
  //fonction pour transformer un entier 32bits en IP notée en décimal pointé
  function int2decPointIP($ip){
    $out='';
    $mask=Array(0x000000ff,0x0000ff00,0x00ff0000,0xff000000);
    //masques à appliquer à l'ip pour obtenir les octets un par un
    for($i=3;$i>=0;$i--){
      //parcour de l'entier 32bits
      $tmp= ( $ip & $mask[$i] ) >> ( 8 * $i );
      //on applique le masque et le décalage correspondant de façon à obtenir un entier sur 8bits
      $out .= ($tmp<0) ? 256+$tmp : $tmp;
      //si le décalage est fait avec des 1 à la place des 0, on corrige
      if($i>0){$out.='.';}
      //on ajoute le séparateur
    }
    return $out; //on renvoie la chaine contenant l'ipl'IP
  }

  //transforme une ip écrite en décimal pointé/hexa/tableau de quatres octets en entier
  function IP2int($addr){
    switch(gettype($addr)){
      case 'integer':
	//si on reçoit un entier, il n'y a rien à faire
	$addri=$addr;
	break;
      case 'string';
	//si c'est une chaine de caractères, il peut y avoir plusieurs possibilités
	if(preg_match('@[0-9a-fA-F]{8}@',$addr)){
	  //cas où c'est une écriture héxadécimale
	  $addri=hexdec($addr);//simple conversion d'héxadécimal à décimal
	  break;
	}
	else{
	  $tmp=explode('.',$addr);// on estime que c'est une notation décimal pointée, on sépare donc chaque octet.
	  if(count($tmp)!==4){ //si il n'y a pas 4 éléments dans le tableau
	    $addri=false;
	    break;
	  }
	  else{
	    $addr=$tmp;
	  }
	}
      case 'array':
	//si c'est un tableau on le parcours et on concatene les entiers à l'aide d'un décalage binaire
	$addri=0x0;
	for( $i=0;$i<4;$i++ ){
	  $addri=($addri<<8)+$addr[$i];
	}
	break;
      default:
	$addri=false;
	break;
    }
    return $addri; //on renvoie soit un entier en cas de réussite, soit false en cas d'erreur
  }
  
  //fonction permettant d'obtenir des informations sur les interfaces du serveurs et les réseaux connéctés
function get_network(){
  exec('/sbin/ifconfig -a 2>&1',$return);//on execute ifconfig sur toutes les interfaces
  $interfaces=Array();//on crée un tableau pour stocker des informations sur les interfaces
  $if_count=0;
  foreach($return as $line){
    //on parcoure ligne par ligne la réponse d'ifconfig
    if( preg_match('@^[a-z]+\d*@',$line,$matches)){
      //ici on matche le nom d'une interface donc on l'ajoute au tableau
      $current_if=$matches[0];
      $interfaces[$current_if]=Array();
      $current_alias_v4=0;
      $current_alias_v6=0;
      $current_alias_v6_local=0;
    }
    if( preg_match('@inet addr:([\d\.]+)  Bcast:[\d\.]+  Mask:([\d\.]+)@',$line,$matches)){
      //ici on matche une addresse IP et son masque sur un système Debian 6/7
      $interfaces[$current_if]['IPv4_addr'][$current_alias_v4]=$matches[1];
      $interfaces[$current_if]['IPv4_mask'][$current_alias_v4]=$matches[2];
      $current_alias_v4+=1;   
     }
    else if( preg_match('@inet ([\d\.]+) netmask 0x([0-9a-f]{8})@',$line,$matches)){
      //ici on matche une addresse IP et son masque sur un système FreeBSD 10
      $interfaces[$current_if]['IPv4_addr'][$current_alias_v4]=$matches[1];
      $interfaces[$current_if]['IPv4_mask'][$current_alias_v4]=$matches[2];
      $current_alias_v4+=1;
    }
    // les opérations IPv6 ne sont pas utiles pour l'instant, on les commente pour ne pas faire des opérations inutiles
    /*
    else if( preg_match('@inet6 (fe80\:[\:\da-f]+)%.+\d@',$line,$matches)){
      //ici on matche une addresse IPv6 link-local 
      $interfaces[$current_if]['IPv6_addr_link-local']=$matches[1];
      $current_alias_v6_local+=1;
    }
    else if( preg_match('@inet6 ([\:\da-f]+) prefixlen (\d+)@',$line,$matches)){
      //ici on matche une addresse IPv6 unique et la longueur de son préfixe
      $interfaces[$current_if]['IPv6_addr'][$current_alias_v6] = $matches[1];
      $interfaces[$current_if]['IPv6_prefixlen'][$current_alias_v6] = $matches[2];
      $current_alias_v6 += 1;
    }
    //*/
  }
  foreach(Array_keys($interfaces) as $if){
    //ici on calcule le subnet de chaque addresse IPv4 en convertissant les IP en entier de 32 bits et en faisant un ET logique puis on le reconvertit en écriture décimale
    if(isset($interfaces[$if]['IPv4_mask'])){
      foreach(Array_keys($interfaces[$if]['IPv4_addr']) as $i){
        $interfaces[$if]['IPv4_subnet'][$i] = int2decPointIP(IP2int($interfaces[$if]['IPv4_addr'][$i])&IP2int($interfaces[$if]['IPv4_mask'][$i]));
      }
    }
  }
  unset($return);//on efface le resultat d'ifconfig
  exec('/usr/bin/netstat -r 2>&1',$return);// on execute netstat pour obtenir des informations sur les routeurs
  foreach($return as $line){
    if (preg_match('@([\d\.]+|default)\h+([\d\.]+)\h+[UGHRS]+\h+\d\h+\d+\h+([a-z]+\d*)@',$line,$matches)){
      //ici on matche une entrée de la table de routage IPv4
      $interfaces[$matches[3]]['gateways'][$matches[2]][]=($matches[1]==='default')?'0.0.0.0':$matches[1];
    }
  }
  return $interfaces;
}

//fonction pour obtenir les serveurs de noms du resolv.conf
function get_ns(){
  $NS=Array();
  foreach(preg_split('@\r\n|\n@',file_get_contents('/etc/resolv.conf')) as $line){
    //on lit le fichier resolv.conf pour obtenir les DNS
    if(preg_match('@nameserver\h(((25[0-5]|2[0-4][0-9]|[0-1]?[0-9]{1,2})\.){3}(25[0-5]|2[0-4][0-9]|[0-1]?[0-9]{1,2}))@',$line,$matches)){
      $NS[]=$matches[1];
      print_r($matches);
    }
  }
  return $NS;
}


//fonction pour tester si une addresse appartient à un subnet
function addr_in_subnet($addr,$subnet,$mask){
    //on commence par représenter les IP sous forme d'entiers 32 bits.
    $addr = IP2int($addr);
    $subnet = IP2int($subnet);
    $mask = IP2int($mask);
    //on vérifie qu'il n'y ai pas d'erreur de conversion
    if($addr && $subnet && $mask!==false)return (dechex($addr & $mask)===dechex($subnet & $mask));
    //on utilise une conversion en hexadécimal car il y a un bug sans elle
    else{
      $errors=Array();
      if($addr===false)$errors[]='L\'adresse n\'as pas pu être transformée en int<br>';
      if($subnet===false)$errors[]='Le subnet n\'as pas pu être transformé';
      if($mask===false)$errors[]='Le masque n\'as pas pu être transformé';
      return $errors;
    };
    
  }
?>