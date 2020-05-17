<!DOCTYPE html>
<html lang="pl">
<head>
  <title>Klient</title>
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
		if($_SESSION['login'] === 1 || $_SESSION['klient'] === 1){
			header("Location: ../main/klient.php");
			exit();
		}	
	}
?>	
	<div class="container">
	<br><br><br>
		<h1 class="display-4">Czy masz już konto w naszej restauracji?</h1>
		<p>Jeśli tak, to <a class="btn btn-primary" href="klient.php">zaloguj się</a></p>
		<p>Jeśli nie, to <a class="btn btn-warning" href="../forms/klient.php">załóż konto</a></p>
	</div>
</body>
</html>