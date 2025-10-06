<?php
// app/commonFunctions.php
declare(strict_types=1);

function redirect(string $path, int $statusCode = 303): void
{
    header('Location: ' . $path, true, $statusCode);
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

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="'.htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8').'">';
}

function check_csrf(string $token): bool {
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}