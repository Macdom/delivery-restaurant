<?php
	session_start();
	if ($_SESSION['login'] != 1 || $_SESSION['kurier'] != 1){
		header("Location: ../login/kurier.php");
		exit();
	}
	
	else{
		if (isset($_GET['id'])) $_SESSION['id'] = $_GET['id'];
	}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <title>Odbieranie zamówienia</title>
  <meta HTTP-EQUIV="Content-Type" content="text/html"; charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="../style/form_style.css">
  <link rel="stylesheet" href="../style/list_style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
</head>

<body>
<body>
	<div class="container">
	<br><br><br>
		<h1 class="display-4">Odbieranie zamówienia</h1>
		<?php
			$conn = pg_connect("host=(insert hostname here) dbname=(insert dbname here) user=(insert username here) password=(insert password here)");
			$stat = pg_connection_status($conn);
			if($stat === PGSQL_CONNECTION_OK){
			pg_query("SET search_path TO projekt");
			
			$id = $_SESSION['kurierID'];
			$zam = $_SESSION['id'];
			
			$klient_query = pg_query("SELECT k.imie, k.nazwisko, k.telefon FROM klient k INNER JOIN zamowienie z ON z.id_kl = k.id_kl WHERE id_zam = $zam");
			$klient_row = pg_fetch_assoc($klient_query);
			$imieKlienta = $klient_row['imie'];
			$nazwiskoKlienta = $klient_row['nazwisko'];
			$telefonKlienta = $klient_row['telefon'];
			
			$adres_query = pg_query("SELECT adres FROM zamowienie WHERE id_zam = $zam");
			$adres_row = pg_fetch_row($adres_query);
			$adres = $adres_row[0];
			
			echo "<p>Klient: ". $imieKlienta . " " . $nazwiskoKlienta . ", nr: " . $telefonKlienta . "</p>";
			echo "<p>Adres do dostawy: ". $adres . "</p>";
			}
			
			if (isset($_POST['wyslij'])){
				$_SESSION['dostawa'] = 1;
				pg_query("UPDATE zamowienie SET id_kur = $id, stan = 2, data_kur = now() WHERE id_zam = $zam");
				header("Location: ../main/dostawa.php?id=" . $zam);
				exit();				
			}

		?>
			<form enctype="multipart/form-data" name="form" id="form" method="post"
			action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>">  
			<div class="form-group row">
			<div class="col-sm-4">
				</div>
				<div class="col-sm-7">
					<input type="submit" id="wyslij" name="wyslij" class="btn btn-primary" value="Odbierz zamówienie do dostawy">
				</div>
			</div>	
		</form>
	</div>
</body>
</html>