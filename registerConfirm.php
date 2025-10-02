<?php

declare(strict_types=1);
require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php';
require_once __DIR__ . '/app/auth.php';
?>

<!DOCTYPE html>
<html lang="ja">

<?php
$pageTitle = "CCドーナツ | 会員登録";
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










		<!-- 入力チェック -->
		<?php
		function val($k)
		{
			return isset($_POST[$k]) ? trim($_POST[$k]) : '';
		}
		$name = val('name');
		$furigana = val('furigana');
		$postcodeHead = val('postcodeHead');
		$postcodeTail = val('postcodeTail');
		$address = val('address');
		$mail = val('mail');
		$mailConfirm = val('mailConfirm');
		$password = val('password');
		$passwordConfirm = val('passwordConfirm');

		$errors = [];
		if (!$name) $errors[] = '名前が未入力です';
		if (!$furigana) $errors[] = 'フリガナが未入力です';
		if (!$postcodeHead || !preg_match('/^\d{3}$/', $postcodeHead)) $errors[] = '郵便番号(前半)の形式が正しくありません';
		if (!$postcodeTail || !preg_match('/^\d{4}$/', $postcodeTail)) $errors[] = '郵便番号(後半)の形式が正しくありません';
		if (!$address) $errors[] = '住所が未入力です';
		if (!$mail || !filter_var($mail, FILTER_VALIDATE_EMAIL)) $errors[] = 'メールアドレスの形式が正しくありません';
		if ($mail !== $mailConfirm) $errors[] = 'メールアドレスが一致しません';
		if (!$password || !preg_match('/^[A-Za-z0-9]{8,20}$/', $password)) $errors[] = 'パスワードは半角英数字8〜20文字です';
		if ($password !== $passwordConfirm) $errors[] = 'パスワードが一致しません';
		?>

		<!-- 会員登録の入力確認セクション -->
		<section>
			<div class="loginTitleContainer">
				<h1 class="h1Login">入力確認</h1>
			</div>

			<!-- エラー表示 -->
			<?php if (!empty($errors)): ?>
				<div class="errors">
					<ul>
						<?php foreach ($errors as $e): ?>
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
						<dt>お名前</dt>
						<dd><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></dd>
						<dt>お名前（フリガナ）</dt>
						<dd><?= htmlspecialchars($furigana, ENT_QUOTES, 'UTF-8') ?></dd>
						<dt>郵便番号</dt>
						<dd><?= htmlspecialchars($postcode, ENT_QUOTES, 'UTF-8') ?></dd>
						<dt>住所</dt>
						<dd><?= htmlspecialchars($address, ENT_QUOTES, 'UTF-8') ?></dd>
						<dt>メールアドレス</dt>
						<dd><?= htmlspecialchars($mail, ENT_QUOTES, 'UTF-8') ?></dd>
						<dt>メールアドレス確認用</dt>
						<dd><?= htmlspecialchars($mailConfirm, ENT_QUOTES, 'UTF-8') ?></dd>
						<dt>パスワード</dt>
						<dd><?= str_repeat('●', max(8, strlen($password))) ?></dd>
						<dt>パスワード確認用</dt>
						<dd><?= str_repeat('●', max(8, strlen($passwordConfirm))) ?></dd>
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
					</div>
				</form>
			<?php endif; ?>
	</main>
	<?php require 'footer.php'; ?>

</body>

</html>