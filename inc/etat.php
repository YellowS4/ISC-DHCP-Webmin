<article>
<?php
	if($_SESSION['grade']>0){
		require 'inc/network.php';
		if(is_run()){//On choisi le logo à affichier si le serveur tourne ou pas
			$run="vert";
		}else{
			$run="rouge";
		}
		if(is_install()){//On verifie que le dhcp est installé
			echo 'serveur DHCP: Installé <img src="images/vert.png" class="icon"> Lancé <img src="images/'.$run.'.png" class="icon"><br />';
		}else{
			echo 'serveur DHCP: Non installé <img src="images/rouge.png" class="icon"> Lancé <img src="images/'.$run.'.png" class="icon"><br />';
		}
		echo 'Récapitulatif des interface: <br \>';
		$interfaces=get_network();
		//print_r($interfaces);
		foreach ($interfaces as $nom_inter => $categories) {//On boucle pour extraire toutes les interfaces $key= les nom des interfaces
			echo "<br \> ".$nom_inter.": ";
			if(is_array($categories) && $categories!=array()){//Si aucune info sur l'interface
				foreach ($categories as $nom_categories => $info  ) { 
					foreach ($info as  $value) { //On extrait les info des categories
					
						if($nom_categories=="IPv4_addr"){
							echo "ip ->".$value." ";
						}elseif($nom_categories=="IPv4_mask"){
							echo "masque ->".$value." ";
						}
					}
				}
			}else{
				echo "Aucune adresse définie";
			}
			
		}
	}else{
		printErrors(Array("Vous n'avez pas un grade suffisant"));
	}	
?>
</article>