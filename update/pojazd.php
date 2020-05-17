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
  <title>Modyfikacja pojazdu</title>
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
	$stary_model = $stara_cena = $model = $cena = $modelErr = $cenaErr = "";
	$conn = pg_connect("host=(insert hostname here) dbname=(insert dbname here) user=(insert username here) password=(insert password here)");
	$stat = pg_connection_status($conn);
	$valid = true; 
	
	$id = $_SESSION['id'];
	echo $id;
	
	if($stat === PGSQL_CONNECTION_OK){
	pg_query("SET search_path TO projekt");
	$poj_query = pg_query("SELECT model, cena FROM pojazd WHERE id_poj = $id");
	$poj_row = pg_fetch_assoc($poj_query);
	$stara_cena = $poj_row['cena'];
	$stary_model = $poj_row['model'];
	
	if (isset($_POST['zmien'])){
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
			pg_query("UPDATE pojazd SET model = '$model', cena = '$cena' WHERE id_poj = $id");
			header("Location: ../list/pojazd.php");
			exit();
		}
	}
	
	if (isset($_POST['usun'])){
		pg_query("DELETE FROM dzien_pracy WHERE id_poj = $id");
		pg_query("DELETE FROM pojazd WHERE id_poj = $id");
		header("Location: ../list/pojazd.php");
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
		<h1 class="display-4">Modyfikacja pojazdu</h1>
		
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
					<input type="text" class="form-control" id="model" name="model" value="<?php echo $stary_model;?>" placeholder="Model...">	
				</div>
			</div>		
			<div class="form-group row">
				<label for="cena" class="col-sm-4 col-form-label"><b>Cena pojazdu (zł):</b></label>
				<div class="col-sm-7">	
					<input type="text" class="form-control" id="cena" name="cena" value="<?php echo $stara_cena;?>" placeholder="Cena (zł)...">	
				</div>
			</div>		
			
			<div class="form-group row">
			<div class="col-sm-4">
				</div>
				<div class="col-sm-7">
					<input type="submit" id="zmien" name="zmien" class="btn btn-primary" value="Zapisz zmiany">
					<input type="submit" id="usun" name="usun" class="btn btn-danger" value="Usuń pojazd">
					<hr/>
					<a class="btn btn-secondary" href="../list/pojazd.php">Powrót</a>	
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