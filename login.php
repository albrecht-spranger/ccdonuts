<?php

declare(strict_types=1);
require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php';
require_once __DIR__ . '/app/auth.php';

if (isLoggedIn()) {
	setFlash('error', 'すでにログイン済みです');
	redirect('loginComplete.php');
	exit;
}
?>

<!DOCTYPE html>
<html lang="ja">

<?php
$pageTitle = "CCドーナツ | ログイン";
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
			['label' => 'ログイン', 'url' => null],
		];
		require "breadcrumbs.php"
		?>

		<!-- ログインユーザ名 -->
		<div class="loginUserContainer">
			<p><?= isLoggedIn() ? getLoginUserName() : 'ようこそ　ゲスト' ?> 様</p>
		</div>

		<!-- ログインセクション -->
		<section>
			<!-- セクションタイトル -->
			<h1 class="loginTitle">ログイン</h1>

			<div class="loginContents">
				<!-- エラー表示 -->
				<?php
				$error = getFlash('error');
				if ($error) {
				?>
					<div class="errorMessage"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
				<?php } ?>

				<form method="post" class="loginBox" action="loginProcess.php">
					<div class="formRow">
						<label for="mail" class="require">メールアドレス</label>
						<input id="mail" name="mail" type="email" required value="<?= htmlspecialchars($_POST['mail'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
					</div>

					<div class="formRow">
						<label for="password" class="require">パスワード</label>
						<input id="password" name="password" type="password" required>
					</div>

					<div class="buttons">
						<button type="submit" class="primaryBtn">ログインする</button>
					</div>
				</form>
				<a class="loginToAnotherLink" href="registerInput.php">新規会員登録はこちら</a>
			</div>
		</section>
	</main>

	<!-- フッタ -->
	<?php require "footer.php"; ?>
</body>

</html>