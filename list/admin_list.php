<?php
	session_start();
	unset($_SESSION['id']);
	if ($_SESSION['login'] != 1 || $_SESSION['admin'] != 1){
		header("Location: ../login/admin.php");
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
  <script src="../js/list_zam_admin.js" defer></script>
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
		$view = pg_query("CREATE VIEW lista_ad AS SELECT z.id_zam, z.adres, z.cena, z.stan, z.data_real, kl.imie ||' '|| kl.nazwisko AS klient, kur.imie ||' '|| kur.nazwisko AS kurier, kuch.imie ||' '|| kuch.nazwisko AS kucharz FROM zamowienie z LEFT JOIN klient kl ON kl.id_kl = z.id_kl LEFT JOIN kurier kur ON z.id_kur = kur.id_kur LEFT JOIN kucharz kuch ON z.id_kuch = kuch.id_kuch GROUP BY z.stan, z.id_zam, kur.imie, kur.nazwisko, kuch.imie, kuch.nazwisko, kl.imie, kl.nazwisko ORDER BY stan");
		$result = pg_query("SELECT * FROM lista_ad");
		if (pg_num_rows($result) > 0) {
			echo "<table><tr class='nagl'><th>Stan</th><th>Adres</th><th>Cena</th><th>Data zrealizowania zamówienia</th><th>Klient</th><th>Kurier</th><th>Kucharz</th></tr>";
			while($row = pg_fetch_assoc($result)) {
				$idZ = $row["id_zam"];
				switch($row['stan']){
					case 0: $stan = 'przyjęte'; break;
					case 1: $stan = 'przygotowane'; break;
 					case 2: $stan = 'wysłane'; break;
					case 3: $stan = 'zrealizowane'; break;
				}
				echo "<tr class='element' onclick='Przekieruj($idZ)'>";		
				echo "<td>" . $stan . "</td><td>" . $row["adres"]. "</td><td>" . $row["cena"]. " zł</td><td>" . $row["data_real"]."</td><td>" . $row["klient"] . "</td><td>" . $row["kurier"] . "</td><td>" . $row["kucharz"]."</td>";
				echo "</tr>";
			}
			echo "</table>";
			pg_query("DROP VIEW lista_ad");
		} 
		else {
			echo "<strong>Historia zamówień jest pusta.</strong>";
		}
		
	}
?>
<hr/>	
<center><a href="../main/admin.php" class="btn btn-warning">Wróć</a></center>

</div>
</body>
</html>