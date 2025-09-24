<?php
// app/commonFunctions.php
declare(strict_types=1);

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $path): void {
    header('Location: ' . $path);
    exit;
}

// Simple flash messages
function set_flash(string $key, string $value): void {
    $_SESSION['flash'][$key] = $value;
}
function get_flash(string $key): ?string {
    if (!empty($_SESSION['flash'][$key])) {
        $v = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $v;
    }
    return null;
}
