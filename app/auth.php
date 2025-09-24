<?php
// app/auth.php
declare(strict_types=1);
require_once __DIR__ . '/dbConnect.php';

function attemptLogin(string $mail, string $password): ?array {
    $pdo = getDbConnection();
    $sql = $pdo->prepare('SELECT id, name, mail, password FROM customers WHERE mail = ? AND password = ? AND id IS NOT NULL');
    $sql->execute([$mail, $password]);
    $user = $sql->fetch();
    return $user ?: null;
}

function currentUser(): ?array {
    return $_SESSION['customer'] ?? null;
}

function isLoggedIn(): bool {
    return isset($_SESSION['customer']);
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        set_flash('error', 'ログインが必要です。');
        redirect('login.php');
    }
}
