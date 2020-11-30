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
		$pass2=$_POST['pass2']; // powtórzone hasło z fomularza

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
			if($pass != $pass2) {
				echo "Hasła nie są takie same!";
			} else {
				$hashedPass = password_hash($pass, PASSWORD_BCRYPT);
				
				mysqli_query($link, "INSERT INTO users (username, password) VALUES ('$user', '$hashedPass');");
				
				$userPath = 'dane/' . $user;
				mkdir($userPath);
				
				echo "Utworzono użytkownika, możesz się zalogować!";
			}
		} else { // jeśli $rekord istnieje
			echo "Użytkownik o takim loginie istnieje!";
		}
		
		mysqli_close($link); // zamknięcie połączenia z BD
	}
?>

<body>
	<br /><br />
	Formularz rejestracji
	<form method="post">
	Login:
	<input 
		type="text" 
		name="user" 
		maxlength="20" 
		required="required"
		size="20"><br>
	Hasło:
	<input 
		type="password" 
		name="pass" 
		maxlength="20" 
		required="required"
		size="20"><br>
	Powtórz hasło:
	<input 
		type="password" 
		name="pass2" 
		maxlength="20" 
		required="required"
		size="20"><br>
	<input type="submit" value="Zarejestruj się"/>
	<a href='index.php'>Logowanie</a>
	</form>
</body>
</html>