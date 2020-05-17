function Walidacja(){
	var errors = "";
	var valid = true;
	
	var ad = document.forms["form"]["adres"].value;
	if(ad == ""){
		errors=errors.concat("Nie wpisano pola Adres.\n");
		valid = false;
	}	
	
	var gl = document.forms["form"]["glowne"].value;
	var zu = document.forms["form"]["zupa"].value;
	var d1 = document.forms["form"]["dodatek1"].value;
	var d2 = document.forms["form"]["dodatek2"].value;
	var na = document.forms["form"]["napoj"].value;
	
	if(gl == 'brak' && zu == 'brak' && d1 == 'brak' && d2 == 'brak' && na == 'brak'){
		errors = errors.concat("Nie wybrano żadnego elementu z menu.");
		valid = false;
	}
	
	if(valid == false) alert(errors);
	return valid;
}

