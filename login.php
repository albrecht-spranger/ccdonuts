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
			<p>ようこそ　<?= isLoggedIn() ? getLoginUserName() : 'ゲスト' ?> 様</p>
		</div>

		<section class="registerPage">
			<div class="loginTitleContainer">
				<h1 class="h1Login">ログイン</h1>
			</div>

			<?php
			$error = getFlash('error');
			if ($error) {
			?>
				<div class="errorMessage"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
			<?php } ?>

			<div class="loginContents">
				<form method="post" class="registerForm" action="app/loginProcess.php">
					<div class="formRow">
						<label for="mail">メールアドレス</label>
						<input id="mail" name="mail" type="email" required value="<?= htmlspecialchars($_POST['mail'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
					</div>

					<div class="formRow">
						<label for="password">パスワード</label>
						<input id="password" name="password" type="password" required>
					</div>

					<div class="buttons">
						<button type="submit" class="primaryBtn">ログインする</button>
					</div>
				</form>
				<a class="registerLink" href="registerInput.php">新規会員登録はこちら</a>
			</div>
		</section>
	</main>

	<!-- フッタ -->
	<?php require "footer.php"; ?>
</body>

</html>