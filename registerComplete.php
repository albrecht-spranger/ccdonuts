<?php
session_start();

/**
 * ▼ DB接続（あなたの環境に合わせて）
 * 例：db.php の中で $pdo = new PDO(...) を用意している前提
 */
require 'db.php'; // ← $pdo が得られる想定（無ければ直接PDO生成を書いてください）

/**
 * ▼ POST受信チェック
 * registerConfirm.php からの POST を想定
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 値の取得（存在しないキーは空文字に）
    $name            = trim($_POST['name']            ?? '');
    $furigana        = trim($_POST['furigana']        ?? '');
    $postcode        = trim($_POST['postcode']        ?? ''); // 123-4567 / 1234567 どちらも許容
    $address         = trim($_POST['address']         ?? '');
    $mail            = trim($_POST['mail']            ?? '');
    $password_plain  =        $_POST['password']      ?? '';

    // バリデーション（最低限：確認画面でもやっているが念のため）
    $errors = [];
    if ($name === '')      $errors[] = 'お名前が未入力です。';
    if ($furigana === '')  $errors[] = 'フリガナが未入力です。';
    if (!preg_match('/^\d{3}-?\d{4}$/', $postcode)) $errors[] = '郵便番号の形式が正しくありません。';
    if ($address === '')   $errors[] = '住所が未入力です。';
    if (!filter_var($mail, FILTER_VALIDATE_EMAIL))   $errors[] = 'メールアドレスの形式が正しくありません。';
    if (!preg_match('/^[A-Za-z0-9]{8,20}$/', $password_plain)) $errors[] = 'パスワードは半角英数字8〜20文字です。';

    // 郵便番号は 123-4567 / 1234567 の両対応 → a/b に分割
    if (!$errors) {
        $digits = preg_replace('/\D/', '', $postcode); // 数字以外除去
        $postcode_a = substr($digits, 0, 3);
        $postcode_b = substr($digits, 3, 4);
        if (strlen($postcode_a) !== 3 || strlen($postcode_b) !== 4) {
            $errors[] = '郵便番号の桁数が不正です。';
        }
    }

    // パスワードのハッシュ化
    if (!$errors) {
        $password_hash = password_hash($password_plain, PASSWORD_DEFAULT);
        if ($password_hash === false) {
            $errors[] = 'パスワードの処理に失敗しました。';
        }
    }

    if ($errors) {
        // 失敗時は確認画面へ戻すか、ここで表示
        // ここでは簡易に確認画面へ戻します（POST再構成が必要）
        $_SESSION['register_errors'] = $errors;
        $_SESSION['register_old'] = [
            'name' => $name, 'furigana' => $furigana, 'postcode' => $postcode,
            'address' => $address, 'mail' => $mail,
        ];
        header('Location: registerConfirm.php');
        exit;
    }

    // 重複メールチェック → INSERT
    try {
        $pdo->beginTransaction();

        // UNIQUE(mail) を想定
        $stmt = $pdo->prepare('SELECT id FROM customers WHERE mail = :mail LIMIT 1');
        $stmt->execute([':mail' => $mail]);
        if ($stmt->fetch()) {
            $pdo->rollBack();
            $_SESSION['register_errors'] = ['このメールアドレスは既に登録されています。'];
            $_SESSION['register_old'] = [
                'name' => $name, 'furigana' => $furigana, 'postcode' => $postcode,
                'address' => $address, 'mail' => $mail,
            ];
            header('Location: registerConfirm.php');
            exit;
        }

        // DDLに合わせてカラム名を調整
        // customers(id, name, furigana, postcode_a, postcode_b, address, mail, password)
        $ins = $pdo->prepare('
            INSERT INTO customers
              (name, furigana, postcode_a, postcode_b, address, mail, `password`)
            VALUES
              (:name, :furigana, :postcode_a, :postcode_b, :address, :mail, :password)
        ');
        $ins->execute([
            ':name'       => $name,
            ':furigana'   => $furigana,
            ':postcode_a' => $postcode_a,
            ':postcode_b' => $postcode_b,
            ':address'    => $address,
            ':mail'       => $mail,
            ':password'   => $password_hash,
        ]);

        $pdo->commit();

        // PRG（Post/Redirect/Get）：完了ページをGET表示
        header('Location: registerComplete.php?done=1');
        exit;

    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        // ログ出力など（本番は error_log へ）
        // error_log($e->getMessage());
        $_SESSION['register_errors'] = ['登録処理でエラーが発生しました。時間をおいて再度お試しください。'];
        $_SESSION['register_old'] = [
            'name' => $name, 'furigana' => $furigana, 'postcode' => $postcode,
            'address' => $address, 'mail' => $mail,
        ];
        header('Location: registerConfirm.php');
        exit;
    }
}

// ここから下は GET（?done=1）で来たときの完了画面表示
require 'header.php';
?>
<link rel="stylesheet" href="styles/registerStyles.css">
<main class="registerPage">
  <h1 class="pageTitle">会員登録完了</h1>

  <?php if (!isset($_GET['done'])): ?>
    <!-- 直アクセスなどのガード（任意） -->
    <p class="completeMessage">このページは会員登録完了後に表示されます。</p>
  <?php else: ?>
    <p class="completeMessage">会員登録が完了しました。</p>
    <div class="buttons stacked">
      <a href="login.php" class="primaryBtn">ログインページへお進みください。</a>
      <a href="cardRegister.php" class="secondaryBtn">クレジットカード登録へすすむ</a>
      <a href="cart.php" class="secondaryBtn">購入確認ページへすすむ</a>
    </div>
  <?php endif; ?>
</main>
<?php require 'footer.php'; ?>
