<?php
$host='localhost';
$db = 'firewall';
$username = 'postgres';
$password = 'test';
$dsn = "pgsql:host=$host;port=5432;dbname=$db;user=$username;password=$password";
	$conn = new PDO($dsn);
		$stmt = $conn -> prepare("SELECT * from traffic");
	$stmt -> execute();
	$row = $stmt -> fetchAll();
	echo json_encode($row,JSON_UNESCAPED_SLASHES);
?>