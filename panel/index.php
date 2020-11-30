<!doctype html>

<html lang="pl">
<head>
  <meta charset="utf-8">

  <title>Olszewski Michał</title>
</head>

<?php
	// plik z konfiguracją
	include '../konfiguracja.php';
		
	// próba połączenia
	$link = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
		
	if(!$link) { 
		echo"Error: ". mysqli_connect_errno()." ".mysqli_connect_error(); 
	} // obsługa błędu połączenia z BD
		
	mysqli_query($link, "SET NAMES 'utf8'"); // ustawienie polskich znaków

	session_start();
	$userId = $_SESSION['user_id'];
	$username = $_SESSION['username'];

	$failR = mysqli_query($link, "SELECT * FROM logs WHERE user_id = $userId AND success = 0 ORDER by created_at DESC LIMIT 1;");
	$fail = mysqli_fetch_array($failR);
	
	if($fail && $_GET['hide']) {
		$failId = $fail['id'];
		
		mysqli_query($link, "UPDATE logs SET hidden = 1 WHERE id = $failId");
		
		$fail = null;
	}
	
	$dir = $_GET['dir'];
	$userPath = '../dane/' . $username . '/';
	
	if($dir) {
		$userPath .= $dir . '/';
	}
	
	$files = array_diff(scandir($userPath), array('..', '.'));
	
	mysqli_close($link); // zamknięcie połączenia z BD
?>

<body>
	<?php if($fail && !$fail['hidden']): ?>
		Wystąpiło błędne logowanie!<br />
		Użytkowniku! Podjęto błędną próbę logowania na Twoje konto z adresu IP: 
		<?php echo $fail['ip']; ?> przy użyciu przeglądarki <?php echo $fail['info']; ?>
		w dniu <?php echo date('d.m.Y', strtotime($fail['created_at'])); ?>
		o godzinie <?php echo date('H:m:i', strtotime($fail['created_at'])); ?>
		
		<form>
			<input type='hidden' name='hide' value='1'>
			<input type='submit' value='Rozumiem, ukryj komunikat'>
		</form>
	<?php endif; ?>
	
	<br /><br />
	
	<a href="wyslij.php?dir=<?php echo $dir; ?>">Wgraj nowy plik</a><br />
	
	<?php if(!$dir): ?>
		<a href="utworz_folder.php">Utwórz nowy folder</a>
	<?php endif; ?>
	
	<br />
	<br />
	
	<div style="display: flex;">
		<?php if($dir): ?>
			<a href="index.php?dir=" style='display: flex; flex-direction: column;'>
				<img src='../assety/folder.png' width="100" height="100" alt="folder" />
				W górę
			</a>
		<?php endif; ?>	
	
		<?php foreach($files as $file): ?>
			<?php if(is_dir($userPath . $file)): ?>
				<a href="index.php?dir=<?php echo $file; ?>" style='display: flex; flex-direction: column;'>
					<img src='../assety/folder.png' width="100" height="100" alt="folder" />
					Otwórz folder
				</a>
			<?php else: ?>
				<a href="pobierz.php?dir=<?php echo $dir; ?>&plik=<?php echo $file; ?>"  style='display: flex; flex-direction: column;'>
					<img src='../assety/file.png' width="100" height="100" alt="plik" />
					Pobierz plik
				</a>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
</body>
</html>