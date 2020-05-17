<?php session_start();?>

<html lang="pl">
<head>
  <title>Strona logowania - admin</title>
  <meta HTTP-EQUIV="Content-Type" content="text/html"; charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="../style/form_style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
</head>

<body>

<?php
	ini_set('display_errors', 'Off');
	ini_set('log_errors', 'On');
	if(isset($_SESSION['login'])){
		if($_SESSION['login'] === 1 || $_SESSION['admin'] === 1){
			header("Location: ../main/admin.php");
			exit();
		}	
	}
	$logTest = -1;
	$logTest = $_SESSION["logTest"] = -1;
	
	$_SESSION['adminID'] = $login = $pass = "";
	
	$conn = pg_connect("host=(insert hostname here) dbname=(insert dbname here) user=(insert username here) password=(insert password here)");
	$stat = pg_connection_status($conn);
	if($stat === PGSQL_CONNECTION_OK){
	if (isset($_POST['submit'])){
		$login = test_input($_POST["login"]);
		$pass = test_input($_POST["pass"]);
		pg_query("SET search_path TO projekt");
		$result = pg_query("SELECT COUNT(*) FROM admin WHERE nazwa = '$login' AND haslo = '$pass'");
		$row = pg_fetch_row($result);
		echo $row[0];
		if($row[0] == 1) {
			$result2 = pg_query("SELECT id_adm FROM admin WHERE nazwa = '$login' AND haslo = '$pass'");
			$row2 = pg_fetch_row($result2);
			$_SESSION['adminID'] = $row2[0];
			$_SESSION['login'] = 1;
			$_SESSION['admin'] = 1;
			$logTest = 1;
		}
		else if ($login != "" or $pass != ""){
		$logTest = 0;
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
<br><br><br><br><br><br><br><br><br><br><br><br>
	<h1 class="display-4">Wpisz dane logowania.</h1>
	
<div class="row">
<div class ="col-sm-2"></div>
<div class="col-sm-9">
<form name="log" id="log" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>">  
		<div class="form-group row">
			<label for="login" class="col-sm-2 col-form-label"><b>Login:</b></label>
			<div class="col-sm-7">	
				<input type="text" class="form-control" id="login" name="login">	
			</div>
		</div>		
		<div class="form-group row">
			<label for="pass" class="col-sm-2 col-form-label"><b>Hasło:</b></label>
			<div class="col-sm-7">	
				<input type="password" class="form-control" id="pass" name="pass">	
			</div>
		</div>	
		<div class="form-group row">
		<div class="col-sm-2">
			</div>
			<div class="col-sm-7">
				<input type="submit" id="submit" name="submit" class="btn btn-primary" value="Zaloguj się">
				<a class="btn btn-secondary" href="../index.php">Powrót</a>
			</div>
		</div>		
</form>             
<?php
	
	if ($logTest === 1){
		$_SESSION['login'] = 1;
		$_SESSION['admin'] = 1;
		header("Location: ../main/admin.php");
		exit();
	}
	else if ($logTest === 0) echo '<p class="error">Nieprawidłowe dane logowania.</p>';
?>
</div>
</div>

</div>
</body>
</html>