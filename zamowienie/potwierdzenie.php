<?php
	session_start();
	if ($_SESSION['login'] != 1 || $_SESSION['klient'] != 1){
		header("Location: ../login/klient.php");
		exit();
	}
	
	else if ($_SESSION['potw'] != 1){
		header("Location: ../main/klient.php");
	}
	
	else{
		if (isset($_GET['zam'])) $_SESSION['zam'] = $_GET['zam'];
	}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <title>Potwierdzenie zamówienia</title>
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
		<h1 class="display-4">Potwierdzenie zamówienia</h1>
		<?php
			$conn = pg_connect("host=(insert hostname here) dbname=(insert dbname here) user=(insert username here) password=(insert password here)");
			$stat = pg_connection_status($conn);
			if($stat === PGSQL_CONNECTION_OK){
			pg_query("SET search_path TO projekt");
			
			$zam = $_SESSION['zam'];
			$cena = 0.0;
			$result = pg_query("SELECT nazwa, cena FROM menu INNER JOIN posilek ON menu.id_menu = posilek.id_menu WHERE posilek.id_zam = $zam");
			if (pg_num_rows($result) > 0) {
			echo "<p>Twoje zamówienie</p>";
			echo "<table><tr class='nagl'><th>Nazwa</th><th>Cena</th></tr>";
			while($row = pg_fetch_assoc($result)) {
				echo "<tr>";
				$cena = $cena + $row["cena"];
				echo "<td>" . $row["nazwa"]. "</td><td>" . $row["cena"]."</td>";
				echo "</tr>";
			}
			echo "</table>";
		} 
			echo "<p>Do zapłaty: " . $cena . " zł.<p>";
			}
			
			if (isset($_POST['wyslij'])){
				pg_query("UPDATE zamowienie SET cena = $cena, data_zam = now() WHERE id_zam = $zam");
				header("Location: ../main/klient.php");
				exit();				
			}
			if(isset($_POST['wycofaj'])){
				pg_query("DELETE FROM posilek WHERE id_zam = $zam");
				pg_query("DELETE FROM zamowienie WHERE id_zam = $zam");
				pg_query("ALTER SEQUENCE zamowienie_id_zam_seq RESTART WITH $zam");
				header("Location: ../main/klient.php");
				exit();
			}

		?>
			<form enctype="multipart/form-data" name="form" id="form" method="post"
			action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>"  onsubmit = "return Walidacja()">  
			<div class="form-group row">
			<div class="col-sm-4">
				</div>
				<div class="col-sm-7">
					<input type="submit" id="wyslij" name="wyslij" class="btn btn-primary" value="Potwierdź zamówienie">
					<input type="submit" id="wycofaj" name="wycofaj" class="btn btn-danger" value="Wycofaj zamówienie">
				</div>
			</div>	
		</form>
	</div>
</body>
</html>