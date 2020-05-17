<?php
	session_start();
	if ($_SESSION['login'] != 1 || $_SESSION['admin'] != 1){
		header("Location: ../login/admin.php");
		exit();
	}
	if ($_SESSION['skladniki'] == 1){
		header("Location: menu_skladniki.php");
	}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <title>Dodaj element menu</title>
  <meta HTTP-EQUIV="Content-Type" content="text/html"; charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="../style/form_style.css">
  <script src="../js/menu.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
</head>

<body>

<?php
	$nazwa = $cena = $typ = $liczba = $nazwaErr = $cenaErr = "";

	$valid = true;
	
	if (isset($_POST['wyslij'])){
		if(empty($_POST["nazwa"])){
			$modelErr = "Nie wpisano pola Nazwa.";
			$valid = false;
		}
		else{
			$nazwa = test_input($_POST["nazwa"]);
		}	
		

	
		if ($valid = true){
			$_SESSION['skladniki'] = 1;
			
			$typ = $_POST["typ"];
			$liczba = $_POST["liczba"];
			$_SESSION['liczbaProd'] = $liczba;
			$_SESSION['typProd'] = $typ;
			$_SESSION['nazwaProd'] = $nazwa;
			header("Location: menu_skladniki.php");
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
		<h1 class="display-4">Dodaj nowy element menu</h1>
		
		<div class="row">
		<div class="col-sm-9">
			<div class="form-group row">
			<label for="data" class="col-sm-4 col-form-label"><b></b></label>
			</div>

		<form enctype="multipart/form-data" name="form" id="form" method="post"
			action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>"  onsubmit = "return Walidacja()">  
			<div class="form-group row">
				<label for="nazwa" class="col-sm-4 col-form-label"><b>Nazwa potrawy:</b></label>
				<div class="col-sm-7">	
					<input type="text" class="form-control" id="nazwa" name="nazwa" value="<?php echo $nazwa;?>" placeholder="Nazwa...">	
				</div>
			</div>				
			<div class="form-group row">
				<label for="typ" class="col-sm-4 col-form-label"><b>Typ:</b></label>
				<div class="col-sm-7">	
					<select class="form-control" id="typ" name="typ">
						<option value="glowne">Danie główne</option>
						<option value="zupa">Zupa</option>
						<option value="dodatek">Dodatek</option>
						<option value="napoj">Napój</option>
					</select>
				</div>
			</div>
			<div class="form-group row">
				<label for="liczba" class="col-sm-4 col-form-label"><b>Liczba składników:</b></label>
				<div class="col-sm-7">	
					<select class="form-control" id="liczba" name="liczba">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
					</select>
				</div>
			</div>				
			
			<div class="form-group row">
			<div class="col-sm-4">
				</div>
				<div class="col-sm-7">
					<input type="submit" id="wyslij" name="wyslij" class="btn btn-primary" value="Wyślij">
					<a class="btn btn-secondary" href="../main/admin.php">Powrót</a>
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
			<p><span class="error"><?php echo $nazwaErr;?></span></p>
			</div>
		</div>
	</div>
</body>
</html>