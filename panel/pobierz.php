<?php
	session_start();
	$username = $_SESSION['username'];
	$dir = $_GET['dir'];
	$file = $_GET['plik'];
	$userPath = '../dane/' . $username . '/';
	
	if($dir) {
		$userPath .= $dir . '/';
	}
	
	$filePath = $userPath . $file;
	
	header('Content-Type: application/octet-stream');
	header("Content-Transfer-Encoding: Binary"); 
	header("Content-disposition: attachment; filename=\"" . basename($filePath) . "\""); 
	readfile($filePath); 
?>