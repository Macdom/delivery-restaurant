<?php
	session_start();
	if ($_SESSION['login'] != 1 || $_SESSION['klient'] != 1){
		header("Location: ../login/klient.php");
		exit();
	}
	
	else{
		if (isset($_GET['id'])) $_SESSION['id'] = $_GET['id'];
	}
?>


<!DOCTYPE html>
<html lang="pl">
<head>
  <title>Nowe zamówienie</title>
  <meta HTTP-EQUIV="Content-Type" content="text/html"; charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="../style/form_style.css">
  <script src="../js/zamowienie.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
</head>

<body>

<?php
	$adres = $glowne = $dodatek1 = $dodatek2 = $adresErr = "";
	$conn = pg_connect("host=(insert hostname here) dbname=(insert dbname here) user=(insert username here) password=(insert password here)");
	$stat = pg_connection_status($conn);
	$valid = true;
	$idK = $_SESSION['id'];

	if($stat === PGSQL_CONNECTION_OK){
	
	pg_query("SET search_path TO projekt");
	$glowne_query = pg_query("SELECT id_menu, nazwa,cena FROM menu WHERE typ = 'glowne'");
	$dodatek1_query = pg_query("SELECT id_menu, nazwa,cena FROM menu WHERE typ = 'dodatek'");
	$dodatek2_query = pg_query("SELECT id_menu, nazwa,cena FROM menu WHERE typ = 'dodatek'");
	$zupa_query = pg_query("SELECT id_menu, nazwa,cena FROM menu WHERE typ = 'zupa'");
	$napoj_query = pg_query("SELECT id_menu, nazwa,cena FROM menu WHERE typ = 'napoj'");
	
	$zam_query = pg_query("SELECT COALESCE(MAX(id_zam),0) FROM zamowienie");
	$zam_row = pg_fetch_row($zam_query);
	$zam = $zam_row[0]+1;
	echo $zam;
	$_SESSION['zam'] = $zam;
	if (isset($_POST['wyslij'])){
		if(empty($_POST["adres"])){
			$adresErr = "Nie wpisano pola Adres.\n";
			$valid = false;
		}
		else{
			$adres = test_input($_POST["adres"]);
		}			
	
		$glowne = $_POST['glowne'];
		$dodatek1 = $_POST['dodatek1'];
		$dodatek2 = $_POST['dodatek2'];
		$zupa = $_POST['zupa'];
		$napoj = $_POST['napoj'];
		if($glowne == 'brak' && $zupa == 'brak' && $dodatek1 == 'brak' && $dodatek2 == 'brak' && $napoj == 'brak'){
			$elementyErr = "Nie wybrano żadnego elementu z menu.";
			$valid = false;
		}
		
		if ($valid = true){
			$_SESSION['potw'] = 1;
			pg_query("INSERT INTO zamowienie(id_kl, adres, stan) VALUES('$idK', '$adres', 0)");
			if ($glowne != 'brak') pg_query("INSERT INTO posilek(id_zam, id_menu) VALUES ($zam, $glowne)");
			if ($zupa != 'brak') pg_query("INSERT INTO posilek(id_zam, id_menu) VALUES ($zam, $zupa)");
			if ($dodatek1 != 'brak') pg_query("INSERT INTO posilek(id_zam, id_menu) VALUES ($zam, $dodatek1)");
			if ($dodatek2 != 'brak') pg_query("INSERT INTO posilek(id_zam, id_menu) VALUES ($zam, $dodatek2)");
			if ($napoj != 'brak') pg_query("INSERT INTO posilek(id_zam, id_menu) VALUES ($zam, $napoj)");
			header("Location: ../zamowienie/potwierdzenie.php?zam=" . $zam);
			exit(); 
		}
	}
	}
	
	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
