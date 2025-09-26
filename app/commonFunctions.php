<?php
// app/commonFunctions.php
declare(strict_types=1);

function redirect(string $path): void
{
	header('Location: ' . $path);
	exit;
}

// Simple flash messages
function setFlash(string $key, string $value): void
{
	$_SESSION['flash'][$key] = $value;
}

function getFlash(string $key): ?string
{
	if (!empty($_SESSION['flash'][$key])) {
		$v = $_SESSION['flash'][$key];
		unset($_SESSION['flash'][$key]);
		return $v;
	}
	return null;
}
