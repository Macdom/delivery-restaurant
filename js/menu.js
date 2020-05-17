function Walidacja(){
	var errors = "";
	var valid = true;
	
	var naz = document.forms["form"]["nazwa"].value;
	if(naz == ""){
		errors=errors.concat("Nie wpisano pola Nazwa.\n");
		valid = false;
	}
	
	if(valid == false) alert(errors);
	return valid;
}
