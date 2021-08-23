<?php
$host='localhost';
$db = 'firewall';
$username = 'postgres';
$password = 'test';
$dsn = "pgsql:host=$host;port=5432;dbname=$db;user=$username;password=$password";
	$conn = new PDO($dsn);
	$stmt = $conn -> prepare("SELECT count(*) from ports where status='allow'");
	$stmt1 = $conn -> prepare("SELECT count(*) from ports where status='block'");
	$stmt -> execute();
	$stmt1 -> execute();
	$row = $stmt -> fetchAll();
	$row2 = $stmt1 -> fetchAll();
	$user[] = json_encode($row,JSON_UNESCAPED_SLASHES);
	$user[] = json_encode($row2,JSON_UNESCAPED_SLASHES);
	$json_merge = json_encode($user);
	echo $json_merge;
?>