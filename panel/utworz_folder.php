<?php
	session_start();
	$username = $_SESSION['username'];
	$dir = $_POST['dir'];
	
	$userPath = '../dane/' . $username . '/';
	
	if($dir) {
		$userPath .= $dir . '/';
		
		if(is_dir($userPath)) {
			echo "Folder już istnieje";
		} else {
			mkdir($userPath);
			echo "Utworzono folder";
		}
	}
?>

<!doctype html>

<html lang="pl">
<head>
  <meta charset="utf-8">

  <title>Olszewski Michał</title>
</head>

<body>
	<br /><br />
	<form method="POST">
		<input type="text" name="dir" required="required" />
		<input type="submit" value="Utwórz folder"/>
	</form>
</body>
</html>