<!DOCTYPE html>
<html lang="ja">

<?php
$pageTitle = "CCドーナツ | ログイン";
require "head.php";
?>

<body>
	<!-- ヘッダ -->
	<?php
	$breadcrumbs = [
		['label' => 'TOP', 'url' => 'index.php'],
		['label' => 'ログイン', 'url' => null],
	];
	require "header.php" ?>

	<main>
		<!-- パンくずリスト -->
		<?php require "breadcrumbs.php" ?>

		<!-- ログインユーザ名 -->
		<div class="loginUserContainer">
			<p>ようこそ　<?= isLoggedIn() ? getLoginUserName() : 'ゲスト' ?> 様</p>
		</div>

		<section class="registerPage">
			<h1 class="pageTitle">ログイン</h1>

			<?php
			$error = getFlash('error');
			if ($error) {
			?>
				<div class="errorMessage"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
			<?php } ?>

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
					<button type="submit" class="primaryBtn">ログイン</button>
					<a class="secondaryBtn" href="registerInput.php">新規会員登録はこちら</a>
				</div>
			</form>
		</section>
	</main>

	<!-- フッタ -->
	<?php require "footer.php"; ?>
</body>

</html>