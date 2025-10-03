<?php

declare(strict_types=1);
require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php';
require_once __DIR__ . '/app/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(400);
	exit('Bad Request!?!');
}

// CSRFチェック(★未実装)

$data   = collectRegisterInput($_POST);
$errors = validateRegister($data);

// エラーがある場合、registerInput.phpに戻す
if ($errors) {
	$_SESSION['form_data']   = $data;
	$_SESSION['form_errors'] = $errors;
	header('Location: registerInput.php', true, 303);
	exit;
}

// OKなので確認画面へ
$_SESSION['register.data']   = $data;
// nonce (★未実装)
// $_SESSION['register.nonce'] = bin2hex(random_bytes(16));
header('Location: registerConfirm.php', true, 303);
exit;
