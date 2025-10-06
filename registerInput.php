<?php

declare(strict_types=1);
require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php';
require_once __DIR__ . '/app/auth.php';

// 確認画面からの遷移時はセッションに入力データとエラーが入る
$data   = $_SESSION['form_data']   ?? [];
$errors = $_SESSION['form_errors'] ?? [];
// デバッグ；配列をダンプ
// echo '<pre>';
// print_r($data);
// print_r($errors);
// echo '</pre>';
unset($_SESSION['form_data'], $_SESSION['form_errors']); // フラッシュ消費
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
			['label' => '会員登録', 'url' => null],
		];
		require "breadcrumbs.php"
		?>

		<!-- ログインユーザ名 -->
		<div class="loginUserContainer">
			<p><?= isLoggedIn() ? getLoginUserName() : 'ようこそ　ゲスト' ?> 様</p>
		</div>

		<!-- 会員登録セクション -->
		<section>
			<h1 class="loginTitle">会員登録</h1>

			<div class="loginContents">
				<form action="registerCheck.php" method="post" class="registerBox">
					<div class="formRow">
						<label for="name" class="require">お名前</label>
						<?php if (!empty($errors['name'])): ?>
							<p class="note"><?= htmlspecialchars($errors['name'], ENT_QUOTES, 'UTF-8') ?></p>
						<?php endif; ?>
						<input id="name" name="name" type="text" value="<?= htmlspecialchars($data['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
					</div>

					<div class="formRow">
						<label for="furigana" class="require">お名前（フリガナ）</label>
						<?php if (!empty($errors['furigana'])): ?>
							<p class="note"><?= htmlspecialchars($errors['furigana'], ENT_QUOTES, 'UTF-8') ?></p>
						<?php endif; ?>
						<input id="furigana" name="furigana" type="text" required
							value="<?= htmlspecialchars($data['furigana'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
					</div>

					<fieldset class="formRow">
						<legend for="postcode" class="require">郵便番号</legend>
						<?php if (!empty($errors['postcodeHead'])): ?>
							<p class="note"><?= htmlspecialchars($errors['postcodeHead'], ENT_QUOTES, 'UTF-8') ?></p>
						<?php endif; ?>
						<?php if (!empty($errors['postcodeTail'])): ?>
							<p class="note"><?= htmlspecialchars($errors['postcodeTail'], ENT_QUOTES, 'UTF-8') ?></p>
						<?php endif; ?>
						<div class="postcodePair">
							<input id="postcodeHead" name="postcodeHead" type="text" inputmode="numeric"
								pattern="\d{3}" placeholder="123" required
								value="<?= htmlspecialchars($data['postcodeHead'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
							<input id="postcodeTail" name="postcodeTail" type="text" inputmode="numeric"
								pattern="\d{4}" placeholder="4567" required
								value="<?= htmlspecialchars($data['postcodeTail'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
						</div>
					</fieldset>

					<div class="formRow">
						<label for="address" class="require">住所</label>
						<?php if (!empty($errors['address'])): ?>
							<p class="note"><?= htmlspecialchars($errors['address'], ENT_QUOTES, 'UTF-8') ?></p>
						<?php endif; ?>
						<input id="address" name="address" type="text" required
							value="<?= htmlspecialchars($data['address'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
					</div>

					<div class="formRow">
						<label for="mail" class="require">メールアドレス</label>
						<?php if (!empty($errors['mail'])): ?>
							<p class="note"><?= htmlspecialchars($errors['mail'], ENT_QUOTES, 'UTF-8') ?></p>
						<?php endif; ?>
						<input id="mail" name="mail" type="email" required
							value="<?= htmlspecialchars($data['mail'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
					</div>

					<div class="formRow">
						<label for="mailConfirm" class="require">メールアドレス確認用</label>
						<?php if (!empty($errors['mailConfirm'])): ?>
							<p class="note"><?= htmlspecialchars($errors['mailConfirm'], ENT_QUOTES, 'UTF-8') ?></p>
						<?php endif; ?>
						<input id="mailConfirm" name="mailConfirm" type="email" required
							value="<?= htmlspecialchars($data['mailConfirm'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
					</div>

					<div class="formRow">
						<label for="password" class="require">パスワード</label>
						<p class="note">半角英数字8文字以上20文字以内で入力してください。※記号の使用はできません</p>
						<?php if (!empty($errors['password'])): ?>
							<p class="note"><?= htmlspecialchars($errors['password'], ENT_QUOTES, 'UTF-8') ?></p>
						<?php endif; ?>
						<input id="password" name="password" type="password" minlength="8" maxlength="20"
							pattern="[A-Za-z0-9]+" required value="">
					</div>

					<div class="formRow">
						<label for="passwordConfirm" class="require">パスワード確認用</label>
						<?php if (!empty($errors['passwordConfirm'])): ?>
							<p class="note"><?= htmlspecialchars($errors['passwordConfirm'], ENT_QUOTES, 'UTF-8') ?></p>
						<?php endif; ?>
						<input id="passwordConfirm" name="passwordConfirm" type="password" required value="">
					</div>

					<div class="buttons">
						<button type="submit" class="primaryBtn">入力確認する</button>
					</div>
				</form>
			</div>
		</section>
	</main>

	<!-- フッタ -->
	<?php require "footer.php"; ?>
</body>

</html>