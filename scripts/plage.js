var totalOptions=1;
function verifPlage(){
  //initialisation des variables
  out=true;
  debut = document.getElementById("plage_debut").value.split('.');
  fin = document.getElementById("plage_fin").value.split('.');
  i = 0;
  deb_valid = true;
  fin_valid = true;
  deb_inf_fin =true;
  alert_msg='';
  //comparaison des IP fin et debut et verification de la validitÃ© des IPs
  while(i < 4){
    if( debut[i] < 0 || debut[i] > 255 ){ deb_valid=false; }
    if( fin[i] < 0 || fin[i] > 255 ){ fin_valid = false; }
    if( i==0){
      if( debut[i] > fin[i]){ deb_inf_fin = false; }
    }
    else{
      if( debut[i] > fin[i] && debut[i-1] >= fin[i-1] ){ deb_inf_fin = false; }
    }
    i++;
  }
  if(!fin_valid){
    alert_msg+="La fin de la plage n'est pas une adresse valide !\n";
  }
  if(!deb_valid){
    alert_msg+="Le début de la plage n'est pas une adresse valide !\n";
  }
  if(!deb_inf_fin){
    alert_msg+="Le début de la plage est supérieur à  la fin !\n";
  }
  alert(alert_msg);
};

// function update_options(){
//   options=document.getElementById("options");
//   if(options.children[options.children.length-1].value!=''){
//     elem = document.getElementById("options").children[0].cloneNode();
//     elem.setAttribute('name','option'+totalOptions);
//     for(option in document.getElementById("options").children[0].children){ elem.appendChild(option)};
//     document.getElementById("options").appendChild(elem);
//     elem = document.getElementById("options").children[1].cloneNode();
//     elem.setAttribute('name','option'+totalOptions+'_value');
//     document.getElementById("options").appendChild(elem);
//   }
// };