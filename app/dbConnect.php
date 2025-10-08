<?php
// app/dbConnect.php
declare(strict_types=1);

function getDbConnection(): PDO
{
	static $pdo = null;
	if ($pdo instanceof PDO) {
		return $pdo;
	}
	$dsn = 'mysql:host=localhost;dbname=ccdonuts;charset=utf8';
	$user = 'ccStaff';
	$pass = 'ccDonuts';
	$opt = [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES => false,
	];
	$pdo = new PDO($dsn, $user, $pass, $opt);
	return $pdo;
}
