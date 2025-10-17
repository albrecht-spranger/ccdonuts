<?php

declare(strict_types=1);

require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php';
require_once __DIR__ . '/app/auth.php';
// require_once __DIR__ . '/app/csrf.php';                 // check_csrf()
// require_once __DIR__ . '/app/registerValidation.php';   // collectRegisterInput(), validateRegister()

/**
 * 受け口は POST のみ
 */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    exit('Bad Request!?!?!?!');
}

/**
 * CSRF / nonce 検証(★未実装)
 */
// $csrf = $_POST['csrf_token'] ?? '';
// if (!check_csrf($csrf)) {
//     http_response_code(400);
//     exit('Bad Request: CSRF');
// }

// $nonce = $_POST['nonce'] ?? '';
// if (!hash_equals($_SESSION['register.nonce'] ?? '', $nonce)) {
//     http_response_code(400);
//     exit('Bad Request: invalid nonce');
// }

/**
 * セッションから登録データ取得（hiddenで値は持ち回らない）
 */
$data = $_SESSION['register.data'] ?? null;
if (!$data) {
    // 想定外（期限切れ等）
    http_response_code(400);
    exit('Bad Request!?!?!?!?!');
}

/**
 * 念のため最終再検証（改ざん/期限切れに強く）
 */
$errors = validateRegister($data);
if ($errors) {
    // 入力へ戻すためのフラッシュ
    $_SESSION['form_data']   = $data;
    $_SESSION['form_errors'] = $errors;
    header('Location: registerInput.php', true, 303);
    exit;
}

/**
 * DB登録（メール重複チェック → INSERT）
 */
try {
    $pdo = getDbConnection();
    $pdo->beginTransaction();

    // メール重複チェック
    $stmt = $pdo->prepare('SELECT id FROM customers WHERE mail = ? LIMIT 1');
    $stmt->execute([$data['mail']]);
    if ($stmt->fetchColumn()) {
        // 重複：入力へ戻してエラー表示
        $pdo->rollBack();
        $_SESSION['form_data']             = $data;
        $_SESSION['form_errors']['mail']   = 'このメールアドレスは既に登録されています';
        header('Location: registerInput.php', true, 303);
        exit;
    }

    // パスワードはハッシュ化して保存
    $hash = password_hash($data['password'], PASSWORD_DEFAULT);

    // INSERT（カラム名はあなたのDDLに合わせて調整）
    $ins = $pdo->prepare(
        'INSERT INTO customers
            (name, furigana, postcode_a, postcode_b, address, mail, password)
         VALUES
            (:name, :furigana, :postcode_a, :postcode_b, :address, :mail, :password)'
    );
    $ins->execute([
        ':name'       => $data['name'],
        ':furigana'   => $data['furigana'],
        ':postcode_a' => (int)$data['postcodeHead'],
        ':postcode_b' => (int)$data['postcodeTail'],
        ':address'    => $data['address'],
        ':mail'       => $data['mail'],
        ':password'   => $hash,
    ]);

    $pdo->commit();

    // 使い終わったセッション値は必ず破棄
    unset($_SESSION['register.data'], $_SESSION['register.nonce']);

    // 完了画面へ（PRG）
    header('Location: registerCompleteView.php', true, 303);
    exit;
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('registerComplete error: ' . $e->getMessage());
    http_response_code(500);
    exit('サーバー内部でエラーが発生しました。時間をおいて再度お試しください。');
}
