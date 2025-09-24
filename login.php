<?php
// login.php
session_start();
require __DIR__ . '/db.php'; // $pdo を取得

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 入力値
    $mail = trim($_POST['mail'] ?? '');
    $password = $_POST['password'] ?? '';

    // バリデーション
    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'メールアドレスの形式が正しくありません。';
    }
    if ($password === '') {
        $errors[] = 'パスワードを入力してください。';
    }

    if (!$errors) {
        try {
            // customers(mail UNIQUE) 前提
            $stmt = $pdo->prepare('SELECT id, name, mail, `password` FROM customers WHERE mail = :mail LIMIT 1');
            $stmt->execute([':mail' => $mail]);
            $user = $stmt->fetch();

            // パスワード検証
            if ($user && password_verify($password, $user['password'])) {
                // 追加のハッシュ更新（オプション）: コストが変わった等
                if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
                    $newHash = password_hash($password, PASSWORD_DEFAULT);
                    $up = $pdo->prepare('UPDATE customers SET `password` = :pw WHERE id = :id');
                    $up->execute([':pw' => $newHash, ':id' => $user['id']]);
                }

                // セッションへ保存（必要に応じて項目を追加）
                $_SESSION['user'] = [
                    'id'    => (int)$user['id'],
                    'name'  => $user['name'],
                    'mail'  => $user['mail'],
                    'loginAt' => time(),
                ];

                // ログイン後の遷移先（TOPや前のページなどに変更可）
                header('Location: index.php');
                exit;
            } else {
                // 失敗（曖昧なメッセージにするのが一般的）
                $errors[] = 'メールアドレスまたはパスワードが違います。';
            }
        } catch (Throwable $e) {
            // 本番ではログにのみ出力
            error_log('[login] ' . $e->getMessage());
            $errors[] = 'ログイン処理でエラーが発生しました。時間をおいてお試しください。';
        }
    }
}
?>
<?php require __DIR__ . '/header.php'; ?>
<link rel="stylesheet" href="css/registerStyles.css"><!-- 既存CSSを流用（見た目を合わせるなら専用login.cssを作成可） -->
<main class="registerPage"><!-- 既存のカード/ボタンスタイルを使うため class を流用 -->
  <div class="pageHeader">
    <div class="breadcrumbs">ログイン ＞ TOP</div>
    <div class="welcomeBar">ようこそ　ゲスト様</div>
  </div>

  <h1 class="pageTitle">ログイン</h1>

  <?php if ($errors): ?>
    <div class="errors">
      <ul>
        <?php foreach ($errors as $e): ?>
          <li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" class="registerForm" novalidate>
    <div class="formRow">
      <label for="mail">メールアドレス</label>
      <input id="mail" name="mail" type="email" required value="<?= htmlspecialchars($_POST['mail'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>

    <div class="formRow">
      <label for="password">パスワード</label>
      <input id="password" name="password" type="password" required>
    </div>

    <div class="buttons">
      <button type="submit" class="primaryBtn">ログイン</button>
      <a class="secondaryBtn" href="registerInput.php">新規会員登録はこちら</a>
    </div>
  </form>
</main>
<?php require __DIR__ . '/footer.php'; ?>
