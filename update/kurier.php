<?php
	session_start();
	if ($_SESSION['login'] != 1 || $_SESSION['admin'] != 1){
		header("Location: ../login/admin.php");
		exit();
	}
	
	else{
		if (isset($_GET['id'])) $_SESSION['id'] = $_GET['id'];
	}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <title>Modyfikacja kuriera</title>
  <meta HTTP-EQUIV="Content-Type" content="text/html"; charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="../style/form_style.css">
  <script src="../js/kurier.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
</head>

<body>

<?php
	$stare_imie = $imie =  $stare_nazwisko = $nazwisko = $stara_placa = $placa = $stary_telefon = $telefon = $imieErr = $nazwiskoErr = $placaErr = $telefonErr = "";
	$conn = pg_connect("host=(insert hostname here) dbname=(insert dbname here) user=(insert username here) password=(insert password here)");
	$stat = pg_connection_status($conn);
	$valid = true;
	$id = $_SESSION['id'];
	
	if($stat === PGSQL_CONNECTION_OK){
	pg_query("SET search_path TO projekt");
	$kur_query = pg_query("SELECT imie, nazwisko, placa, telefon FROM kurier WHERE id_kur = $id");
	$kur_row = pg_fetch_assoc($kur_query);
	$stare_imie = $kur_row['imie'];
	$stare_nazwisko = $kur_row['nazwisko'];
	$stara_placa = $kur_row['placa'];
	$stary_telefon = $kur_row['telefon'];

	if (isset($_POST['zmien'])){
		if(empty($_POST["imie"])){
			$imieErr = "Nie wpisano pola Imie.\n";
			$valid = false;
		}
		else{
			$imie = test_input($_POST["imie"]);
			if(!preg_match("/[A-ZĄĆĘŁÓŚŻŹ][a-ząćęłóśźż-]*$/", $imie)) {
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

		if(empty($_POST["placa"])){
			$placaErr = "Nie wpisano pola Płaca.\n";
			$valid = false;
		}
		else{
			$placa = test_input($_POST["placa"]);
			if(!preg_match("/[0-9]*/", $placa)) {
				$placaErr = "W polu Płaca można wpisać tylko cyfry.\n"; 
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
			pg_query("UPDATE kurier SET imie = '$imie', nazwisko = '$nazwisko', placa = '$placa', telefon = '$telefon' WHERE id_kur = $id");
			header("Location: ../list/kurier.php");
			exit();
		}
	}
	
	if (isset($_POST['usun'])){
		pg_query("DELETE FROM kurier WHERE id_kur = $id");
		header("Location: ../list/kurier.php");
		exit();
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
		<h1 class="display-4">Modyfikacja kucharza</h1>
		
		<div class="row">
		<div class="col-sm-9">
			<div class="form-group row">
			<label for="data" class="col-sm-4 col-form-label"><b></b></label>
			</div>

		<form enctype="multipart/form-data" name="form" id="form" method="post"
			action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>"  onsubmit = "return Walidacja()">  
			<div class="form-group row">
				<label for="imie" class="col-sm-4 col-form-label"><b>Imię kuriera:</b></label>
				<div class="col-sm-7">	
					<input type="text" class="form-control" id="imie" name="imie" value="<?php echo $stare_imie;?>" placeholder="Imię...">	
				</div>
			</div>	
			<div class="form-group row">
				<label for="nazwisko" class="col-sm-4 col-form-label"><b>Nazwisko kuriera:</b></label>
				<div class="col-sm-7">	
					<input type="text" class="form-control" id="nazwisko" name="nazwisko" value="<?php echo $stare_nazwisko;?>" placeholder="Nazwisko...">	
				</div>
			</div>	
			<div class="form-group row">
				<label for="placa" class="col-sm-4 col-form-label"><b>Płaca (zł):</b></label>
				<div class="col-sm-7">	
					<input type="text" class="form-control" id="placa" name="placa" value="<?php echo $stara_placa;?>" placeholder="Płaca (zł)...">	
				</div>
			</div>				
			<div class="form-group row">
				<label for="telefon" class="col-sm-4 col-form-label"><b>Numer telefonu:</b></label>
				<div class="col-sm-7">	
					<input type="text" class="form-control" id="telefon" name="telefon" value="<?php echo $stary_telefon;?>" placeholder="Numer telefonu...">	
				</div>
			</div>		
			
			<div class="form-group row">
			<div class="col-sm-4">
				</div>
				<div class="col-sm-7">
					<input type="submit" id="zmien" name="zmien" class="btn btn-primary" value="Zapisz zmiany">
					<input type="submit" id="usun" name="usun" class="btn btn-danger" value="Usuń kuriera">
					<hr/>
					<a class="btn btn-secondary" href="../list/kurier.php">Powrót</a>	
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
			<p><span class="error"><?php echo $placaErr;?></span></p>
			<p><span class="error"><?php echo $telefonErr;?></span></p>
			</div>
		</div>
	</div>
</body>
</html>