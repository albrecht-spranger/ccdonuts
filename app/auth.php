<?php
// app/auth.php
declare(strict_types=1);
require_once __DIR__ . '/dbConnect.php';

function attemptLogin(string $mail, string $password): ?array
{
    $pdo = getDbConnection();
    $sql = $pdo->prepare('SELECT id, name, password FROM customers WHERE mail = ?');
    $sql->execute([$mail]);
    $user = $sql->fetch(PDO::FETCH_ASSOC);
    // ユーザーが存在し、かつパスワードが正しければ返す
    if ($user && password_verify($password, $user['password'])) {
        return [
            'id'   => (int)$user['id'],
            'name' => (string)$user['name'],
        ];
    }

    // 失敗時は null
    return null;
}

function getLoginUserName(): ?string
{
    // ⇒?stringとするとstring、または、nullを返す。
    // ⇒stringとするとstringしか返せないので、下のコードを...?? "";とする必要あり
    return $_SESSION['customer']['name'] ?? null;
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

function norm(string $s): string
{
    // 全角→半角（英数・カナ・スペース）してtrim
    $s = mb_convert_kana($s, 'asKV', 'UTF-8');
    return trim($s);
}

function collectRegisterInput(array $src): array
{
    return [
        'name'            => norm($src['name']            ?? ''),
        'furigana'        => norm($src['furigana']        ?? ''),
        'postcodeHead'    => norm($src['postcodeHead']    ?? ''),
        'postcodeTail'    => norm($src['postcodeTail']    ?? ''),
        'address'         => norm($src['address']         ?? ''),
        'mail'            => norm($src['mail']            ?? ''),
        'mailConfirm'     => norm($src['mailConfirm']     ?? ''),
        'password'        => (string)($src['password']        ?? ''),
        'passwordConfirm' => (string)($src['passwordConfirm'] ?? ''),
    ];
}

function validateRegister(array $d): array
{
    $errors = [];

    if ($d['name'] === '') $errors['name'] = '名前が未入力です';
    if ($d['furigana'] === '' || !preg_match('/^[ァ-ヶー\s]+$/u', $d['furigana'])) {
        $errors['furigana'] = 'フリガナはカタカナで入力してください';
    }
    if (!preg_match('/^\d{3}$/', $d['postcodeHead'])) $errors['postcodeHead'] = '郵便番号(前半)は3桁の数字です';
    if (!preg_match('/^\d{4}$/', $d['postcodeTail'])) $errors['postcodeTail'] = '郵便番号(後半)は4桁の数字です';
    if ($d['address'] === '') $errors['address'] = '住所が未入力です';
    if (!filter_var($d['mail'], FILTER_VALIDATE_EMAIL)) $errors['mail'] = 'メールアドレスの形式が正しくありません';
    if ($d['mail'] !== $d['mailConfirm']) $errors['mailConfirm'] = 'メールアドレスが一致しません';
    if (!preg_match('/^[A-Za-z0-9]{8,20}$/', $d['password'])) $errors['password'] = 'パスワードは半角英数字8〜20文字です';
    if ($d['password'] !== $d['passwordConfirm']) $errors['passwordConfirm'] = 'パスワードが一致しません';

    return $errors;
}
