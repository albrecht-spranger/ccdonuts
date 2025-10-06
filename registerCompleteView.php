<?php
declare(strict_types=1);
require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php';
require_once __DIR__ . '/app/auth.php';
?>

<!DOCTYPE html>
<html lang="ja">

<?php
$pageTitle = "CCドーナツ | 会員登録完了";
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
			['label' => '会員登録完了', 'url' => null],
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
				<h1 class="loginTitle">会員登録完了</h1>

			<div class="loginContents">
				<div class="loginBox">
					<p class="contentInLoginBox">会員登録が完了しました。</p>
					<p class="contentInLoginBox">ログインページへお進みください。</p>
				</div>
				<a class="loginToAnotherLink" href="login.php">ログインする</a>
				<a class="loginToAnotherLink" href="index.php">TOPページへもどる</a>
			</div>
		</section>
	</main>

	<!-- フッタ -->
	<?php require "footer.php"; ?>
</body>

</html>