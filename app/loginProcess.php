<?php
// /app/loginProcess.php
declare(strict_types=1);
require_once __DIR__ . '/sessionManager.php';
require_once __DIR__ . '/commonFunctions.php';
require_once __DIR__ . '/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	setFlash('error', '不正なリクエストです。もう一度お試しください。');
	redirect('../login.php');
	exit;
}

$mail = trim((string)($_POST['mail'] ?? ''));
$pass = (string)($_POST['password'] ?? '');
if ($mail === '' || $pass === '') {
	setFlash('error', 'メールアドレスとパスワードを入力してください。');
	redirect('../login.php');
	exit;
}

$user = attemptLogin($mail, $pass);
if ($user) {
	$_SESSION['customer'] = $user;
	redirect('../loginComplete.php'); // ← 成功時は完了ページへ
	exit;
} else {
	setFlash('error', 'メールアドレスまたはパスワードが違います。');
	redirect('../login.php');
	exit;
}
