function selection(a_afficher){
		
	var conf = ["conf",  //On cree les tableau de toutes les class qu'il faut modifier
    "conf_default",
    "conf_actuelle",
    "conf_default_actuelle"];
	
	var defaults = ["conf_default",
    "conf_default_actuelle"];
	
	var actuelle = ["conf_actuelle",
    "conf_default_actuelle"];
	if(a_afficher=="conf"){
		if(document.getElementById("selection_"+a_afficher).checked){
			//On parcours le tableau des possibilite			
			for(j=0; j<conf.length;j++){
				for (i=0; i<document.getElementsByClassName(conf[j]).length; i++){
					document.getElementsByClassName(conf[j])[i].style.display="block";
				}
			}
		}else{
			for(j=0; j<conf.length;j++){
				for (i=0; i<document.getElementsByClassName(conf[j]).length; i++){			
					document.getElementsByClassName(conf[j])[i].style.display="none";
				}
			}
		}
		
	}else if(a_afficher=="default"){
		if(!document.getElementById("selection_conf").checked){
			if(document.getElementById("selection_"+a_afficher).checked){
				for(j=0; j<defaults.length;j++){
					for (i=0; i<document.getElementsByClassName(defaults[j]).length; i++){
						document.getElementsByClassName(defaults[j])[i].style.display="block";
						
					}
				}
			}else{
				for(j=0; j<defaults.length;j++){
					for (i=0; i<document.getElementsByClassName(defaults[j]).length; i++){
						document.getElementsByClassName(defaults[j])[i].style.display="none";
					}
				}
			}
		}
		
	}else if (a_afficher=="actuelle"){
	//On ne fait rien si la case Tous est encore active
		if(!document.getElementById("selection_conf").checked){
			if(document.getElementById("selection_"+a_afficher).checked){
			for(j=0; j<actuelle.length;j++){
				for (i=0; i<document.getElementsByClassName(actuelle[j]).length; i++){
					document.getElementsByClassName(actuelle[j])[i].style.display="block";
				}
			}
		}	else{			
				for(j=0; j<actuelle.length;j++){
					for (i=0; i<document.getElementsByClassName(actuelle[j]).length; i++){
						document.getElementsByClassName(actuelle[j])[i].style.display="none";
					}
				}
			}
		}
	}

}

