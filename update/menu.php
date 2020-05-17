<?php
	session_start();
	unset($_SESSION['liczbaProd']);
	if ($_SESSION['login'] != 1 || $_SESSION['admin'] != 1){
		header("Location: ../login/admin.php");
		exit();
	}
	
	else{
		if (isset($_GET['id'])) $_SESSION['id'] = $_GET['id'];
	}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <title>Modyfikacja menu</title>
  <meta HTTP-EQUIV="Content-Type" content="text/html"; charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="../style/form_style.css">
  <link rel="stylesheet" href="../style/list_style.css">
  <script src="../js/produkt.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
</head>

<body>

<?php
	$stara_nazwa = $stary_typ = $nazwa = $typ = $nazwaErr = "";
	$conn = pg_connect("host=(insert hostname here) dbname=(insert dbname here) user=(insert username here) password=(insert password here)");
	$stat = pg_connection_status($conn);
	$valid = true; 
	
	$id = $_SESSION['id'];
	echo $id;
	
	if($stat === PGSQL_CONNECTION_OK){
	pg_query("SET search_path TO projekt");
	$menu_query = pg_query("SELECT nazwa, typ FROM menu WHERE id_menu = $id");
	$menu_row = pg_fetch_assoc($menu_query);
	$stara_nazwa = $menu_row['nazwa'];
	$stary_typ = $menu_row['typ'];
	
	$liczba_query = pg_query("SELECT COUNT(*) from prod_menu WHERE id_menu = $id");
	$liczba_row = pg_fetch_row($liczba_query);
	$liczba = $liczba_row[0];
	
	if (isset($_POST['zmien'])){
		if(empty($_POST["nazwa"])){
			$modelErr = "Nie wpisano pola Nazwa.";
			$valid = false;
		}
		else{
			$nazwa = test_input($_POST["nazwa"]);
		}			
	
		$typ = $_POST['typ'];
		
		if ($valid = true){
			pg_query("UPDATE menu SET nazwa = '$nazwa', typ = '$typ' WHERE id_menu = $id");
			header("Location: ../list/menu.php");
			exit();
		}
	}
	
		if (isset($_POST['skladniki'])){
		if(empty($_POST["nazwa"])){
			$modelErr = "Nie wpisano pola Nazwa.";
			$valid = false;
		}
		else{
			$nazwa = test_input($_POST["nazwa"]);
		}			
	
		$typ = $_POST['typ'];
		
		if ($valid = true){
			$_SESSION['skladniki'] = 1;
			pg_query("UPDATE menu SET nazwa = '$nazwa', typ = '$typ' WHERE id_menu = $id");
			
			$typ = $_POST["typ"];
			$liczba = $_POST["liczba"];
			$_SESSION['liczbaProd'] = $liczba;
			
			header("Location: menu_skladniki.php?id=" . $id);
			exit();
		}
	}
	
	if (isset($_POST['usun'])){
		pg_query("DELETE FROM posilek WHERE id_menu = $id");
		pg_query("DELETE FROM prod_menu WHERE id_menu = $id");
		pg_query("DELETE FROM menu WHERE id_menu = $id");
		header("Location: ../list/menu.php");
		exit();
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
		<h1 class="display-4">Modyfikacja menu</h1>
		
		<div class="row">
		<div class="col-sm-9">
			<div class="form-group row">
			<label for="data" class="col-sm-4 col-form-label"><b></b></label>
			</div>

		<form enctype="multipart/form-data" name="form" id="form" method="post"
			action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>"  onsubmit = "return Walidacja()">  
						<div class="form-group row">
				<label for="nazwa" class="col-sm-4 col-form-label"><b>Nazwa potrawy:</b></label>
				<div class="col-sm-7">	
					<input type="text" class="form-control" id="nazwa" name="nazwa" value="<?php echo $stara_nazwa;?>" placeholder="Nazwa...">	
				</div>
			</div>				
			<div class="form-group row">
				<label for="typ" class="col-sm-4 col-form-label"><b>Typ:</b></label>
				<div class="col-sm-7">	
					<select class="form-control" id="typ" name="typ">
						<option value="glowne" <?php if($stary_typ == 'glowne') echo 'selected'; ?>>Danie główne</option>
						<option value="zupa" <?php if($stary_typ == 'zupa') echo 'selected'; ?>>Zupa</option>
						<option value="dodatek" <?php if($stary_typ == 'dodatek') echo 'selected'; ?>>Dodatek</option>
						<option value="napoj" <?php if($stary_typ == 'napoj') echo 'selected'; ?>>Napój</option>
					</select>
				</div>
			</div>
			<div class="form-group row">
				<label for="liczba" class="col-sm-4 col-form-label"><b>Liczba składników: (obecnie <?php echo $liczba; ?>). Wybierz, jeśli chcesz zmienić składniki lub cenę.</b></label>
				<div class="col-sm-7">	
					<select class="form-control" id="liczba" name="liczba">
						<option value="1" <?php if($liczba == 1) echo 'selected';?>>1</option>
						<option value="2" <?php if($liczba == 2) echo 'selected';?>>2</option>
						<option value="3" <?php if($liczba == 3) echo 'selected';?>>3</option>
						<option value="4" <?php if($liczba == 4) echo 'selected';?>>4</option>
						<option value="5" <?php if($liczba == 5) echo 'selected';?>>5</option>
						<option value="6" <?php if($liczba == 6) echo 'selected';?>>6</option>
						<option value="7" <?php if($liczba == 7) echo 'selected';?>>7</option>
						<option value="8" <?php if($liczba == 8) echo 'selected';?>>8</option>
					</select>
				</div>
			</div>	
			</div>		
			
			<div class="form-group row">
			<div class="col-sm-4">
				</div>
				<div class="col-sm-7">
				<?php
					$result = pg_query("SELECT nazwa FROM produkt INNER JOIN prod_menu ON produkt.id_prod = prod_menu.id_prod WHERE id_menu = $id;");
							if (pg_num_rows($result) > 0) {
								echo "<table><tr class='nagl'><th>Obecne składniki:</th></tr>";
								while($row = pg_fetch_assoc($result)) {
									echo "<tr>";		
									echo "<td>" . $row["nazwa"]. "</td>";
									echo "</tr>";
								}
								echo "</table>";

							} 
				?>			
				</div>
			</div>
			
			<div class="form-group row">
			<div class="col-sm-4">
				</div>
				<div class="col-sm-7">
					<input type="submit" id="zmien" name="zmien" class="btn btn-primary" value="Zapisz zmiany">
					<input type="submit" id="skladniki" name="skladniki" class="btn btn-info" value="Zmień składniki">
					<input type="submit" id="usun" name="usun" class="btn btn-danger" value="Usuń element menu">
					<hr/>
					<a class="btn btn-secondary" href="../list/menu.php">Powrót</a>	
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
			<p><span class="error"><?php echo $nazwaErr;?></span></p>
			</div>
		</div>
	</div>
</body>
</html>