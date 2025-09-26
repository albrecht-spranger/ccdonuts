<?php
session_start();
require 'header.php';

// minimal server-side checks
function val($k){ return isset($_POST[$k]) ? trim($_POST[$k]) : ''; }
$name = val('name');
$furigana = val('furigana');
$postcode = val('postcode');
$address = val('address');
$mail = val('mail');
$mailConfirm = val('mailConfirm');
$password = val('password');
$passwordConfirm = val('passwordConfirm');

$errors = [];
if(!$name) $errors[] = 'お名前が未入力です';
if(!$furigana) $errors[] = 'フリガナが未入力です';
if(!$postcode || !preg_match('/^\d{3}-?\d{4}$/', $postcode)) $errors[] = '郵便番号の形式が正しくありません';
if(!$address) $errors[] = '住所が未入力です';
if(!$mail || !filter_var($mail, FILTER_VALIDATE_EMAIL)) $errors[] = 'メールアドレスの形式が正しくありません';
if($mail !== $mailConfirm) $errors[] = 'メールアドレスが一致しません';
if(!$password || !preg_match('/^[A-Za-z0-9]{8,20}$/', $password)) $errors[] = 'パスワードは半角英数字8〜20文字です';
if($password !== $passwordConfirm) $errors[] = 'パスワードが一致しません';

?>
<link rel="stylesheet" href="styles/registerStyles.css">
<main class="registerPage">
  <h1 class="pageTitle">入力確認</h1>

  <?php if (!empty($errors)): ?>
    <div class="errors">
      <ul>
        <?php foreach($errors as $e): ?>
          <li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li>
        <?php endforeach; ?>
      </ul>
      <div class="buttons">
        <a class="secondaryBtn" href="registerInput.php">戻って修正する</a>
      </div>
    </div>
  <?php else: ?>
    <form action="registerComplete.php" method="post" class="confirmForm">
      <dl class="confirmList">
        <dt>お名前</dt><dd><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></dd>
        <dt>お名前（フリガナ）</dt><dd><?= htmlspecialchars($furigana, ENT_QUOTES, 'UTF-8') ?></dd>
        <dt>郵便番号</dt><dd><?= htmlspecialchars($postcode, ENT_QUOTES, 'UTF-8') ?></dd>
        <dt>住所</dt><dd><?= htmlspecialchars($address, ENT_QUOTES, 'UTF-8') ?></dd>
        <dt>メールアドレス</dt><dd><?= htmlspecialchars($mail, ENT_QUOTES, 'UTF-8') ?></dd>
        <dt>メールアドレス確認用</dt><dd><?= htmlspecialchars($mailConfirm, ENT_QUOTES, 'UTF-8') ?></dd>
        <dt>パスワード</dt><dd><?= str_repeat('●', max(8, strlen($password))) ?></dd>
        <dt>パスワード確認用</dt><dd><?= str_repeat('●', max(8, strlen($passwordConfirm))) ?></dd>
      </dl>
      <!-- carry values forward -->
      <input type="hidden" name="name" value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="furigana" value="<?= htmlspecialchars($furigana, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="postcode" value="<?= htmlspecialchars($postcode, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="address" value="<?= htmlspecialchars($address, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="mail" value="<?= htmlspecialchars($mail, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="password" value="<?= htmlspecialchars($password, ENT_QUOTES, 'UTF-8') ?>">
      <div class="buttons">
        <button type="submit" class="primaryBtn">登録する</button>
        <a class="secondaryBtn" href="registerInput.php">戻って修正する</a>
      </div>
    </form>
  <?php endif; ?>
</main>
<?php require 'footer.php'; ?>
