<article>
<?php
//Includes
require_once 'inc/fonctionsBDD.php';
require_once 'inc/network.php';
require_once 'inc/appliquer_conf.php';
require_once 'inc/dhcp.php';
if(is_install()){
	$connex=connexionBDD();
	//Constantes
	define("NB_CONF_PAGE","2");
	$nw=get_network();
	//On securise la variable ID
	foreach($_POST as $indice=>$valeur){//On securise les valeurs en mettant par défaut une chaine
		$_POST[$indice]= htmlspecialchars ($valeur,ENT_QUOTES);
		settype($_POST[$indice], "string");
	}
	if(isset($_POST['conf'])){
		settype($_POST['conf'],"integer");
	}
	if(isset($_GET['id']) && $_GET['id']>=0 && $_GET['id']!==""){//Sinon "" est comme 0
		settype($_GET['id'],"integer");
		$id=$_GET['id'];
	}else{

		$id="";
	}
	$manquant="";

		if(isset($_POST['ajouter']) && $_POST['ajouter']==="Ajouter"){ //On ajoute la nouvelle configuration
			$erreur="";//Pour mettre des erreurs
			if(!isset($_POST['contenuconf']) || $_POST['contenuconf']=="" || !isset($_POST['createur']) || $_POST['createur']=="" || !isset($_POST['actuelle']) || $_POST['actuelle']==""){ //On verifie tous les parametre
				$erreur.="veuillez remplir tous les champs";
				echo $_POST['createur'];
			}else{
				if($_POST['actuelle']=="true"){//Si il existe déjà une conf actuelle on la met en false
					//a del $resultats=listerConf_actuelle($connex,"true");
					//a del $donnees=$resultats->fetch();
					//$resultats=$connex->exec("UPDATE projet34_configurations  SET conf_actuelle=FALSE WHERE id=".$donnees['id']."; ");	
					appliquer_conf($_POST['contenuconf']);
				}
			}
			if(isset($erreur) && $erreur!=""){
				echo 'erreur:'.$erreur;
			}else{
				echo 'Ajout effectue';
				$resultats=$connex->exec("INSERT INTO projet34_configurations  (contenuconf,createurconf) VALUES ('".$_POST['contenuconf']."',1); ");
			}
			echo '<form method="POST" action="index.php?page=modif_conf"><input type="submit" name="afficher_tout" value="Retour"> </form>';
			
			
		}elseif(isset($_POST['modifier']) && $_POST['modifier']==="Modifier"){//Pour modifier une conf
			if(!isset($_POST['contenuconf'])  || $_POST['contenuconf']=="" ||  !isset($_POST['createur']) || $_POST['createur']==""  || !isset($_POST['actuelle']) || $_POST['actuelle']==""){ //On verifie tous les parametre
				$manquant="veuillez remplir tous les champs";
				echo $manquant;
			}else{
				$erreur="";//Si toutesfois il y a des erreurs
				if($_POST['actuelle']=="true"){
					$resultats=listerConf_actuelle($connex,"true");
					$donnees=$resultats->fetch();
					//$resultats=$connex->exec("UPDATE projet34_configurations  SET conf_actuelle=FALSE WHERE id=".$donnees['id']."; ");	
					$verif=appliquer_conf($_POST['contenuconf']);
					if($verif!=""){
						$erreur.="Erreur dans la configuration: ".$verif."\n";
					}else{
						echo "Configuration appliqué ";
					}
				}
				if(isset($erreur) && $erreur!=""){
					echo $erreur;
				}else{
					echo 'modification effectue';
					$resultats=$connex->exec("UPDATE projet34_configurations  SET contenuconf='".$_POST['contenuconf']."', createurconf=1 WHERE idconf='".$_POST['id']."'; ");
				}
			}
			echo '<form method="POST" action="index.php?page=modif_conf"><input type="submit" name="afficher_tout" value="Retour"> </form>';		
		}elseif(isset($_POST['afficher_une_conf']) && $_POST['afficher_une_conf']==="ok"){//Pour afficher qu'une seul conf depuis l'affichage simple
			//$conf=$connex->query("SELECT id,contenuconf, interface, nom_conf, creation, conf_default, conf_actuelle FROM projet34_configurations  WHERE id=".$_POST['conf'].";");
			$conf=listerConf_id($connex,$_POST['conf']);
			$row=$conf->fetch();
			?>
			<form method="POST" action=""> 
					<input type="hidden" name="id" value="<?php echo $row['id'];?>">
					<label class="aligner"><span style="vertical-align:top;">fichier dhcpd.conf: </span></label><textarea name="contenuconf" rows="20" cols="50"><?php echo $row['contenuconf'];?></textarea><br />	  
					<label class="aligner">Propriétaire de la configuration: </label><input type="text" name="nom" value="<?php echo $row['nomuser'];?>" readonly="readonly"/><br />
					<label class="aligner">Appliquer la configuration (cela remplaçera la configuration actuelle):<label>  Oui</label> <input type="radio" name="actuelle" value="true" <?php if($row['conf_actuelle']=="true"){ echo ' checked="checked"';} ?>></label> <label>Non: <input type="radio" name="actuelle" value="false"<?php if($row['conf_actuelle']!="true"){ echo ' checked="checked"';} ?>></label><br />
					<input type="submit" value="Ajouter" name="ajouter">
					<input type="submit" value="Modifier" name="modifier">
					<input type="submit" value="retour" name="affichage_simple">
				</form>	
				<?php
		}elseif((isset($_POST['afficher_tout']) && ($_POST['afficher_tout']==="Affichage détaillé" || $_POST['afficher_tout']==="Retour")) || (isset($_GET['id']) && $_GET['id']!=="" && !isset($_POST['affichage_simple'])) || (isset($_POST['selectionner']) && $_POST['selectionner']==="selectionner") || (isset($_POST['trie']) && $_POST['trie']==="trié")){
			//Tri
			print 'Afficher seulement:<form method="POST" class="liste_conf"> Les configuration de l\'utilisateur: Après la date:<input type="date" name="selection_date"><input type="submit" value="selectionner" name="selectionner">';
			print 'Trié par date <select name="trie_date"><option value="ASC">Croissant</option><option value="DESC">Descroissant</option></select><input type="submit" value="trié" name="trie"><br /></form>';
			
			//On prends le nombre de configuration pour la pagination
			if(isset($_POST['selection_date']) && $_POST['selection_date']!==""){
				$liste_conf=listerConf($connex,"0","0",$_POST['selection_date']);
			}else{
				$liste_conf=listerConf($connex);
			}
			if($liste_conf->rowCount()!=0){
				$nb_conf=$liste_conf->rowCount();
				//On calcule le nombre de page
				if($nb_conf%NB_CONF_PAGE==0){
					$nb_pages=$nb_conf/NB_CONF_PAGE;
				}else{
					$nb_pages=ceil($nb_conf/NB_CONF_PAGE);
				}
				//On verifie les deux valeurs pour le trie DESC ou ASC 
				if(isset($_POST['trie_date']) && ($_POST['trie_date']==="ASC" || $_POST['trie_date']==="DESC")){
					$trie=$_POST['trie_date'];
				}else{
					$trie="ASC";
				}
				//On selectionne si on choisi une date
				if(isset($_POST['selection_date']) && $_POST['selection_date']!=""){
					$liste_conf=listerConf($connex,NB_CONF_PAGE,$id*NB_CONF_PAGE,$_POST['selection_date'],$trie);
				}else{
					$resultats=listerConf($connex,NB_CONF_PAGE,$id*NB_CONF_PAGE,"0",$trie);
				}
				//Permet de choisir (avec js)
				//print 'Afficher seulement: <label>Tous <input type="checkbox" id="selection_conf" name="affichage[]" value="Tous" onclick="selection(\'conf\')" checked="checked"></label> <label>La configuration par defaut <input type="checkbox" id="selection_default" name="affichage[]" value="Configuration_d?faut" onclick="selection(\'default\')"></label> <label>La configuration actuelle <input type="checkbox" id="selection_actuelle" name="affichage[]" value="Configuration_actuelle" onclick="selection(\'actuelle\')"></label> Concernant les interfaces:.... <br />';
				
				echo $manquant;
				//$i=0;
				foreach ($resultats as $row) {	
				
					//On affiche que 5 element par page
					
				?>
				
					<form method="POST" action="" class="conf"> 
						<input type="hidden" name="id" value="<?php echo $row['idconf'];?>">
						<label class="aligner"><span style="vertical-align:top;">fichier dhcpd.conf: </label></span><textarea class="form" name="contenuconf" rows="20" cols="50"><?php echo $row['contenuconf'];?></textarea><br />				  
						<label class="aligner">Propriétaire de la configuration: </label><input type="text" class="form" name="createur" value="<?php echo $row['nomuser'];?>" readonly="readonly"/><br />
						Appliquer la configuration (cela remplaçera la configuration actuelle):  <label>Oui<input type="radio" name="actuelle" value="true"></label>  <label>Non: <input type="radio" name="actuelle" value="false" checked="checked"></label><br />
						<input type="submit" value="Ajouter" name="ajouter">
						<input type="submit" value="Modifier" name="modifier">
					</form>	
				<?php
				}
				
				//ESSAI DE PAGINATION
				//On definit les pages a afficher
				echo $nb_pages;
				if($nb_pages>=2 && $nb_pages<=4){//Si il n'y a pas beaucoup de pages
				
					for($i=0; $i<$nb_pages; $i++){
						//	echo $nb_pages;
							echo '<a href="?page=modif_conf&id='.$i.'">'.$i.'</a>';
						}
				}elseif($nb_pages >4){
					//Le debut
					echo '<a href="?page=modif_conf&id=0"><< </a>';
					$milieu=ceil($nb_pages/2);
					for($i=$milieu-1;$i<=$milieu+1;$i++){
						echo '<a href="?page=modif_conf&id='.$i.'">'.$i.'</a>';
					}
					echo '<a href="?page=modif_conf&id='.($nb_pages-1).'"> >></a>';
				//FIN ESSAI DE PAGINATION
				}
			}
			print '<form method="POST" action=""><input type="submit" name="affichage_simple" value="Affichage simple"> </form>';
		}else{
			$liste_conf=listerConf($connex);
			print '<form method="POST" action="">';
			print 'Listes configurations: <select name="conf">';
					foreach($liste_conf as $ligne){
						print '<option value="'.$ligne['idconf'].'">'.substr($ligne['creation'],'0','19').'('.$ligne['nomuser'].')</option>'."\n";
					}
					print '</select><input type="submit" name="afficher_une_conf" value="ok"> </form>';
			print '<form method="POST" action="index.php?page=modif_conf"><input type="submit" name="afficher_tout" value="Affichage détaillé"> </form>';
		}
}else{
		printErrors(Array("Le serveur n'est pas installé"));
}
?>
</article>
