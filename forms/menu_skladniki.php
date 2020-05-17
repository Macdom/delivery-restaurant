<?php
	session_start();
	if ($_SESSION['login'] != 1 || $_SESSION['admin'] != 1){
		header("Location: ../login/admin.php");
		exit();
	}
	if ($_SESSION['skladniki'] != 1){
		header("Location: menu.php");
	}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <title>Dodaj składniki</title>
  <meta HTTP-EQUIV="Content-Type" content="text/html"; charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="../style/form_style.css">
  <script src="../js/menu_skladniki.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
</head>

<body>
<?php
	$obecny_skladnik = $cena = $cenaErr = "";
	
	$conn = pg_connect("host=(insert hostname here) dbname=(insert dbname here) user=(insert username here) password=(insert password here)");
	$stat = pg_connection_status($conn);

	if($stat === PGSQL_CONNECTION_OK){
		pg_query("SET search_path TO projekt");
		$prod_query = pg_query("SELECT id_prod, nazwa, cena FROM produkt ORDER BY id_prod");
		
		$idy = array();	
		$produkty = array();
		$ceny = array();
	
		while($prod_row = pg_fetch_assoc($prod_query)) {
			$idy[] = $prod_row['id_prod'];
			$produkty[] = $prod_row['nazwa'];
			$ceny[] = $prod_row['cena'];
		}

		$nazwa = $_SESSION['nazwaProd'];
		$typ = $_SESSION['typProd'];
		$liczba = $_SESSION['liczbaProd'];
		
		if (isset($_POST['wyslij'])){
			if(empty($_POST["cena"])){
				$cenaErr = "Nie wpisano pola Cena.";
				$valid = false;
			}
			else{
				$cena = test_input($_POST["cena"]);
				if(!preg_match("/([0-9]*[.])[0-9][0-9]/", $cena)) {
					$cenaErr = "W polu Cena można wpisać tylko liczbę całkowitą z 2 liczbami po kropce."; 
					$valid = false;
				}	
			}
			
			if($valid = true){
				pg_query("INSERT INTO menu(nazwa, cena, typ) VALUES('$nazwa', '$cena', '$typ')");
				$id_menu_query = pg_query("SELECT id_menu FROM menu WHERE nazwa = '$nazwa'");
				$id_menu_row = pg_fetch_row($id_menu_query);
				$id_menu = $id_menu_row[0];
				echo "Id menu: " . $id_menu . "\n";
				for($i = 1; $i <= $liczba; $i++){
					$obecny_skladnik = $_POST['skladnik' . $i];
					pg_query("INSERT INTO prod_menu (id_prod, id_menu) VALUES ($obecny_skladnik, $id_menu)");
				}
				unset($_SESSION['skladniki']);
				header("Location: ../main/admin.php");
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
		<h1 class="display-4">Dodaj składniki</h1>
		
		<div class="row">
		<div class="col-sm-9">
			<div class="form-group row">
			<label for="data" class="col-sm-4 col-form-label"><b></b></label>
			</div>

		<form enctype="multipart/form-data" name="form" id="form" method="post"
			action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>"  onsubmit = "return Walidacja()">  			
			<?php
				for($i = 1; $i <= $liczba; $i++){
					echo
					'<div class="form-group row">
						<label for="skladnik' . $i .'" class="col-sm-4 col-form-label"><b>Składnik ' . $i . ':</b></label>
						<div class="col-sm-7">								
							<select class="form-control" id="skladnik' . $i . '"name="skladnik' . $i .'">';
								for($j = 0; $j < count($idy); $j++){
									echo '<option value=' . $idy[$j] . '>' . $produkty[$j] . " (" . $ceny[$j] . " zł)</option>";
								}
							echo '</select>
						</div>
					</div>';				
				} 
			?>
				</div>
			</div>
			<div class="form-group row">
				<label for="cena" class="col-sm-4 col-form-label"><b>Cena potrawy (zł):</b></label>
				<div class="col-sm-7">	
					<input type="text" class="form-control" id="cena" name="cena" value="<?php echo $cena;?>" placeholder="Cena (zł)...">	
				</div>
			</div>				
			
			<div class="form-group row">
			<div class="col-sm-4">
				</div>
				<div class="col-sm-7">
					<input type="submit" id="wyslij" name="wyslij" class="btn btn-primary" value="Wyślij">
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
			<p><span class="error"><?php echo $cenaErr;?></span></p>
			</div>
		</div>
	</div>
</body>
</html>

<?php
	unset($_SESSION['nazwa']);
	unset($_SESSION['typ']);
	unset($_SESSION['liczba']);
?>