?> 

	<div class="container">
	<br><br><br>
		<h1 class="display-4">Nowe zamówienie</h1>
		
		<div class="row">
		<div class="col-sm-9">
			<div class="form-group row">
			<label for="data" class="col-sm-4 col-form-label"><b></b></label>
			</div>

		<form enctype="multipart/form-data" name="form" id="form" method="post"
			action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>"  onsubmit = "return Walidacja()">  
			<div class="form-group row">
				<label for="imie" class="col-sm-4 col-form-label"><b>Adres:</b></label>
				<div class="col-sm-7">	
					<input type="text" class="form-control" id="adres" name="adres" value="<?php echo $adres;?>" placeholder="Adres...">	
				</div>
			</div>
			<div class="form-group row">
				<label for="zupa" class="col-sm-4 col-form-label"><b>Zupa:</b></label>
				<div class="col-sm-7">	
					<select class="form-control" id="zupa" name="zupa">
						<option value="brak">[brak]</option>
						<?php
							while($row = pg_fetch_assoc($zupa_query)) {
								$idZ = $row["id_menu"];
								echo '<option value="' . $idZ . '">' . $row['nazwa'] . ' (' . $row['cena'] . 'zł)'; 
							}
						?>
					</select>
				</div>
			</div>
			<div class="form-group row">
				<label for="glowne" class="col-sm-4 col-form-label"><b>Danie główne:</b></label>
				<div class="col-sm-7">	
					<select class="form-control" id="glowne" name="glowne">
						<option value="brak">[brak]</option>
						<?php
							while($row = pg_fetch_assoc($glowne_query)) {
								$idG = $row["id_menu"];
								echo '<option value="' . $idG . '">' . $row['nazwa'] . ' (' . $row['cena'] . 'zł)'; 
							}
						?>
					</select>
				</div>
			</div>
			<div class="form-group row">
				<label for="dodatek1" class="col-sm-4 col-form-label"><b>Dodatek 1:</b></label>
				<div class="col-sm-7">	
					<select class="form-control" id="dodatek1" name="dodatek1">
						<option value="brak">[brak]</option>
						<?php
							while($row = pg_fetch_assoc($dodatek1_query)) {
								$idD1 = $row["id_menu"];
								echo '<option value="' . $idD1 . '">' . $row['nazwa'] . ' (' . $row['cena'] . 'zł)'; 
							}
						?>
					</select>
				</div>
			</div>
			<div class="form-group row">
				<label for="dodatek2" class="col-sm-4 col-form-label"><b>Dodatek 2:</b></label>
				<div class="col-sm-7">	
					<select class="form-control" id="dodatek2" name="dodatek2">
						<option value="brak">[brak]</option>
						<?php
							while($row = pg_fetch_assoc($dodatek2_query)) {
								$idD2 = $row["id_menu"];
								echo '<option value="' . $idD2 . '">' . $row['nazwa'] . ' (' . $row['cena'] . 'zł)'; 
							}
						?>
					</select>
				</div>
			</div>
			<div class="form-group row">
				<label for="napoj" class="col-sm-4 col-form-label"><b>Napój:</b></label>
				<div class="col-sm-7">	
					<select class="form-control" id="napoj" name="napoj">
						<option value="brak">[brak]</option>
						<?php
							while($row = pg_fetch_assoc($napoj_query)) {
								$idN = $row["id_menu"];
								echo '<option value="' . $idN . '">' . $row['nazwa'] . ' (' . $row['cena'] . 'zł)'; 
							}
						?>
					</select>
				</div>
			</div>				
			
			<div class="form-group row">
			<div class="col-sm-4">
				</div>
				<div class="col-sm-7">
					<input type="submit" id="wyslij" name="wyslij" class="btn btn-primary" value="Złóż zamówienie">
					<a class="btn btn-secondary" href="../main/klient.php">Powrót</a>
				</div>
			</div>	
		</form>
			
		<div class="row">
				<div class="col-sm-4">
				</div>
				<div class="col-sm-7">		
			</div></div>
			<br>
		</div>
			<div class="col-sm-3">	
			<br><br>
			<p><span class="error"><?php echo $adresErr;?></span></p>
			<p><span class="error"><?php echo $elementyErr;?></span></p>
			</div>
		</div>
	</div>
</body>
</html>