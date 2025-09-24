<?php
// db.php — PDO connection for ccdonuts
declare(strict_types=1);

// 1) .env 読み込み（存在しなければ既定値を使用）
$env = @parse_ini_file(__DIR__ . '/.env', false, INI_SCANNER_TYPED);

$DB_HOST    = $env['DB_HOST']    ?? 'localhost';
$DB_PORT    = $env['DB_PORT']    ?? 3306;
$DB_NAME    = $env['DB_NAME']    ?? 'ccdonuts';
$DB_USER    = $env['DB_USER']    ?? 'ccStaff';
$DB_PASS    = $env['DB_PASS']    ?? 'ccDonuts';
$DB_CHARSET = $env['DB_CHARSET'] ?? 'utf8mb4';

$dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $DB_HOST, $DB_PORT, $DB_NAME, $DB_CHARSET);

// 2) PDO オプション
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // 例外で捕捉
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // fetch は連想配列
    PDO::ATTR_EMULATE_PREPARES   => false,                  // ネイティブプリペアド
    // PDO::ATTR_PERSISTENT      => true,                   // 必要なら持続接続
];

try {
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);

    // 任意：タイムゾーンをJSTに（必要な場合）
    // $pdo->exec("SET time_zone = '+09:00'");

    // 任意：必要に応じて SQL モード等も設定可能
    // $pdo->exec("SET SESSION sql_mode = ''");
} catch (Throwable $e) {
    // 本番では詳細は表示せずログへ
    error_log('[DB] Connection failed: ' . $e->getMessage());
    http_response_code(500);
    exit('データベース接続に失敗しました。時間をおいて再度お試しください。');
}
