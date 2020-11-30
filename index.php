<!doctype html>

<html lang="pl">
<head>
  <meta charset="utf-8">

  <title>Olszewski Michał</title>
</head>

<?php
	if(count($_POST) > 0) {
		$user=$_POST['user']; // login z formularza
		$pass=$_POST['pass']; // hasło z formularza

		// plik z konfiguracją
		include 'konfiguracja.php';
		
		// próba połączenia
		$link = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
		
		if(!$link) { 
			echo"Error: ". mysqli_connect_errno()." ".mysqli_connect_error(); 
		} // obsługa błędu połączenia z BD
		
		mysqli_query($link, "SET NAMES 'utf8'"); // ustawienie polskich znaków
		$result = mysqli_query($link, "SELECT * FROM users WHERE username='$user'"); // pobranie z BD wiersza, w którym login=login z formularza
		$rekord = mysqli_fetch_array($result); // wiersza z BD, struktura zmiennej jak w BD

		if(!$rekord) { //Jeśli brak, to nie ma użytkownika o podanym loginie
			echo "Nieprawidłowy login lub hasło!";
		} else { // jeśli $rekord istnieje
			$id = $rekord['id'];
			$browser = $_SERVER['HTTP_USER_AGENT'];
			$ip = $_SERVER['REMOTE_ADDR'];
			
			if(password_verify($pass, $rekord['password'])) { // czy hasło zgadza się z BD
				$failsR = mysqli_query($link, "SELECT success, count(*) as suma, max(created_at) as maks FROM logs WHERE user_id = $id AND success = 0 AND `created_at` >= NOW() + INTERVAL -10 MINUTE;");
				$fails = mysqli_fetch_array($failsR);

				if($fails && $fails['suma'] >= 3) {
					echo 'Trzy błędne logowania w ciągu 10 minut! Konto zablokowane!';
				} else {
					session_start();
					$_SESSION['username'] = $rekord['username'];
					$_SESSION['user_id'] = $rekord['id'];
					
					mysqli_query($link, "INSERT INTO logs (user_id, ip, info, success) VALUES ('$id', '$ip', '$browser', 1);");
					
					header('Location: panel/index.php');
					die;
				}
			} else {
				echo "Nieprawidłowy login lub hasło!";
				mysqli_query($link, "INSERT INTO logs (user_id, ip, info, success) VALUES ('$id', '$ip', '$browser', 0);");
			}
		
		}
		
		mysqli_close($link); // zamknięcie połączenia z BD
	}
?>

<body>
	<br /><br />
	Formularz logowania
	<form method="post">
		Login:
		<input 
			type="text" 
			name="user" 
			maxlength="20" 
			required="required"
			size="20"><br>
		Haslo:
		<input 
			type="password" 
			name="pass" 
			maxlength="20" 
			required="required"
			size="20"><br>
		<input type="submit" value="Zaloguj się"/>
		<a href='rejestracja.php'>Zarejestruj się</a>
	</form>
</body>
</html>