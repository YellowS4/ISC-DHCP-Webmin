<article>
<?php
//Includes
include 'inc/fonctionsBDD.php';
include 'inc/network.php';
include 'inc/appliquer_conf.php';
$connex=connexionBDD();

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
$manquant="";

	if(isset($_POST['ajouter']) && $_POST['ajouter']==="Ajouter"){ //On ajoute la nouvelle configuration
		if(!isset($_POST['conf_contenu']) || $_POST['conf_contenu']=="" || !isset($_POST['interface']) || $_POST['interface']=="" || !isset($_POST['nom']) || $_POST['nom']=="" || !isset($_POST['default']) || $_POST['default']=="" || !isset($_POST['actuelle']) || $_POST['actuelle']==""){ //On verifie tous les parametre
			$manquant="veuillez remplir tous les champs";
		}else{
			$erreur="";
			if($_POST['default']=="true"){
				$resultats=$connex->query("SELECT id FROM dhcp_test WHERE conf_default=TRUE");
				if($resultats->rowCount()>0){
					$erreur.="Impossible, il existe d?j? une configuration par d?faut\n";
				}
			}
			if($_POST['actuelle']=="true"){//Si il existe déjà une conf actuelle on la met en false
				$resultats=$connex->query("SELECT id FROM dhcp_test WHERE conf_actuelle=TRUE;");
				$donnees=$resultats->fetch();
				$resultats=$connex->exec("UPDATE dhcp_test SET conf_actuelle=FALSE WHERE id=".$donnees['id']."; ");	
				appliquer_conf($_POST['conf_contenu'],$_POST['interface']);
			}
			if(isset($erreur) && $erreur!=""){
				echo $erreur;
			}else{
				echo 'Ajout effectue';
				$resultats=$connex->exec("INSERT INTO dhcp_test (conf_contenu, interface, nom_conf, date_creation, conf_default, conf_actuelle) VALUES ('".$_POST['conf_contenu']."','".$_POST['interface']."','".$_POST['nom']."','".date("Y-m-d")."','".$_POST['default']."','".$_POST['actuelle']."'); ");
				
			}
		}
	}elseif(isset($_POST['modifier']) && $_POST['modifier']==="Modifier"){//Pour modifier une conf
		if(!isset($_POST['conf_contenu'])  || $_POST['conf_contenu']=="" || !isset($_POST['interface']) || $_POST['interface']=="" || !isset($_POST['nom']) || $_POST['nom']=="" || !isset($_POST['default']) || $_POST['default']=="" || !isset($_POST['actuelle']) || $_POST['actuelle']==""){ //On verifie tous les parametre
			$manquant="veuillez remplir tous les champs";
			echo $manquant;
		}else{
			$erreur="";
			if($_POST['default']=="true"){
				$resultats=$connex->query("SELECT id FROM dhcp_test WHERE conf_default=TRUE;");
				if($resultats->rowCount()>0){
					$erreur.="Impossible, il existe d?j? une configuration par d?faut\n";
				}
			}
			if($_POST['actuelle']=="true"){
				$resultats=$connex->query("SELECT id FROM dhcp_test WHERE conf_actuelle=TRUE;");
				$donnees=$resultats->fetch();
				//$resultats=$connex->exec("UPDATE dhcp_test SET conf_actuelle=FALSE WHERE id=".$donnees['id']."; ");	
				$verif=appliquer_conf($_POST['conf_contenu'],$_POST['interface']);
				if($verif!=""){
					$erreur.="Erreur dans la configuration: ".$verif."\n";
				}else{
					echo "Configuration appilquÃ© ";
				}
			}
			if(isset($erreur) && $erreur!=""){
				echo $erreur;
			}else{
				echo 'modification effectue';
				//$resultats=$connex->exec("UPDATE dhcp_test SET conf_contenu='".$_POST['conf_contenu']."', interface='".$_POST['interface']."', nom_conf='".$_POST['nom']."', conf_default='".$_POST['default']."', conf_actuelle='".$_POST['actuelle']."' WHERE id='".$_POST['id']."'; ");
			}
		}
	}elseif(isset($_POST['afficher_une_conf']) && $_POST['afficher_une_conf']==="ok"){//Pour afficher qu'une seul conf depuis l'affichage simple
		$conf=$connex->query("SELECT id,conf_contenu, interface, nom_conf, date_creation, conf_default, conf_actuelle FROM dhcp_test WHERE id=".$_POST['conf'].";");
		$row=$conf->fetch();
		?>
		<form method="POST" action=""> 
				<input type="hidden" name="id" value="<?php echo $row['id'];?>">
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
								print '<option value="'.$nom_inter.'" '.$select.'>'.$nom_inter.' (Aucun adresse d?finie)</option>';
							}
							
						} 
				  ?>
				  </select>
				  
				nom de la configuration: <input type="text" name="nom" value="<?php echo $row['nom_conf'];?>"/><br />
				configuration par defaut:  Oui <input type="radio" name="default" value="true" <?php if($row['conf_default']=="true"){ echo ' checked="checked"';}?>> Non: <input type="radio" name="default" value="false" <?php if($row['conf_default']!="true"){ echo ' checked="checked"';} ?>><br />
				Appliquer la configuration (cela remplaçera la configuration actuelle):  Oui <input type="radio" name="actuelle" value="true" <?php if($row['conf_actuelle']=="true"){ echo ' checked="checked"';} ?>> Non: <input type="radio" name="actuelle" value="false"<?php if($row['conf_actuelle']!="true"){ echo ' checked="checked"';} ?>><br />
				<input type="submit" value="Ajouter" name="ajouter">
				<input type="submit" value="Modifier" name="modifier">
				<input type="submit" value="retour" name="">
			</form>	
			<?php
	}elseif(isset($_POST['afficher_tout']) && $_POST['afficher_tout']==="Affichage dÃ©taillÃ©"){
	//On prends le nombre de configuration pour la pagination
		$nb_conf_requete=$connex->query("SELECT conf_contenu, interface, nom_conf, date_creation, conf_default, conf_actuelle  FROM dhcp_test");
		$nb_conf=$nb_conf_requete->rowCount();
		//On calcule le nombre de page
		if($nb_conf%NB_CONF_PAGE==0){
			$nb_pages=$nb_conf/NB_CONF_PAGE;
		}else{
			$nb_pages=floor($nb_conf/NB_CONF_PAGE);
		}
		$resultats=$connex->query("SELECT id,conf_contenu, interface, nom_conf, date_creation, conf_default, conf_actuelle  FROM dhcp_test LIMIT ".NB_CONF_PAGE." OFFSET ".$id*NB_CONF_PAGE.";");
		$liste_conf=$connex->query("SELECT id,conf_contenu, interface, nom_conf, date_creation, conf_default, conf_actuelle  FROM dhcp_test;");
	
		print 'Afficher seulement: <label>Tous <input type="checkbox" id="selection_conf" name="affichage[]" value="Tous" onclick="selection(\'conf\')" checked="checked"></label> <label>La configuration par defaut <input type="checkbox" id="selection_default" name="affichage[]" value="Configuration_d?faut" onclick="selection(\'default\')"></label> <label>La configuration actuelle <input type="checkbox" id="selection_actuelle" name="affichage[]" value="Configuration_actuelle" onclick="selection(\'actuelle\')"></label> Concernant les interfaces:.... <br />';

		echo $manquant;
		//$i=0;
		foreach ($resultats as $row) {	
		
			//On affiche que 5 element par page
			
		?>
		
			<form method="POST" action="" class="conf<?php if($row['conf_default']=="true"){ echo '_default';} if($row['conf_actuelle']=="true"){ echo '_actuelle';} //On ajoute toutes nos classes pour les gerer en js ?>"> 
				<input type="hidden" name="id" value="<?php echo $row['id'];?>">
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
								print '<option value="'.$nom_inter.'" '.$select.'>'.$nom_inter.' (Aucun adresse d?finie)</option>';
							}
							
						} 
				  ?>
				  </select>
				  
				nom de la configuration: <input type="text" name="nom" value="<?php echo $row['nom_conf'];?>"/><br />
				configuration par defaut:  Oui <input type="radio" name="default" value="true" <?php if($row['conf_default']=="true"){ echo ' checked="checked"';}?>> Non: <input type="radio" name="default" value="false" <?php if($row['conf_default']!="true"){ echo ' checked="checked"';} ?>><br />
				Appliquer la configuration (cela remplaçera la configuration actuelle):  Oui <input type="radio" name="actuelle" value="true" <?php if($row['conf_actuelle']=="true"){ echo ' checked="checked"';} ?>> Non: <input type="radio" name="actuelle" value="false"<?php if($row['conf_actuelle']!="true"){ echo ' checked="checked"';} ?>><br />
				<input type="submit" value="Ajouter" name="ajouter">
				<input type="submit" value="Modifier" name="modifier">
			</form>	
		<?php
		}
		//ESSAI DE PAGINATION
		//On definit les pages a afficher
			//Le debut
			echo '<a href="index.php?page=modif_conf&id=0"><< </a>';
			if($id>=6){
				for($i=-NB_PAGE/2+5; $i<NB_PAGE/2+6; $i++){
					//On selectionne tous les parametre dans l'url
						echo '<a href="index.php?page=modif_conf&id='.$i.'">'.$i.'</a>';
				}
			}elseif($id<=0){
				for($i=-NB_PAGE/2+1; $i<NB_PAGE/2+2; $i++){
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
		print '<form method="POST" action=""><input type="submit" name="" value="Affichage simple"> </form>';
	}else{
		$liste_conf=$connex->query("SELECT id,conf_contenu, interface, nom_conf, date_creation, conf_default, conf_actuelle  FROM dhcp_test;");
		print '<form method="POST" action="">';
		print 'Listes configurations: <select name="conf">';
				foreach($liste_conf as $ligne){
					print '<option value="'.$ligne['id'].'">'.$ligne['date_creation'].'('.$ligne['nom_conf'].')</option>'."\n";
				}
				print '</select><input type="submit" name="afficher_une_conf" value="ok"> </form>';
		print '<form method="POST" action=""><input type="submit" name="afficher_tout" value="Affichage dÃ©taillÃ©"> </form>';
	}
?>
</article>
