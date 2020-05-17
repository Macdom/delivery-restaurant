<?php
	session_start();
	if ($_SESSION['login'] != 1 || $_SESSION['admin'] != 1){
		header("Location: ../login/admin.php");
		exit();
	}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <title>Dodaj pojazd</title>
  <meta HTTP-EQUIV="Content-Type" content="text/html"; charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="../style/form_style.css">
  <script src="../js/pojazd.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
</head>

<body>

<?php
	$model = $cena = $modelErr = $cenaErr = "";
	$conn = pg_connect("host=(insert hostname here) dbname=(insert dbname here) user=(insert username here) password=(insert password here)");
	$stat = pg_connection_status($conn);
	$valid = true; 
	
	if($stat === PGSQL_CONNECTION_OK){
	if (isset($_POST['wyslij'])){
		if(empty($_POST["model"])){
			$modelErr = "Nie wpisano pola Model.";
			$valid = false;
		}
		else{
			$model = test_input($_POST["model"]);
		}	

		if(empty($_POST["cena"])){
			$cenaErr = "Nie wpisano pola Cena.";
			$valid = false;
		}
		else{
			$cena = test_input($_POST["cena"]);
			if(!preg_match("/([0-9]*[.])[0-9][0-9]/", $cena)) {
				$cenaErr = "W polu Cena można wpisać tylko liczbę całkowitą z 2 liczbami po kropce."; 
				$valid = false;
			}	
		}			
	
		if ($valid = true){
			pg_query("SET search_path TO projekt");
			pg_query("INSERT INTO pojazd(model, cena, dostepny) VALUES('$model', '$cena', 'true')");
			header("Location: ../main/admin.php");
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
		<h1 class="display-4">Dodaj nowy pojazd</h1>
		
		<div class="row">
		<div class="col-sm-9">
			<div class="form-group row">
			<label for="data" class="col-sm-4 col-form-label"><b></b></label>
			</div>

		<form enctype="multipart/form-data" name="form" id="form" method="post"
			action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>"  onsubmit = "return Walidacja()">  
			<div class="form-group row">
				<label for="model" class="col-sm-4 col-form-label"><b>Model pojazdu:</b></label>
				<div class="col-sm-7">	
					<input type="text" class="form-control" id="model" name="model" value="<?php echo $model;?>" placeholder="Model...">	
				</div>
			</div>		
			<div class="form-group row">
				<label for="cena" class="col-sm-4 col-form-label"><b>Cena pojazdu (zł):</b></label>
				<div class="col-sm-7">	
					<input type="text" class="form-control" id="cena" name="cena" value="<?php echo $cena;?>" placeholder="Cena (zł)...">	
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
			<p><span class="error"><?php echo $cenaErr;?></span></p>
			<p><span class="error"><?php echo $modelErr;?></span></p>
			</div>
		</div>
	</div>
</body>
</html>