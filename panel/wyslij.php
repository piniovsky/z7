<!doctype html>

<html lang="pl">
<head>
  <meta charset="utf-8">

  <title>Olszewski Michał</title>
</head>

<body>
	<br /><br />
	<form action="odbierz.php" method="POST" ENCTYPE="multipart/form-data">
		<input type="hidden" name="dir" value="<?php echo $_GET['dir']; ?>" />
		<input type="file" name="plik"/>
		<input type="submit" value="Wyślij plik"/>
	</form>
</body>
</html>