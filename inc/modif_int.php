<article>
<?php
	include 'inc/network.php';
	$nw=get_network();
	if(is_install()){
	if(isset($_POST['valider']) && $_POST['valider']==="Valider"){
		
		
		
			$interfaces="";
			if(isset($_POST['interface'])){//On autorise vide si on veux mettre aucune interface
				foreach($_POST['interface'] as $interface){
					$valide=false;
					//On parcours toutes les interfaces
					foreach ($nw as $nom_inter => $categories ) {//On boucle pour extraire toutes les interfaces $key= les nom des interfaces
						if($nom_inter===$interface){
							$valide=true;
						}								
					} 
					if($valide){//Si c'est valide c'est bon
						$interfaces.=" ,".$interface;//On concatene tous
					}
					
				}
			}
			$interfaces = substr($interfaces,2);//On supprime la premiere virgule

			$sortie=shell_exec("echo \"INTERFACES=\\\"".$interfaces."\\\"\">/etc/default/isc-dhcp-server 2>&1");
			//$sortie=shell_exec("echo bonjour >/tmp/test 2>&1");
			echo $sortie;
		
	}
?>

	Modification des interfaces d'écoutes
	<form method="POST">
		<?php
			$interface_ecoute="";
			$liste_interface_ecoute=shell_exec("cat /etc/default/isc-dhcp-server");
			 if (preg_match('@INTERFACES="(.*)"@',$liste_interface_ecoute,$matches)){
				$interface_ecoute=$matches[1];
			}
			foreach ($nw as $nom_inter => $categories ) {//On boucle pour extraire toutes les interfaces $key= les nom des interfaces
							if(is_array($categories) && $categories!=array()){//Si aucune info sur l'interface
								foreach ($categories as $nom_categories => $info  ) { 
									foreach ($info as  $value) { //On extrait les info des categories
										if($nom_categories=="IPv4_addr"){//On ne prends que l'ip
											$checked="";
											if(strstr($interface_ecoute,$nom_inter)){
												$checked="checked=\"checked\"";
											}
											print '<label>'.$nom_inter.' ('.$value.')<input type="checkbox" '.$checked.' name="interface[]" value="'.$nom_inter.'"> </label><br />';
											
										}
									}
								}
							}else{		
								$checked="";
								if(strstr($interface_ecoute,$nom_inter)){
									$checked="checked=\"checked\"";
								}
								print '<label>'.$nom_inter.' (aucune ip définie) <input type="checkbox" '.$checked.' name="interface[]" value="'.$nom_inter.'"></label><br />';
							}
							
						} 
		?>
		<input type="submit" value="Valider" name="valider">
	</form>

</article>
<?php
}else{
			printErrors(Array("Le serveur n'est pas installé"));
}
?>