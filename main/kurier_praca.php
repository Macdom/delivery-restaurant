<?php
	session_start();
	if ($_SESSION['login'] != 1 || $_SESSION['kurier'] != 1){
		header("Location: ../main/kurier.php");
		exit();
	}
	else if ($_SESSION['praca'] !== 1){
		header("Location: ../main/kurier.php");
		exit();
	}
	else if ($_SESSION['dostawa'] == 1){
		$id = $_SESSION['id'];
		header("Location: ../main/dostawa.php?id=" + $id);
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
  <link rel="stylesheet" href="../style/list_style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
  <script src="../js/odbior.js"></script>
</head>

<body>
	<div class="container">
	<br><br><br>
		<h1 class="display-4">Panel kuriera</h1>
		<?php
			$id = $_SESSION['kurierID'];
			$pojazdID = $_SESSION['pojazd'];
			$conn = pg_connect("host=(insert hostname here) dbname=(insert dbname here) user=(insert username here) password=(insert password here)");
			$stat = pg_connection_status($conn);
			if($stat === PGSQL_CONNECTION_OK){
				pg_query("SET search_path TO projekt");
				$result = pg_query("SELECT imie FROM kurier WHERE id_kur = '$id'");
				$result2 = pg_query("SELECT nazwisko FROM kurier WHERE id_kur = '$id'");
				$result3 = pg_query("SELECT  model FROM pojazd WHERE id_poj = '$pojazdID'");
				$row = pg_fetch_row($result);
				$row2 = pg_fetch_row($result2);
				$row3 = pg_fetch_row($result3);
				$imie = $row[0];
				$nazwisko = $row2[0];
				$pojazd = $row3[0];
				
				if (isset($_POST['wyslij'])){
					pg_query("UPDATE kurier SET dostepny='false' WHERE id_kur = $id");
					pg_query("UPDATE pojazd SET dostepny='true' WHERE id_poj = $pojazdID");
					header("Location: ../logout.php");
					exit();
				}
			}
		?>
		<p>Zalogowano jako: <strong><?php echo $imie . " " . $nazwisko ?></strong></p>
		<p>Twój pojazd dziś: <strong><?php echo $pojazd ?></strong></p>
		<?php
			$tab_query = pg_query("SELECT z.id_zam, k.imie, k.nazwisko, k.telefon  FROM zamowienie z INNER JOIN klient k ON k.id_kl = z.id_kl WHERE stan = 1");
			if (pg_num_rows($tab_query) > 0) {
				echo "<p>Zamówienia czekające na odbiór: </p>";
				echo "<table><tr class='nagl'><th>ID</th><th>Imię</th><th>Nazwisko</th><th>Telefon klienta</th></tr>";
				while($row = pg_fetch_assoc($tab_query)) {
					$id = $row["id_zam"];
					echo "<tr class='element' onclick='Przekieruj($id)'>";		
					echo "<td>" . $row["id_zam"]. "</td><td>" . $row["imie"]. "</td><td>" . $row["nazwisko"]."</td><td>" . $row["telefon"] . "</td>";
					echo "</tr>";
				}
				echo "</table>";
			} 
			else {
				echo "<strong>Brak zamówień.</strong>";
			}
		?>
		<form enctype="multipart/form-data" name="form" id="form" method="post"
			action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>">  
			<div class="form-group row">
			<div class="col-sm-4">
				</div>
				<div class="col-sm-7">
					<input type="submit" id="wyslij" name="wyslij" class="btn btn-primary" value="Zakończ dzień pracy i wyloguj się">
				</div>
			</div>	
		</form>
	</div>
</body>
</html>