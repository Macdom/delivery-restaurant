function Walidacja(){
	var errors = "";
	var valid = true;
	
	var mod = document.forms["form"]["model"].value;
	if(mod == ""){
		errors=errors.concat("Nie wpisano pola Model.\n");
		valid = false;
	}

	var cen = document.forms["form"]["cena"].value;
	if(cen == ""){
		errors=errors.concat("Nie wpisano pola Cena.\n");
		valid = false;
	}
	else{
		var reg = /([0-9]*[.])[0-9][0-9]/;
		if((reg.test(cen))==false){
			errors=errors.concat("W polu Cena można wpisać tylko liczbę całkowitą z 2 liczbami po kropce.");
			valid = false;
		}
	}		
	
	if(valid == false) alert(errors);
	return valid;
}

