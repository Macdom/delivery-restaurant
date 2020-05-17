<?php
	session_start();
	if ($_SESSION['login'] != 1 || $_SESSION['kucharz'] != 1){
		header("Location: ../login/kucharz.php");
		exit();
	}
	
	else{
		if (isset($_GET['id'])) $_SESSION['id'] = $_GET['id'];
	}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <title>Przygotowanie zamówienia</title>
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
		<h1 class="display-4">Przygotowanie zamówienia</h1>
		<?php
			$conn = pg_connect("host=(insert hostname here) dbname=(insert dbname here) user=(insert username here) password=(insert password here)");
			$stat = pg_connection_status($conn);
			if($stat === PGSQL_CONNECTION_OK){
			pg_query("SET search_path TO projekt");
			
			$id = $_SESSION['kucharzID'];
			$zam = $_SESSION['id'];
			echo $zam;
			$cena = 0.0;
			$result3 = pg_query("SELECT nazwa FROM menu INNER JOIN posilek ON menu.id_menu = posilek.id_menu WHERE posilek.id_zam = $zam");
			if (pg_num_rows($result3) > 0) {
			echo "<p>Posiłki do przygotowania:</p>";
			echo "<table><tr class='nagl'><th>Nazwa</th></tr>";
			while($row = pg_fetch_assoc($result3)) {
				echo "<tr>";
				echo "<td>" . $row["nazwa"]. "</td>";
				echo "</tr>";
			}
			echo "</table>";
		} 
			}
			
			if (isset($_POST['wyslij'])){
				pg_query("UPDATE zamowienie SET id_kuch = $id, stan = 2, data_przyg = now() WHERE id_zam = $zam");
				header("Location: ../main/kucharz.php");
				exit();				
			}

		?>
			<form enctype="multipart/form-data" name="form" id="form" method="post"
			action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>"  onsubmit = "return Walidacja()">  
			<div class="form-group row">
			<div class="col-sm-4">
				</div>
				<div class="col-sm-7">
					<input type="submit" id="wyslij" name="wyslij" class="btn btn-primary" value="Przygotuj potrawy">
				</div>
			</div>	
		</form>
	</div>
</body>
</html>