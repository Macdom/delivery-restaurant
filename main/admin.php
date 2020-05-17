<?php
	session_start();
	if ($_SESSION['login'] != 1 || $_SESSION['admin'] != 1){
		header("Location: ../login/admin.php");
		exit();
	}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <title>Panel administratora</title>
  <meta HTTP-EQUIV="Content-Type" content="text/html"; charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="../style/form_style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
</head>

<body>
	<div class="container">
	<br><br><br>
		<h1 class="display-4">Panel administratora</h1>
		<?php
			$id = $_SESSION['adminID'];
			$conn = pg_connect("host=(insert hostname here) dbname=(insert dbname here) user=(insert username here) password=(insert password here)");
			$stat = pg_connection_status($conn);
			if($stat === PGSQL_CONNECTION_OK){
				pg_query("SET search_path TO projekt");
				$result = pg_query("SELECT nazwa FROM admin WHERE id_adm = '$id'");
				$row = pg_fetch_row($result);
				$nazwa = $row[0];
			}
		?>
		 <p>Zalogowano jako: <strong><?php echo $nazwa ?></strong></p>
		 <div class="dropdown">
			<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Dodaj...</button>
			<ul class="dropdown-menu">
				<li><a class="dropdown-item" href="../forms/pojazd.php">pojazd</a></li>
				<li><a class="dropdown-item" href="../forms/kurier.php">kuriera</a></li>
				<li><a class="dropdown-item" href="../forms/kucharz.php">kucharza</a></li>
				<li><a class="dropdown-item" href="../forms/produkt.php">produkt</a></li>
				<li><a class="dropdown-item" href="../forms/menu.php">element menu</a></li>
			</ul>
		</div><br/>
		
		<div class="dropdown">
			<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Wyświetl listę/zmień/usuń...</button>
			<ul class="dropdown-menu">
				<li><a class="dropdown-item" href="../list/pojazd.php">pojazdy</a></li>
				<li><a class="dropdown-item" href="../list/kurier.php">kurierzy</a></li>
				<li><a class="dropdown-item" href="../list/kucharz.php">kucharze</a></li>
				<li><a class="dropdown-item" href="../list/produkt.php">produkty</a></li>
				<li><a class="dropdown-item" href="../list/menu.php">element menu</a></li>
			</ul>
		</div><br/><hr/>
		<a class="btn btn-info" href="../list/admin_list.php"> Historia zamówień</a><hr/>
		<a class="btn btn-secondary" href="../logout.php">Wyloguj się</a>
	</div>
</body>
</html>