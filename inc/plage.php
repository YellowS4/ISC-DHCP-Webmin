<article>
<?php
/*
  Code par Jason Gantner
*/
require_once 'inc/network.php';
$nw=get_network();
?>
<form name="plage" method="POST" action="index.php?page=nouvelle_plage">
  <p><span>Subnet :</span>
  <select name='subnet'>
  <?php
    foreach(Array_keys($nw) as $if){
      foreach( $nw[$if]['IPv4_subnet'] as $subnet ){
	echo '<option value="',$subnet,'">',$subnet,' (',$if,')</option>';
      }
    }
  ?>
  </select><br>
  Masque (Notation CIDR) :
  <input type="number" name="mask" min="0" max="32" list="masks"><br>
  <datalist id="masks">
    <?php
      foreach(Array_keys($nw) as $if){
	foreach( $nw[$if]['IPv4_mask'] as $mask ){
	  echo '<option value="',$mask,'">',$mask,'</option>';
	}
      }
    ?>
  </datalist>
  Début de la plage:
  <input id="plage_debut" type="text" name="debut" onchange="verifPlage();"  pattern="^((25[0-5]|2[0-4][0-9]|[0-1]?[0-9]{1,2})\.){3}(25[0-5]|2[0-4][0-9]|[0-1]?[0-9]{1,2})" placeholder="ex:192.168.0.1"><br>
  <span>Fin de la plage :</span>
  <input id="plage_fin" type="text" name="fin" onchange="verifPlage();" pattern="^((25[0-5]|2[0-4][0-9]|[0-1]?[0-9]{1,2})\.){3}(25[0-5]|2[0-4][0-9]|[0-1]?[0-9]{1,2})" placeholder="ex:192.168.0.253"><br>
  </p>
  <fieldset id="options" >
  <legend>Options :</legend>
  <input type="checkbox" name="check_routers">
  Routeur :
  <input type="text" list="routers" name="routeur" placeholder="ex:192.168.0.254" pattern="^((25[0-5]|2[0-4][0-9]|[0-1]?[0-9]{1,2})\.){3}(25[0-5]|2[0-4][0-9]|[0-1]?[0-9]{1,2})$">
  <datalist id="routers" >
  <?php
    foreach(Array_keys($nw) as $if){
      if(isset($nw[$if]['gateways'])){
	foreach(Array_keys($nw[$if]['gateways']) as $gt){
	  echo '<option value="',$gt,'"> Routeur sur ',$if,'</option>';
	}
      }
    }
    foreach(Array_keys($nw) as $if){
      foreach( $nw[$if]['IPv4_addr'] as $addr ){
	echo '<option value="',$addr,'">addresse de ',$if,'</option>';
      }
    }
  ?>
  </datalist>
  <br>
  <input type="checkbox" name="check_domain" form="plage">
  Nom de domaine :
  <input type="text" name="domain" placeholder="ex: home ou batiment3.local ..." pattern="[a-z0-9]+(\.[a-z0-9]+)*"><br>
  <input type="checkbox" name="check_DNS" form="plage">
  Serveurs de noms :
  <input type="text" name="DNS" list="nslist" placeholder="ex:8.8.8.8, 8.8.4.4 ou 10.100.100.20, 8.8.8.8 ..." pattern="([\,\h]*([0-2]?[0-9]{1,2}\.){3}[0-2]?[0-9]{1,2})+[\,\h]*" multiple ><br>
  <datalist id="nslist">
  <?php
    foreach(get_ns() as $ns){
	  echo '<option value="',$ns,'"> ',$ns,' resolv.conf</option>';
    }
    foreach(Array_keys($nw) as $if){
      foreach( $nw[$if]['IPv4_addr'] as $addr ){
	echo '<option value="',$addr,'">addresse de ',$if,'</option>';
      }
    }
  ?>
  </datalist>
  </fieldset>
  <input type="reset" value="Effacer">
  <input type="submit" value="valider">
</form>
</article>