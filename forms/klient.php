<!DOCTYPE html>
<html lang="pl">
<head>
  <title>Nowe zamówienie</title>
  <meta HTTP-EQUIV="Content-Type" content="text/html"; charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="../style/form_style.css">
  <script src="../js/klient.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
</head>

<body>

<?php
	$imie = $nazwisko = $telefon = $imieErr = $nazwiskoErr = $telefonErr = "";
	$conn = pg_connect("host=(insert hostname here) dbname=(insert dbname here) user=(insert username here) password=(insert password here)");
	$stat = pg_connection_status($conn);
	$valid = true;
	
	if($stat === PGSQL_CONNECTION_OK){
	if (isset($_POST['wyslij'])){
		if(empty($_POST["imie"])){
			$imieErr = "Nie wpisano pola Imie.\n";
			$valid = false;
		}
		else{
			$imie = test_input($_POST["imie"]);
			if(!preg_match("/[A-ZĄĆĘŁÓŚŻŹ][a-ząćęłóśźż]*$/", $imie)) {
				$imieErr = "Nieprawidłowy format imienia.\n"; 
				$valid = false;
			}	
		}	
		
		if(empty($_POST["nazwisko"])){
			$nazwiskoErr = "Nie wpisano pola Nazwisko.\n";
			$valid = false;
		}
		else{
			$nazwisko = test_input($_POST["nazwisko"]);
			if(!preg_match("/[A-ZĄĆĘŁÓŚŻŹ][a-ząćęłóśźż-]*$/", $nazwisko)) {
				$nazwiskoErr = "Nieprawidłowy format nazwiska.\n"; 
				$valid = false;
			}	
		}	
		
		if(empty($_POST["telefon"])){
			$telefonErr = "Nie wpisano pola Numer telefonu.\n";
			$valid = false;
		}
		else{
			$telefon = test_input($_POST["telefon"]);
			if(!preg_match("/[0-9]*/", $telefon)) {
				$telefonErr = "W polu Numer telefonu można wpisać tylko cyfry.\n"; 
				$valid = false;
			}	
		}			
	
		if ($valid = true){
			pg_query("SET search_path TO projekt");
			pg_query("INSERT INTO klient(imie, nazwisko, telefon) VALUES('$imie', '$nazwisko', '$telefon')");
			header("Location: ../main/klient.php");
			exit();
		}
	}
	}
	
	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
?> 

	<div class="container">
	<br><br><br>
		<h1 class="display-4">Nowe zamówienie</h1>
		
		<div class="row">
		<div class="col-sm-9">
			<div class="form-group row">
			<label for="data" class="col-sm-4 col-form-label"><b></b></label>
			</div>

		<form enctype="multipart/form-data" name="form" id="form" method="post"
			action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>"  onsubmit = "return Walidacja()">  
			<div class="form-group row">
				<label for="imie" class="col-sm-4 col-form-label"><b>Imię klienta:</b></label>
				<div class="col-sm-7">	
					<input type="text" class="form-control" id="imie" name="imie" value="<?php echo $imie;?>" placeholder="Imię...">	
				</div>
			</div>	
			<div class="form-group row">
				<label for="nazwisko" class="col-sm-4 col-form-label"><b>Nazwisko klienta:</b></label>
				<div class="col-sm-7">	
					<input type="text" class="form-control" id="nazwisko" name="nazwisko" value="<?php echo $nazwisko;?>" placeholder="Nazwisko...">	
				</div>
			</div>			
			<div class="form-group row">
				<label for="telefon" class="col-sm-4 col-form-label"><b>Numer telefonu:</b></label>
				<div class="col-sm-7">	
					<input type="text" class="form-control" id="telefon" name="telefon" value="<?php echo $telefon;?>" placeholder="Numer telefonu...">	
				</div>
			</div>		
			
			<div class="form-group row">
			<div class="col-sm-4">
				</div>
				<div class="col-sm-7">
					<input type="submit" id="wyslij" name="wyslij" class="btn btn-primary" value="Wyślij">
					<a class="btn btn-secondary" href="../main/klient.php">Powrót</a>
				</div>
			</div>	
		</form>
			
		<div class="row">
				<div class="col-sm-4">
				</div>
				<div class="col-sm-7">		
			</div></div>
			<br>
		</div>
			<div class="col-sm-3">	
			<br><br>
			<p><span class="error"><?php echo $imieErr;?></span></p>
			<p><span class="error"><?php echo $nazwiskoErr;?></span></p>
			<p><span class="error"><?php echo $telefonErr;?></span></p>
			</div>
		</div>
	</div>
</body>
</html>