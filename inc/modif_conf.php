
<article>
<?php
////////////////////////////////////
//Projet 34 - AUDON Florian - B1  //
////////////////////////////////////
if($_SESSION['grade']>1){
	//Includes
	require_once 'inc/fonctionsBDD.php';
	require_once 'inc/network.php';
	require_once 'inc/appliquer_conf.php';
	require_once 'inc/dhcp.php';

	if(is_install()){
		$connex=connexionBDD();
		//Constantes
		/** NB_CONF_PAGE définit le nombre d'element par page */
		define("NB_CONF_PAGE",2);
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
			if(isset($_POST['supprimer']) && $_POST['supprimer']==="supprimer" && $_SESSION['grade']>2){
				echo 'configuration supprimé';
				rmConf_id($connex,$_POST['conf']);
			}
			if(isset($_POST['ajouter']) && $_POST['ajouter']==="Ajouter"){ //On ajoute la nouvelle configuration
				$erreur="";//Pour mettre des erreurs
	    		echo '<h3>Ajout d\'une configuration</h3>';
				if(!isset($_POST['contenuconf']) || $_POST['contenuconf']==="" || !isset($_POST['createur']) || $_POST['createur']==="" || !isset($_POST['actuelle']) || $_POST['actuelle']===""){ //On verifie tous les parametre
					$erreur.="veuillez remplir tous les champs";
					echo $_SESSION['id'];
				}else{
					if($_POST['actuelle']==="true"){//Si il existe déjà une conf actuelle on la met en false
						//a del $resultats=listerConf_actuelle($connex,"true");
						//a del $donnees=$resultats->fetch();
						//$resultats=$connex->exec("UPDATE projet34_configurations  SET conf_actuelle=FALSE WHERE id=".$donnees['id']."; ");	
						appliquer_conf($_POST['contenuconf']);
					}
				}
				if(isset($erreur) && $erreur!==""){
					printErrors(Array($erreur));
				}else{				
					echo 'Ajout effectué avec succès';
					$resultats=addConf($connex,$_POST['contenuconf'],$_SESSION['id']);
					//$resultats=$connex->exec("INSERT INTO projet34_configurations  (contenuconf,createurconf) VALUES ('".$_POST['contenuconf']."',1); ");
				}
				echo '<form method="POST" action="index.php?page=modif_conf"><input type="submit" name="afficher_tout" value="Retour"> </form>';
				
				
			}elseif(isset($_POST['modifier']) && $_POST['modifier']==="Modifier"){//Pour modifier une conf
				echo '<h3>Modification d\'une configuration</h3>';
				if(!isset($_POST['contenuconf'])  || $_POST['contenuconf']==="" ||  !isset($_POST['createur']) || $_POST['createur']===""  || !isset($_POST['actuelle']) || $_POST['actuelle']===""){ //On verifie tous les parametre
					$manquant="veuillez remplir tous les champs";
					printErrors(Array($manquant));
				}else{
					$erreur="";//Si toutesfois il y a des erreurs
					if($_POST['actuelle']==="true"){//Si on veut la passer en configuration actuelle
						$verif=appliquer_conf($_POST['contenuconf']);
						if($verif!=""){
							$erreur.="Erreur dans la configuration: ".$verif."\n";
						}else{
							echo "Configuration appliqué ";
						}
					}
					if(isset($erreur) && $erreur!==""){//Si il y a des erreur
						printErrors(Array($erreur));
					}else{
						echo 'modification effectué avec succès';
						$resultats=$connex->exec("UPDATE projet34_configurations  SET contenuconf='".$_POST['contenuconf']."', createurconf=1 WHERE idconf='".$_POST['id']."'; ");
					}
				}
				echo '<form method="POST" action="index.php?page=modif_conf"><input type="submit" name="afficher_tout" value="Retour"> </form>';		
			}elseif(isset($_POST['afficher_une_conf']) && $_POST['afficher_une_conf']==="ok"){//Pour afficher qu'une seul conf depuis l'affichage simple
				$conf=listerConf_id($connex,$_POST['conf']);
				$row=$conf->fetch();//On affiche qu'une conf
				?>
				<form method="POST"> 
						<input type="hidden" name="conf" value="<?php echo $row['id'];?>">
						<label class="aligner"><span style="vertical-align:top;">fichier dhcpd.conf: </span></label><textarea name="contenuconf" rows="20" cols="50"><?php echo $row['contenuconf'];?></textarea><br />	  
						<label class="aligner">Propriétaire de la configuration: </label><input type="text" name="nom" value="<?php echo $row['nomuser'];?>" readonly="readonly"/><br />
						<label class="aligner">Appliquer la configuration (cela remplaçera la configuration actuelle):</label>  <label>Oui<input type="radio" name="actuelle" value="true"> </label><label>Non: <input type="radio" name="actuelle" value="false"></label><br />
						<input type="submit" value="Ajouter" name="ajouter">
						<input type="submit" value="Modifier" name="modifier">
						<input type="submit" value="retour" name="affichage_simple">
					</form>	
					<?php
			}elseif((isset($_POST['afficher_tout']) && ($_POST['afficher_tout']==="Affichage détaillé" || $_POST['afficher_tout']==="Retour")) || (isset($_GET['id']) && $_GET['id']!=="" && !isset($_POST['affichage_simple'])) || (isset($_POST['selectionner']) && $_POST['selectionner']==="selectionner") || (isset($_POST['trie']) && $_POST['trie']==="trié")){
				
				//On prends le nombre de configuration pour la pagination
				if(isset($_POST['selection_date']) && $_POST['selection_date']!==""){
					$liste_conf=listerConf($connex,"0","0",$_POST['selection_date']);
				}else{
					$liste_conf=listerConf($connex);
				}
				if($liste_conf->rowCount()!==0){//Si il n'y a aucune configuration disponnible on affiche un message
					//Tri
					print 'Afficher seulement:<form method="POST" class="liste_conf"> Les configuration de l\'utilisateur: Après la date:<input type="date" name="selection_date"><input type="submit" value="selectionner" name="selectionner">';
					print 'Trié par date <select name="trie_date"><option value="ASC">Croissant</option><option value="DESC">Descroissant</option></select><input type="submit" value="trié" name="trie"><br /></form>';
				
					$nb_conf=$liste_conf->rowCount();
					//On calcule le nombre de page
					if($nb_conf%NB_CONF_PAGE===0){
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
					if(isset($_POST['selection_date']) && $_POST['selection_date']!==""){
						$liste_conf=listerConf($connex,NB_CONF_PAGE,$id*NB_CONF_PAGE,$_POST['selection_date'],$trie);
					}else{
						$resultats=listerConf($connex,NB_CONF_PAGE,$id*NB_CONF_PAGE,"0",$trie);
					}

					//$i=0;
					foreach ($resultats as $row) {	
					
						//On affiche que 5 element par page
						
					?>
					
						<form method="POST" class="conf"> 
							<input type="hidden" name="id" value="<?php echo $row['idconf'];?>">
							<label class="aligner"><span style="vertical-align:top;">fichier dhcpd.conf: </label></span><textarea class="form" name="contenuconf" rows="20" cols="50"><?php echo $row['contenuconf'];?></textarea><br />				  
							<label class="aligner">Propriétaire de la configuration: </label><input type="text" class="form" name="createur" value="<?php echo $row['nomuser'];?>" readonly="readonly"/><br />
							Appliquer la configuration (cela remplaçera la configuration actuelle):  <label>Oui<input type="radio" name="actuelle" value="true"></label>  <label>Non: <input type="radio" name="actuelle" value="false" checked="checked"></label><br />
							<input type="submit" value="Ajouter" name="ajouter">
							<input type="submit" value="Modifier" name="modifier">
							<input type="submit" value="supprimer" name="supprimer">
						</form>	
					<?php
					}
					
					//On definit les pages a afficher
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
					}
				}else{
					printErrors(Array("Aucune configuration disponnible"));
				}
				print '<form method="POST"><input type="submit" name="affichage_simple" value="Affichage simple"> </form>';
			}else{
	   			echo '<h3>Configuration disponnible</h3>';
				$liste_conf=listerConf($connex);
				if($liste_conf->rowCount()!==0){//On regarde si il y a des configurations à afficher
					print '<form method="POST">';
					print 'Listes configurations: <select name="conf">';
							foreach($liste_conf as $ligne){
								print '<option value="'.$ligne['idconf'].'">'.substr($ligne['creation'],'0','19').'('.$ligne['nomuser'].')</option>'."\n";
							}
							print '</select><input type="submit" name="afficher_une_conf" value="ok"> ';
							if($_SESSION['grade']>2){print '<input type="submit" name="supprimer" value="supprimer"></form>';}
					print '<form method="POST" action="index.php?page=modif_conf"><input type="submit" name="afficher_tout" value="Affichage détaillé"> </form>';
				}else{
					printErrors(Array("Aucune configuration disponnible"));
				}
			}
	}else{
			printErrors(Array("Le serveur n'est pas installé"));
	}
}else{
	printErrors(Array("Vous n'avez pas un grade suffisant"));
}
?>
</article>
