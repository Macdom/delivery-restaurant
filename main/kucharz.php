<?php
	session_start();
	if ($_SESSION['login'] != 1 || $_SESSION['kucharz'] != 1){
		header("Location: ../login/kucharz.php");
		exit();
	}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <title>Panel kucharza</title>
  <meta HTTP-EQUIV="Content-Type" content="text/html"; charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="../style/form_style.css">
  <link rel="stylesheet" href="../style/list_style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
  <script src="../js/gotowanie.js"></script>
</head>

<body>
	<div class="container">
	<br><br><br>
		<h1 class="display-4">Panel kucharza</h1>
		<?php
			$id = $_SESSION['kucharzID'];
			$conn = pg_connect("host=(insert hostname here) dbname=(insert dbname here) user=(insert username here) password=(insert password here)");
			$stat = pg_connection_status($conn);
			if($stat === PGSQL_CONNECTION_OK){
				pg_query("SET search_path TO projekt");
				$result = pg_query("SELECT imie FROM kucharz WHERE id_kuch = '$id'");
				$result2 = pg_query("SELECT nazwisko FROM kucharz WHERE id_kuch = '$id'");
				$row = pg_fetch_row($result);
				$row2 = pg_fetch_row($result2);
				$imie = $row[0];
				$nazwisko = $row2[0];
			}
		?>
		<p>Zalogowano jako: <strong><?php echo $imie . " " . $nazwisko ?></strong></p>
		<?php
			$tab_query = pg_query("SELECT z.id_zam, k.imie, k.nazwisko, k.telefon  FROM zamowienie z INNER JOIN klient k ON k.id_kl = z.id_kl WHERE stan = 0");
			if (pg_num_rows($tab_query) > 0) {
				echo "<p>Zamówienia czekające na przygotowanie: </p>";
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
		<hr/>
		<a class="btn btn-secondary" href="../logout.php">Wyloguj się</a>
	</div>
</body>
</html>