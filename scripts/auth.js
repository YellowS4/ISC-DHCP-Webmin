/*
 * Code par Jason Gantner
 */
function digest(){
	//initialisation de variables pour la lisibilité
	pseudo = document.getElementById("pseudo");
	password = document.getElementById("pass");
	chall1 = document.getElementById("ch1");
	chall2 = document.getElementById("ch2");
	//génération du challenge2 en hexa
	chall2.value = CryptoJS.lib.WordArray.random(16).toString();
	//hash de "user:pass"
	var h1 = CryptoJS.SHA512(pseudo.value+':'+password.value).toString(CryptoJS.enc.Hex);
	//hash de chall1
	var h2 = CryptoJS.SHA512(chall1.value).toString(CryptoJS.enc.Hex);
	//hash de chall2
	var h3 = CryptoJS.SHA512(chall2.value).toString(CryptoJS.enc.Hex);
	//on remplace le pass par le hash de h1:h2:h3 encodé en hexadécimal
	password.value = CryptoJS.SHA512(h1+":"+h2+":"+h3).toString(CryptoJS.enc.Hex);
	//on autorise l'envoie du formulaire
	return true;
      }