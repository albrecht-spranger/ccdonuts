<?php
// app/logoutProcess.php
declare(strict_types=1);
require_once __DIR__ . '/sessionManager.php';
require_once __DIR__ . '/commonFunctions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../index.php');
}

if (empty($_POST['csrfToken']) || !hash_equals($_SESSION['csrfToken'] ?? '', (string)$_POST['csrfToken'])) {
    setFlash('error', '不正なリクエストです。');
    redirect('../index.php');
}

unset($_SESSION['customer']);
setFlash('done', 'ログアウトしました。');
redirect('../index.php');
