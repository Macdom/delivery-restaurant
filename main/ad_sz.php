<?php
	session_start();
	unset($_SESSION['id']);
	if ($_SESSION['login'] != 1 || $_SESSION['admin'] != 1){
		header("Location: ../login/admin.php");
		exit();
	}
	else{
		if (isset($_GET['idZ'])) $_SESSION['idZ'] = $_GET['idZ'];
	}
?>
<html lang="pl">
<!DOCTYPE html>
<html lang="pl">
<head>
  <title>Zamówienie</title>
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
<div class="container">
<center>
<?php
	$conn = pg_connect("host=(insert hostname here) dbname=(insert dbname here) user=(insert username here) password=(insert password here)");
	$stat = pg_connection_status($conn);

	$idZ = $_SESSION['idZ'];
	
	if($stat === PGSQL_CONNECTION_OK){
		pg_query("SET search_path TO projekt");
		$result = pg_query("SELECT z.id_kl, z.adres, z.cena, z.data_zam, z.data_przyg, z.data_kur, z.data_real, kl.imie ||' '|| kl.nazwisko AS klient, kur.imie ||' '|| kur.nazwisko AS kurier, kuch.imie ||' '|| kuch.nazwisko AS kucharz FROM zamowienie z LEFT JOIN klient kl ON kl.id_kl = z.id_kl LEFT JOIN kurier kur ON z.id_kur = kur.id_kur LEFT JOIN kucharz kuch ON z.id_kuch = kuch.id_kuch WHERE z.id_zam = $idZ");
		$row = pg_fetch_assoc($result);
		
		$id = $row['id_kl'];
		$adres = $row['adres'];
		$cena = $row['cena'];
		$data_zam = $row['data_zam'];
		$data_przyg = $row['data_przyg'];
		$data_kur = $row['data_kur'];
		$data_real = $row['data_real'];
		$kurier = $row['kurier'];
		$klient = $row['klient'];
		$kucharz = $row['kucharz'];
		
		echo "<p>Zamówione jedzenie</p>";
		echo "<table><tr class='nagl'><th>Nazwa</th></tr>";
		$result2 = pg_query("SELECT nazwa FROM menu INNER JOIN posilek ON menu.id_menu = posilek.id_menu WHERE posilek.id_zam = $idZ");
		while($row2 = pg_fetch_assoc($result2)) {
			echo "<tr>";
			echo "<td>" . $row2["nazwa"]."</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
?>
<hr/>
<ul>
	<li>Data i godzina złożenia zamówienia: <?php echo $data_zam; ?></li>
	<li>Data i godzina przygotowania posiłków: <?php echo $data_przyg; ?></li>
	<li>Data wysłania zamówienia: <?php echo $data_kur; ?></li>
	<li>Data zrealizowania zamówienia: <?php echo $data_real; ?></li>
	<li>Jedzenie zamówił: <?php echo $klient; ?></li>
	<li>Jedzenie przygotował: <?php echo $kucharz; ?></li>
	<li>Jedzenie przywiózł: <?php echo $kurier; ?></li>
	<li>Całkowita cena zamówienia: <?php echo $cena; ?></li>
	<li>Adres zamówienia: <?php echo $adres; ?></li>
</ul>
	
<center><a href="../list/admin_list.php" class="btn btn-warning">Wróć</a></center>

</div>
</body>
</html>