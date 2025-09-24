<?php
// app/logoutProcess.php
declare(strict_types=1);
require_once __DIR__ . '/sessionManager.php';
require_once __DIR__ . '/commonFunctions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('../index.php'); }
if (empty($_POST['csrfToken']) || !hash_equals($_SESSION['csrfToken'] ?? '', (string)$_POST['csrfToken'])) {
    set_flash('error', '不正なリクエストです。');
    redirect('../index.php');
}

unset($_SESSION['customer']);
set_flash('done', 'ログアウトしました。');
redirect('../index.php');
