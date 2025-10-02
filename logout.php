<?php
// logout.php
declare(strict_types=1);
require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php';

if (isset($_SESSION['customer'])) {
    unset($_SESSION['customer']);
} else {
    setFlash('error', 'すでにログアウト済みです。');
};
redirect('logoutComplete.php');
exit;
?>