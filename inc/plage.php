<article>
	<?php
	/*
	 Code par Jason Gantner
	*/
	if($_SESSION['grade']<2){
	  echo '<span class="error">Vous n\'êtes pas assez gradé pour accèder à cette partie du site!</span>';
	  exit();
	}
	echo '<h3>Générateur de configuration de plage</h3>';
	require_once 'inc/network.php';
	$nw=get_network();
	/*
	 * on récolte des information sur la mise en réseau du
	 * serveur et on les enregistre dans $nw
	 */
	?>
	<form name="plage" method="POST" action="index.php?page=plage&amp;generate">
		<p>
			<span>Subnet :</span> 
			<select name='subnet'>
				<?php
				foreach(Array_keys($nw) as $if){
				  if(isset($nw[$if]['IPv4_subnet']))
				  foreach( $nw[$if]['IPv4_subnet'] as $subnet ){
				    /*
				     * Pour chaque interface, si elle est sur un subnet particulier on 
				     */
				    echo '<option value="',$subnet,'">',$subnet,' (',$if,')</option>';
				  }
				}
				?>
			</select><br>
			Masque (Notation CIDR) :
			<input	type="number" name="mask" min="0" max="32" list="masks"><br>
			<datalist id="masks">
				<?php
				foreach(Array_keys($nw) as $if){
				  if(isset($nw[$if]['IPv4_mask'])){
				    foreach( $nw[$if]['IPv4_mask'] as $mask ){
					  echo '<option value="',$mask,'">',$mask,'</option>';
				    }
				  }
				}
				?>
			</datalist>
			Début de la plage: 
			<input id="plage_debut" type="text" name="debut"
				onchange="verifPlage();"
				pattern="^((25[0-5]|2[0-4][0-9]|[0-1]?[0-9]{1,2})\.){3}(25[0-5]|2[0-4][0-9]|[0-1]?[0-9]{1,2})$"
				placeholder="ex:192.168.0.1"><br>
			<span>Fin de la plage : </span>
			<input id="plage_fin" type="text" name="fin" onchange="verifPlage();"
			pattern="^((25[0-5]|2[0-4][0-9]|[0-1]?[0-9]{1,2})\.){3}(25[0-5]|2[0-4][0-9]|[0-1]?[0-9]{1,2})$"
			placeholder="ex:192.168.0.253"><br>
		</p>
		<fieldset id="options">
			<legend>Options :</legend>
			<input type="checkbox" name="check_routers"> Routeur : <input
				type="text" list="routers" name="routeur"
				placeholder="ex:192.168.0.254"
				pattern="^((25[0-5]|2[0-4][0-9]|[0-1]?[0-9]{1,2})\.){3}(25[0-5]|2[0-4][0-9]|[0-1]?[0-9]{1,2})$">
			<datalist id="routers">
				<?php
				foreach(Array_keys($nw) as $if){
				  if(isset($nw[$if]['gateways'])){
				    foreach(Array_keys($nw[$if]['gateways']) as $gt){
				      /*
				       * Pour chaque interface on récupère l'adresse du ou des routeurs
				       * pour les ajouter comme proposition dans le champ routeur
				       */
				      echo '<option value="',$gt,'"> Routeur sur ',$if,'</option>';
				    }
				  }
				}
				foreach(Array_keys($nw) as $if){
				  if(isset($nw[$if]['IPv4_addr'])){
				    foreach( $nw[$if]['IPv4_addr'] as $addr ){
				      /*
				       * on fait de même pour les adresses IP de chaque interfaces
				       */
				      echo '<option value="',$addr,'">addresse de ',$if,'</option>';
				    }
				  }
				}
				?>
		      </datalist>
			<br>
			<input type="checkbox" name="check_domain">
			Nom de domaine : 
			<input type="text" name="domain"
				placeholder="ex: home ou batiment3.local ..."
				pattern="[a-z0-9\-\_]+(\.[a-z0-9\-\_]+)*"><br>
			<input type="checkbox" name="check_DNS">
			Serveurs de noms :
			<input type="text" name="DNS" list="nslist"
				placeholder="ex:8.8.8.8, 8.8.4.4, 10.100.100.20"
				pattern="[\,\h]*((25[0-5]|2[0-4][0-9]|[0-1]?[0-9]{1,2})\.){3}(25[0-5]|2[0-4][0-9]|[0-1]?[0-9]{1,2})[\,\h]*"><br>
			<datalist id="nslist">
				<?php
				foreach(get_ns() as $ns){
				      /*
				       * on propose les serveurs de noms du resolv.conf du serveur
				       */
				      echo '<option value="',$ns,'"> ',$ns,' resolv.conf</option>';
				}
				foreach(Array_keys($nw) as $if){
					    if(isset($nw[$if]['IPv4_mask'])){
				    foreach( $nw[$if]['IPv4_addr'] as $addr ){
						    /*
						     * on propose aussi les addresses IP du serveur
						     */
						    echo '<option value="',$addr,'">addresse de ',$if,'</option>';
				    }
				      }
				}
				?>
			</datalist>
		</fieldset>
		<input type="reset" value="Effacer"> <input type="submit"
			value="valider">
	</form>
</article>
<?php 
  if(isset($_GET['generate'])) include 'inc/add_plage.php';
  if(isset($_GET['apply'])) include 'inc/apply_plage.php';
?>