<?php
	session_start();
	unset($_SESSION['id']);
	if ($_SESSION['login'] != 1 || $_SESSION['klient'] != 1){
		header("Location: ../login/klient.php");
		exit();
	}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <title>Panel klienta</title>
  <meta HTTP-EQUIV="Content-Type" content="text/html"; charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="../style/form_style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
  <script src="../js/klient_update.js"></script>
</head>

<body>
	<div class="container">
	<br><br><br>
		<h1 class="display-4">Panel klienta</h1>
		<?php
			$id = $_SESSION['klientID'];
			$conn = pg_connect("host=(insert hostname here) dbname=(insert dbname here) user=(insert username here) password=(insert password here)");
			$stat = pg_connection_status($conn);
			if($stat === PGSQL_CONNECTION_OK){
				pg_query("SET search_path TO projekt");
				$result = pg_query("SELECT imie FROM klient WHERE id_kl = '$id'");
				$result2 = pg_query("SELECT nazwisko FROM klient WHERE id_kl = '$id'");
				$zamowienia_query = pg_query("SELECT COUNT(*) FROM zamowienie WHERE id_kl='$id' AND stan <> 3");
				$zamowienia_row = pg_fetch_row($zamowienia_query);
				$zamowienia = $zamowienia_row[0];
				$row = pg_fetch_row($result);
				$row2 = pg_fetch_row($result2);
				$imie = $row[0];
				$nazwisko = $row2[0];
			}
		?>
		<p>Zalogowano jako: <strong><?php echo $imie . " " . $nazwisko ?></strong></p>
		
		<?php 
			if($zamowienia == 0){ 
				echo '<a class="btn btn-primary" href="../zamowienie/nowe_zamowienie.php?id=' . $id . '">Złóż zamówienie</a><hr/>';
				echo '<a class="btn btn-info" href="../list/klient_zam.php?id=' . $id . '"> Historia zamówień </a><hr/>';
			}
			else{
				$stan_query = pg_query("SELECT stan FROM zamowienie WHERE id_kl='$id' ORDER BY stan LIMIT 1");
				$stan_row = pg_fetch_row($stan_query);
				$stan = $stan_row[0];
				
				if ($stan == 0) echo "<p>Twoje zamówienie jest przygotowywane przez naszą restaurację.</p><hr/>";
				if ($stan == 1){
					$kuch_query = pg_query("SELECT imie, nazwisko, telefon FROM kucharz INNER JOIN zamowienie on zamowienie.id_kuch = kucharz.id_kuch WHERE id_kl='$id' AND zamowienie.stan = 1");
					$kuch_row = pg_fetch_assoc($kuch_query);
					$kuch_imie = $kuch_row['imie'];
					$kuch_naz = $kuch_row['nazwisko'];
					$kuch_tel = $kuch_row['telefon'];
					echo "<p>Twoje zamówienie zostało ugotowane i czeka na wysyłkę. </p>";
					echo "<p>Dane kucharza: " . $kuch_imie . " " . $kuch_naz . ", tel: ". $kuch_tel . "</p><hr/>";
				}
				if ($stan == 2){
					$kuch_query = pg_query("SELECT imie, nazwisko, telefon FROM kucharz INNER JOIN zamowienie on zamowienie.id_kuch = kucharz.id_kuch WHERE id_kl='$id' AND zamowienie.stan = 2");
					$kur_query = pg_query("SELECT imie, nazwisko, telefon FROM kurier INNER JOIN zamowienie on zamowienie.id_kur = kurier.id_kur WHERE id_kl='$id' AND zamowienie.stan = 2");
					$kuch_row = pg_fetch_assoc($kuch_query);
					$kur_row = pg_fetch_assoc($kur_query);
					$kuch_imie = $kuch_row['imie'];
					$kur_imie = $kur_row['imie'];
					$kuch_naz = $kuch_row['nazwisko'];
					$kur_naz = $kur_row['nazwisko'];
					$kuch_tel = $kuch_row['telefon'];
					$kur_tel = $kur_row['telefon'];
					echo "<p>Twoje zamówienie właśnie do ciebie jedzie. </p>";
					echo "<p>Dane kucharza: " . $kuch_imie . " " . $kuch_naz . ", tel: ". $kuch_tel . "</p>";
					echo "<p>Dane kuriera: " . $kur_imie . " " . $kur_naz . ", tel: ". $kur_tel . "</p><hr/>";
				}
			}
		?>
		<button class="btn btn-warning" onclick="Przekieruj(<?php echo $id; ?>)">Zmień dane lub usuń konto</button>
		<a class="btn btn-secondary" href="../logout.php">Wyloguj się</a>
	</div>
</body>
</html>