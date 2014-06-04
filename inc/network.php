<?php
  //fonction pour transformer un entier 32bits en IP notée en décimal pointé
  function int2decPointIP($ip){
    $out='';
    $mask=Array(0x000000ff,0x0000ff00,0x00ff0000,0xff000000);
    for($i=3;$i>=0;$i--){
      $tmp=($ip&$mask[$i])>>(8*($i));
      $out .= ($tmp<0) ? 256+$tmp : $tmp;
      if($i>0){$out.='.';}
    }
    return $out;
  }

  //transforme une ip écrite en décimal pointé/hexa/tableau de quatres octets en entier
  function IP2int($addr){
    print $debug?"IN IP2int: ":'';
    switch(gettype($addr)){
      case "integer":
	$addri=$addr;
	break;
      case "string";
	if(preg_match("@[0-9a-fA-F]{8}@",$addr)){
	  $addri=hexdec($addr);
	  print $debug?"hexIP 2 intIP<br>":'';
	  break;
	}
	else{
	  $tmp=explode('.',$addr);
	  if(count($tmp)!=4){
	    $addri=false;
	    print $debug?"str_split error<br/>":'';
	    break;
	  }
	  else{
	    $addr=$tmp;
	    print $debug?"decPointIP 2 arrayIP<br>":'';
	  }
	}
      case "array":
	$addri=0x0;
	for( $i=0;$i<4;$i++ ){
	  $addri=($addri<<8)+$addr[$i];
	}
	break;
      default:
	$addri=false;
	break;
    }
    return $addri;
  }
  
function get_network(){
  exec("/sbin/ifconfig -a 2>&1",$return);
  $interfaces=Array();
  $if_count=0;
  foreach($return as $line){
    if( preg_match('@^[a-z]+\d*@',$line,$matches)){
      $current_if=$matches[0];
      $interfaces[$current_if]=Array();
      $current_alias_v4=0;
      $current_alias_v6=0;
      $current_alias_v6_local=0;
    }
    if( preg_match('@inet addr:([\d\.]+)  Bcast:[\d\.]+  Mask:([\d\.]+)@',$line,$matches)){ //debian ifconfig
      $interfaces[$current_if]['IPv4_addr'][$current_alias_v4]=$matches[1];
      $interfaces[$current_if]['IPv4_mask'][$current_alias_v4]=$matches[2];
      $current_alias_v4+=1;   
     }
    else if( preg_match('@inet ([\d\.]+) netmask 0x([0-9a-f]{8})@',$line,$matches)){//freebsd ifconfig
      $interfaces[$current_if]['IPv4_addr'][$current_alias_v4]=$matches[1];
      $interfaces[$current_if]['IPv4_mask'][$current_alias_v4]=$matches[2];
      $current_alias_v4+=1;
    }
    else if( preg_match('@inet6 (fe80\:[\:\da-f]+)%.+\d@',$line,$matches)){
      $interfaces[$current_if]['IPv6_addr_link-local']=$matches[1];
      $current_alias_v6_local+=1;
    }
    else if( preg_match('@inet6 ([\:\da-f]+) prefixlen (\d+)@',$line,$matches)){
      $interfaces[$current_if]['IPv6_addr'][$current_alias_v6] = $matches[1];
      $interfaces[$current_if]['IPv6_prefixlen'][$current_alias_v6] = $matches[2];
      $current_alias_v6 += 1;
    }
  }
  foreach(Array_keys($interfaces) as $if){
    if(isset($interfaces[$if]['IPv4_maskt'])){
      foreach(Array_keys($interfaces[$if]['IPv4_addr']) as $i){
        $interfaces[$if]['IPv4_subnet'][$i] = int2decPointIP(IP2int($interfaces[$if]['IPv4_addr'][$i])&IP2int($interfaces[$if]['IPv4_mask'][$i]));
      }
    }
  }
  unset($return);
  exec("/usr/bin/netstat -r 2>&1",$return);
  foreach($return as $line){
    if (preg_match('@([\d\.]+|default)\h+([\d\.]+)\h+[UGHRS]+\h+\d\h+\d+\h+([a-z]+\d)@',$line,$matches)){
      $interfaces[$matches[3]]['gateways'][$matches[2]][]=($matches[1]==="default")?"0.0.0.0":$matches[1];
    }
  }
  return $interfaces;
}

//fonction pour tester si une addresse appartient à un subnet
function addr_in_subnet($addr,$subnet,$mask){
    print $debug?"IN addr_in_subnet():":'';
    $addr = IP2int($addr);
    $subnet = IP2int($subnet);
    $mask = IP2int($mask);
    print ($addr===false)?"L'adresse n'as pas pu être transformée en int<br>":'';
    print ($subnet===false)?"Le subnet n'as pas pu être transformé":'';
    if($addr && $subnet && $mask){
      return ($addr & $mask === $subnet & $mask);
    }
    else{return "error";}
  }
?>