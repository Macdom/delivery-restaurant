<?php
	session_start();
	if ($_SESSION['login'] != 1 || $_SESSION['kurier'] != 1){
		header("Location: ../login/kurier.php");
		exit();
	}
	else if ($_SESSION['praca'] == 1){
		header("Location: ../main/kurier_praca.php");
		exit();
	}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <title>Panel kuriera</title>
  <meta HTTP-EQUIV="Content-Type" content="text/html"; charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="../style/form_style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
</head>

<body>
	<div class="container">
	<br><br><br>
		<h1 class="display-4">Panel kuriera</h1>
		<?php
			$id = $_SESSION['kurierID'];
			$conn = pg_connect("host=(insert hostname here) dbname=(insert dbname here) user=(insert username here) password=(insert password here)");
			$stat = pg_connection_status($conn);
			if($stat === PGSQL_CONNECTION_OK){
				pg_query("SET search_path TO projekt");
				$result = pg_query("SELECT imie FROM kurier WHERE id_kur = '$id'");
				$result2 = pg_query("SELECT nazwisko FROM kurier WHERE id_kur = '$id'");
				$pojazdy_query = pg_query("SELECT id_poj, model FROM pojazd WHERE dostepny='true' ORDER BY id_poj");
				$row = pg_fetch_row($result);
				$row2 = pg_fetch_row($result2);
				$pojazdy = array();
				$idpoj = array();
				while($pojazdy_row = pg_fetch_assoc($pojazdy_query)) {
					$pojazdy[] = $pojazdy_row['model'];
					$idpoj[] = $pojazdy_row['id_poj'];
				}
				
				$imie = $row[0];
				$nazwisko = $row2[0];
				if (isset($_POST['wyslij'])){
					$wybor = $_POST["wybor"];
					$_SESSION['praca'] = 1;
					$_SESSION['pojazd'] = $wybor;
					pg_query("INSERT INTO dzien_pracy (id_kur, id_poj) VALUES($id, $wybor)");
					pg_query("UPDATE kurier SET dostepny='true' WHERE id_kur = $id");
					pg_query("UPDATE pojazd SET dostepny='false' WHERE id_poj = $wybor");
					header("Location: ../main/kurier_praca.php");
					exit();
				}
			}
		?>
		<p>Zalogowano jako: <strong><?php echo $imie . " " . $nazwisko ?></strong></p>
			
			<form enctype="multipart/form-data" name="form" id="form" method="post"
			action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>">  
			<div class="form-group row">
				<label for="wybor" class="col-sm-4 col-form-label"><b>Wybierz pojazd:</b></label>
				<div class="col-sm-7">
					<select class="form-control" id="wybor" name="wybor">
						<?php 
							for($i = 0; $i < count($pojazdy); $i++){
								echo '<option value=' . $idpoj[$i] . '>' . $pojazdy[$i] . "</option>";
							}
						?>
					</select>
				</div>
			</div>	
			<div class="form-group row">
			<div class="col-sm-4">
				</div>
				<div class="col-sm-7">
					<input type="submit" id="wyslij" name="wyslij" class="btn btn-primary" value="Zacznij dzień pracy">
				</div>
			</div>	
		</form>
		<a class="btn btn-secondary" href="../logout.php">Wyloguj się</a>
	</div>
</body>
</html>