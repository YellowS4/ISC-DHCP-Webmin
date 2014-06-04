<article>
<?php
//Includes
include 'inc/fonctionsBDD.php';
<<<<<<< HEAD
$connex=connexionBDD();
=======
$connexionBDD=connexionBDD();
>>>>>>> 07b672b0320b83dd823a0adfd7f23e449884b7ec
include 'inc/network.php';
//Constantes
define("NB_CONF_PAGE","2");
define("NB_PAGE","2");
$nw=get_network();
//On securise la variable ID
if(isset($_GET['id']) && $_GET['id']>=0){
	settype($_GET['id'],"string");
	$id=$_GET['id'];
}else{
	$id=0;
}


	if(isset($_POST['ajouter'])){ //On ajoute la nouvelle configuration
		$erreur="";
		if($_POST['default']=="true"){
			$resultats=$connex->query("SELECT id FROM dhcp_test WHERE conf_default=TRUE");
			if($resultats->rowCount()>0){
<<<<<<< HEAD
				$erreur.="Impossible, il existe d?j? une configuration par d?faut\n";
=======
				$erreur.="Impossible, il existe d�j� une configuration par d�faut\n";
>>>>>>> 07b672b0320b83dd823a0adfd7f23e449884b7ec
			}
		}
		if(isset($erreur) && $erreur!=""){
			echo $erreur;
		}else{
			echo 'Ajout effectue';
			$resultats=$connex->exec("INSERT INTO dhcp_test (conf_contenu, interface, nom_conf, date_creation, conf_default, conf_actuelle) VALUES ('".$_POST['conf_contenu']."','".$_POST['interface']."','".$_POST['nom']."','".date("Y-m-d")."','".$_POST['default']."','".$_POST['actuelle']."'); ");
			
		}
	}else{
	//On prends le nombre de configuration pour la pagination
		$nb_conf_requete=$connex->query("SELECT conf_contenu, interface, nom_conf, date_creation, conf_default, conf_actuelle  FROM dhcp_test");
		$nb_conf=$nb_conf_requete->rowCount();
		//On calcule le nombre de page
		if($nb_conf%NB_CONF_PAGE==0){
			$nb_pages=$nb_conf/NB_CONF_PAGE;
		}else{
			$nb_pages=floor($nb_conf/NB_CONF_PAGE);
		}
		$resultats=$connex->query("SELECT conf_contenu, interface, nom_conf, date_creation, conf_default, conf_actuelle  FROM dhcp_test LIMIT ".NB_CONF_PAGE." OFFSET ".$id*NB_CONF_PAGE."");
	?>
<<<<<<< HEAD
	Afficher seulement: <label>Tous <input type="checkbox" id="selection_conf" name="affichage[]" value="Tous" onclick="selection('conf')" checked="checked"></label> <label>La configuration par defaut <input type="checkbox" id="selection_default" name="affichage[]" value="Configuration_d?faut" onclick="selection('default')"></label> <label>La configuration actuelle <input type="checkbox" id="selection_actuelle" name="affichage[]" value="Configuration_actuelle" onclick="selection('actuelle')"></label> Concernant les interfaces:.... <br />
=======
	Afficher seulement: <label>Tous <input type="checkbox" id="selection_conf" name="affichage[]" value="Tous" onclick="selection('conf')" checked="checked"></label> <label>La configuration par defaut <input type="checkbox" id="selection_default" name="affichage[]" value="Configuration_d�faut" onclick="selection('default')"></label> <label>La configuration actuelle <input type="checkbox" id="selection_actuelle" name="affichage[]" value="Configuration_actuelle" onclick="selection('actuelle')"></label> Concernant les interfaces:.... <br />
>>>>>>> 07b672b0320b83dd823a0adfd7f23e449884b7ec
	<?php	
		$i=0;
		foreach ($resultats as $row) {	
		
			//On affiche que 5 element par page
			
		?>
		
			<form method="POST" action="" class="conf<?php if($row['conf_default']=="true"){ echo '_default';} if($row['conf_actuelle']=="true"){ echo '_actuelle';} //On ajoute toutes nos classes pour les gerer en js ?>"> 
				<span style="vertical-align:top;">fichier dhcpd.conf: </span><textarea name="conf_contenu" rows="20" cols="50"><?php echo $row['conf_contenu'];?></textarea><br />
				interface:  <select name='interface'>
				  <?php
				  //On liste les interfaces
					foreach ($nw as $nom_inter => $categories ) {//On boucle pour extraire toutes les interfaces $key= les nom des interfaces
							if(is_array($categories) && $categories!=array()){//Si aucune info sur l'interface
								foreach ($categories as $nom_categories => $info  ) { 
									foreach ($info as  $value) { //On extrait les info des categories
										if($nom_categories=="IPv4_addr"){//On ne prends que l'ip
											$select="";
											if($row['interface']==$nom_inter){
												$select='selected="selected"';
											}
											print '<option value="'.$nom_inter.'" '.$select.'>'.$nom_inter.' ('.$value.')</option>';
										}
									}
								}
							}else{
								$select="";
								if($row['interface']==$nom_inter){
									$select='selected="selected"';
								}
<<<<<<< HEAD
								print '<option value="'.$nom_inter.'" '.$select.'>'.$nom_inter.' (Aucun adresse d?finie)</option>';
=======
								print '<option value="'.$nom_inter.'" '.$select.'>'.$nom_inter.' (Aucun adresse d�finie)</option>';
>>>>>>> 07b672b0320b83dd823a0adfd7f23e449884b7ec
							}
							
						} 
				  ?>
				  </select>
				  
				nom de la configuration: <input type="text" name="nom" value="<?php echo $row['nom_conf'];?>"/><br />
				configuration par defaut:  Oui <input type="radio" name="default" value="true" <?php if($row['conf_default']=="true"){ echo ' checked="checked"';}?>> Non: <input type="radio" name="default" value="false" <?php if($row['conf_default']!="true"){ echo ' checked="checked"';} ?>><br />
				Appliquer la configuration:  Oui <input type="radio" name="actuelle" value="true" <?php if($row['conf_actuelle']=="true"){ echo ' checked="checked"';} ?>> Non: <input type="radio" name="actuelle" value="false"<?php if($row['conf_actuelle']!="true"){ echo ' checked="checked"';} ?>>
				<input type="submit" value="ajouter" name="ajouter">
			</form>	
		<?php
		}
		/*for($i=0; $i<$nb_pages; $i++){
			//On selectionne tous les parametre dans l'url
			preg_match('@(index.php?.*)&id+@',$_SERVER['REQUEST_URI'],$matches);
				echo '<a href="'.$matches[1].'&id='.$i.'">'.$i.'</a>';
		}*/
		
		//ESSAI DE PAGINATION
		//On definit les pages a afficher
			//Le debut
			echo '<a href="index.php?page=modif_conf&id=0"><< </a>';
			if($id>=6){
				for($i=-NB_PAGE/2+5; $i<NB_PAGE/2+1+5; $i++){
					//On selectionne tous les parametre dans l'url
						echo '<a href="index.php?page=modif_conf&id='.$i.'">'.$i.'</a>';
				}
			}elseif($id<=0){
				for($i=-NB_PAGE/2+1; $i<NB_PAGE/2+1+1; $i++){
					//On selectionne tous les parametre dans l'url
						echo '<a href="index.php?page=modif_conf&id='.$i.'">'.$i.'</a>';
				}
			}else{
				for($i=-NB_PAGE/2+$id; $i<NB_PAGE/2+1+$id; $i++){
					//On selectionne tous les parametre dans l'url
						echo '<a href="index.php?page=modif_conf&id='.$i.'">'.$i.'</a>';
				}
			}
			echo '<a href="index.php?page=modif_conf&id='.$nb_pages.'"> >></a>';
		//FIN ESSAI DE PAGINATION
	}
?>
</article>
