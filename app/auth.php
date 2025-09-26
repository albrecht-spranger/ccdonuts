<?php
// app/auth.php
declare(strict_types=1);
require_once __DIR__ . '/dbConnect.php';

function attemptLogin(string $mail, string $password): ?string {
    $pdo = getDbConnection();
    $sql = $pdo->prepare('SELECT name, password FROM customers WHERE mail = ?');
    $sql->execute([$mail]);
    $user = $sql->fetch(PDO::FETCH_ASSOC);
    // ユーザーが存在し、かつパスワードが正しければ返す
    if ($user && password_verify($password, $user['password'])) {
        return $user['name'];
    }

    // 失敗時は null
    return null;
}

function getLoginUserName(): ?string
{
    // ⇒?stringとするとstring、または、nullを返す。
    // ⇒stringとするとstringしか返せないので、下のコードを...?? "";とする必要あり
    return $_SESSION['customer'] ?? null;
}

function isLoggedIn(): bool
{
    return isset($_SESSION['customer']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        setFlash('error', 'ログインが必要です。');
        redirect('login.php');
    }
}
