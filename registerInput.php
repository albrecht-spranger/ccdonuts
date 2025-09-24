<?php
session_start();
require 'header.php';
?>
<!-- Page-specific CSS -->
<link rel="stylesheet" href="styles/registerStyles.css">
<main class="registerPage">
  <div class="pageHeader">
    <div class="breadcrumbs">会員登録 ＞ ログイン ＞ TOP</div>
    <div class="welcomeBar">ようこそ　ゲスト様</div>
  </div>

  <h1 class="pageTitle">会員登録</h1>

  <form action="registerConfirm.php" method="post" class="registerForm" novalidate>
    <div class="formRow">
      <label for="name">お名前（必須）</label>
      <input id="name" name="name" type="text" required>
    </div>

    <div class="formRow">
      <label for="furigana">お名前（フリガナ）（必須）</label>
      <input id="furigana" name="furigana" type="text" required>
    </div>

    <div class="formRow">
      <label for="postcode">郵便番号（必須）</label>
      <input id="postcode" name="postcode" type="text" inputmode="numeric" pattern="\d{3}-?\d{4}" placeholder="123-4567" required>
    </div>

    <div class="formRow">
      <label for="address">住所（必須）</label>
      <input id="address" name="address" type="text" required>
    </div>

    <div class="formRow">
      <label for="mail">メールアドレス（必須）</label>
      <input id="mail" name="mail" type="email" required>
    </div>

    <div class="formRow">
      <label for="mailConfirm">メールアドレス確認用（必須）</label>
      <input id="mailConfirm" name="mailConfirm" type="email" required>
    </div>

    <div class="formRow">
      <label for="password">パスワード（必須）</label>
      <input id="password" name="password" type="password" minlength="8" maxlength="20" pattern="[A-Za-z0-9]+" required>
      <p class="note">半角英数字8文字以上20文字以内で入力してください。※記号の使用はできません</p>
    </div>

    <div class="formRow">
      <label for="passwordConfirm">パスワード確認用（必須）</label>
      <input id="passwordConfirm" name="passwordConfirm" type="password" required>
    </div>

    <div class="buttons">
      <button type="submit" class="primaryBtn">入力確認する</button>
    </div>
  </form>
</main>
<?php require 'footer.php'; ?>
