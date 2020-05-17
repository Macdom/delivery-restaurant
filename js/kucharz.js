function Walidacja(){
	var errors = "";
	var valid = true;
	
	var im = document.forms["form"]["imie"].value;
	if(im == ""){
		errors=errors.concat("Nie wpisano pola Imie.\n");
		valid = false;
	}
	else{
		var reg = /[A-ZĄĆĘŁÓŚŻŹ][a-ząćęłóśźż-]*$/;
		if((reg.test(im))==false){
			errors=errors.concat("Nieprawidłowy format imienia\n");
			valid = false;
		}
	}		
	
	var naz = document.forms["form"]["nazwisko"].value;
	if(naz == ""){
		errors=errors.concat("Nie wpisano pola Nazwisko.\n");
		valid = false;
	}
	else{
		var reg = /[A-ZĄĆĘŁÓŚŻŹ][a-ząćęłóśźż-]*$/;
		if((reg.test(naz))==false){
			errors=errors.concat("Nieprawidłowy format nazwiska\n");
			valid = false;
		}
	}
	
	var plac = document.forms["form"]["placa"].value;
	if(plac == ""){
		errors=errors.concat("Nie wpisano pola Płaca.\n");
		valid = false;
	}
	else{
		var reg = /[0-9]*/;
		if((reg.test(plac))==false){
			errors=errors.concat("W polu Płaca można wpisać tylko cyfry.\n");
			valid = false;
		}
	}
	
	var tel = document.forms["form"]["telefon"].value;
	if(tel == ""){
		errors=errors.concat("Nie wpisano pola Numer telefonu.\n");
		valid = false;
	}
	else{
		var reg = /[0-9]*/;
		if((reg.test(tel))==false){
			errors=errors.concat("W polu Numer telefonu można wpisać tylko cyfry.\n");
			valid = false;
		}
	}		
	
	if(valid == false) alert(errors);
	return valid;
}

