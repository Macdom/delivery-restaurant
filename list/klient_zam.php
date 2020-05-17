<?php
	session_start();
	unset($_SESSION['id']);
	if ($_SESSION['login'] != 1 || $_SESSION['klient'] != 1){
		header("Location: ../login/klient.php");
		exit();
	}
	else{
		if (isset($_GET['id'])) $_SESSION['id'] = $_GET['id'];
	}
?>
<html lang="pl">
<!DOCTYPE html>
<html lang="pl">
<head>
  <title>Historia zamówień</title>
  <meta HTTP-EQUIV="Content-Type" content="text/html"; charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="../style/list_style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
  <script src="../js/list_zam_klient.js" defer></script>
</head>

<body>
<br><br><br>
<center><h4>Kliknij w rząd, aby wyświetlić więcej szczegółów.</h4></center>
<div class="container">
<center>
<?php
	$conn = pg_connect("host=localhost dbname=u6domagalski user=u6domagalski password=6domagalski");
	$stat = pg_connection_status($conn);
	if($stat === PGSQL_CONNECTION_OK){
		pg_query("SET search_path TO projekt");
		$id = $_SESSION['id'];
		$view = pg_query("CREATE VIEW lista_kl AS SELECT z.id_zam, z.adres, z.cena, z.data_real, kur.imie ||' '|| kur.nazwisko AS kurier, kuch.imie ||' '|| kuch.nazwisko AS kucharz FROM zamowienie z INNER JOIN kurier kur ON z.id_kur = kur.id_kur INNER JOIN kucharz kuch ON z.id_kuch = kuch.id_kuch WHERE z.id_kl = $id");
		$result = pg_query("SELECT * FROM lista_kl");
		if (pg_num_rows($result) > 0) {
			echo "<table><tr class='nagl'><th>Adres</th><th>Cena</th><th>Data otrzymania zamówienia</th><th>Kurier</th><th>Kucharz</th></tr>";
			while($row = pg_fetch_assoc($result)) {
				$idZ = $row["id_zam"];
				echo "<tr class='element' onclick='Przekieruj($idZ)'>";		
				echo "<td>" . $row["adres"]. "</td><td>" . $row["cena"]. " zł</td><td>" . $row["data_real"]."</td><td>" .$row["kurier"] . "</td><td>" . $row["kucharz"]."</td>";
				echo "</tr>";
			}
			echo "</table>";
			pg_query("DROP VIEW lista_kl");
		} 
		else {
			echo "<strong>Historia zamówień jest pusta.</strong>";
		}
	}
	
?>
<hr/>	
<center><a href="../main/klient.php" class="btn btn-warning">Wróć</a></center>

</div>
</body>
</html>