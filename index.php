<!DOCTYPE html>
<html lang="pl">
<head>
  <title>Strona główna</title>
  <meta HTTP-EQUIV="Content-Type" content="text/html"; charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="style/form_style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
</head>

<body>
	<div class="container">
	<br><br><br>
		<h1 class="display-4">Strona główna</h1>
		 <div class="dropdown">
			<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Kim jesteś?</button>
			<ul class="dropdown-menu">
				<li><a class="dropdown-item" href="login/klient_choose.php">Klientem</a></li>
				<li><a class="dropdown-item" href="login/kucharz.php">Kucharzem</a></li>
				<li><a class="dropdown-item" href="login/kurier.php">Kurierem</a></li>
				<li><a class="dropdown-item" href="login/admin.php">Administratorem</a></li>
			</ul>
		</div> 
	</div>
</body>
</html>