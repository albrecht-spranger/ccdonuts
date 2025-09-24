<?php
// /app/loginProcess.php
declare(strict_types=1);
require_once __DIR__ . '/sessionManager.php';
require_once __DIR__ . '/commonFunctions.php';
require_once __DIR__ . '/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('../login.php'); }
if (empty($_POST['csrfToken']) || !hash_equals($_SESSION['csrfToken'] ?? '', (string)$_POST['csrfToken'])) {
  set_flash('error', '不正なリクエストです。もう一度お試しください。');
  redirect('../login.php');
}

$mail = trim((string)($_POST['mail'] ?? ''));
$pass = (string)($_POST['password'] ?? '');
if ($mail === '' || $pass === '') {
  set_flash('error', 'メールアドレスとパスワードを入力してください。');
  redirect('../login.php');
}

$user = attemptLogin($mail, $pass);
if ($user) {
  $_SESSION['customer'] = ['id'=>(int)$user['id'], 'name'=>$user['name'], 'mail'=>$user['mail']];
  redirect('../loginComplete.php'); // ← 成功時は完了ページへ
} else {
  set_flash('error', 'メールアドレスまたはパスワードが違います。');
  redirect('../login.php');
}
