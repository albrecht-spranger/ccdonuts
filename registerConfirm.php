<?php

declare(strict_types=1);
require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php';
require_once __DIR__ . '/app/auth.php';

// nonceチェック(★未実装)
// if (empty($_SESSION['register.data']) || empty($_SESSION['register.nonce'])) {
if (empty($_SESSION['register.data'])) {
    // 検証を通っていない or 期限切れ → 入力へ戻す
    header('Location: registerInput.php', true, 303);
    exit;
}

// 確認表示用データとワンタイムnonceを取得
$data  = $_SESSION['register.data'];
// $nonce = $_SESSION['register.nonce'];

// 各項目を個別変数に展開（未定義なら空文字）
$name            = (string)($data['name']            ?? '');
$furigana        = (string)($data['furigana']        ?? '');
$postcodeHead    = (string)($data['postcodeHead']    ?? '');
$postcodeTail    = (string)($data['postcodeTail']    ?? '');
$address         = (string)($data['address']         ?? '');
$mail            = (string)($data['mail']            ?? '');
$mailConfirm     = (string)($data['mailConfirm']     ?? '');
$password        = (string)($data['password']        ?? '');
$passwordConfirm = (string)($data['passwordConfirm'] ?? '');
?>

<!DOCTYPE html>
<html lang="ja">

<?php
$pageTitle = "CCドーナツ | 入力確認";
require "head.php";
?>

<body>
	<!-- ヘッダ -->
	<?php require "header.php" ?>

	<main>
		<!-- パンくずリスト -->
		<?php
		$breadcrumbs = [
			['label' => 'TOP', 'url' => 'index.php'],
			['label' => 'ログイン', 'url' => 'login.php'],
			['label' => '会員登録', 'url' => 'registerInput.php'],
			['label' => '入力確認', 'url' => null],
		];
		require "breadcrumbs.php"
		?>

		<!-- ログインユーザ名 -->
		<div class="loginUserContainer">
			<p><?= isLoggedIn() ? getLoginUserName() : 'ようこそ　ゲスト' ?> 様</p>
		</div>

		<!-- 会員登録の入力確認セクション -->
		<section>
			<div class="loginTitleContainer">
				<h1 class="h1Login">入力確認</h1>
			</div>

			<div class="loginContents">
				<form action="registerComplete.php" method="post" class="registerBox">
					<div class="formRow">
						<p class="confirmLabel">お名前</p>
						<p class="confirmValue"><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></p>
					</div>
					<div class="formRow">
						<p class="confirmLabel">お名前（フリガナ）</p>
						<p class="confirmValue"><?= htmlspecialchars($furigana, ENT_QUOTES, 'UTF-8') ?></p>
					</div>
					<div class="formRow">
						<p class="confirmLabel">郵便番号</p>
						<p class="confirmValue">
							<?= htmlspecialchars($postcodeHead, ENT_QUOTES, 'UTF-8') . '-' . htmlspecialchars($postcodeTail, ENT_QUOTES, 'UTF-8') ?>
						</p>
					</div>
					<div class="formRow">
						<p class="confirmLabel">住所</p>
						<p class="confirmValue"><?= htmlspecialchars($address, ENT_QUOTES, 'UTF-8') ?></p>
					</div>
					<div class="formRow">
						<p class="confirmLabel">メールアドレス</p>
						<p class="confirmValue"><?= htmlspecialchars($mail, ENT_QUOTES, 'UTF-8') ?></p>
					</div>
					<div class="formRow">
						<p class="confirmLabel">メールアドレス確認用</p>
						<p class="confirmValue"><?= htmlspecialchars($mailConfirm, ENT_QUOTES, 'UTF-8') ?></p>
					</div>
					<div class="formRow">
						<p class="confirmLabel">パスワード</p>
						<p class="confirmValue"><?= str_repeat('●', max(8, strlen($password))) ?></p>
					</div>
					<div class="formRow">
						<p class="confirmLabel">パスワード確認用</p>
						<p class="confirmValue"><?= str_repeat('●', max(8, strlen($passwordConfirm))) ?></p>
					</div>

					<div class="buttons">
						<button type="submit" class="primaryBtn">登録する</button>
					</div>
				</form>
			</div>
		</section>
	</main>
	<?php require 'footer.php'; ?>

</body>

</html>