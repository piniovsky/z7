<?php
	session_start();
	$username = $_SESSION['username'];
	$dir = $_POST['dir'];
	$userPath = '../dane/' . $username . '/';
	
	if($dir) {
		$userPath .= $dir . '/';
	}
	
	$maxRozmiar = 1024 * 1024 * 10; // byte * 1024 -> kilobyte * 1024 -> megabyty * 10

	if(is_uploaded_file($_FILES['plik']['tmp_name'])) {
		if ($_FILES['plik']['size'] > $maxRozmiar) {
			echo "Przekroczenie rozmiaru $maxRozmiar";
		} else {
			echo 'Odebrano plik: '.$_FILES['plik']['name'].'<br/>';
			move_uploaded_file(
				$_FILES['plik']['tmp_name'],
				$userPath . $_FILES['plik']['name']
			);
		}
	} else {
		echo 'Błąd przy przesyłaniu danych!';
	}
?>