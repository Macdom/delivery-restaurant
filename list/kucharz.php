<?php
	session_start();
	unset($_SESSION['id']);
	if ($_SESSION['login'] != 1 || $_SESSION['admin'] != 1){
		header("Location: ../login/admin.php");
		exit();
	}
?>
<html lang="pl">
<!DOCTYPE html>
<html lang="pl">
<head>
  <title>Lista kucharzy</title>
  <meta HTTP-EQUIV="Content-Type" content="text/html"; charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="../style/list_style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
  <script src="../js/list_kucharz.js" defer></script>
</head>

<body>
<br><br><br>
<center><h4>Kliknij w rząd, aby zmodyfikować lub usunąć kucharza.</h4></center>
<div class="container">
<center>
<?php
	$conn = pg_connect("host=localhost dbname=u6domagalski user=u6domagalski password=6domagalski");
	$stat = pg_connection_status($conn);
	if($stat === PGSQL_CONNECTION_OK){
		pg_query("SET search_path TO projekt");
		$result = pg_query("SELECT * FROM kucharz ORDER BY id_kuch");
		if (pg_num_rows($result) > 0) {
			echo "<table><tr class='nagl'><th>ID</th><th>Imię</th><th>Nazwisko</th><th>Płaca</th><th>Telefon</th></tr>";
			while($row = pg_fetch_assoc($result)) {
				$id = $row["id_kuch"];
				echo "<tr class='element' onclick='Przekieruj($id)'>";		
				echo "<td>" . $row["id_kuch"]. "</td><td>" . $row["imie"]. "</td><td>" . $row["nazwisko"]."</td><td>" . $row["placa"]."</td><td>" . $row["telefon"] . "</td>";
				echo "</tr>";
			}
			echo "</table>";
		} 
		else {
			echo "<strong>Brak kucharzy.</strong>";
		}
	}
?>
<hr/>	
<center><a href="../main/admin.php" class="btn btn-warning">Wróć</a></center>

</div>
</body>
</html>