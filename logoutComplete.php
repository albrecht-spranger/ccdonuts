<?php

declare(strict_types=1);
require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php';
require_once __DIR__ . '/app/auth.php';
?>

<!DOCTYPE html>
<html lang="ja">

<?php
$pageTitle = "CCドーナツ | ログアウト完了";
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
			['label' => 'ログアウト完了', 'url' => null],
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
			<h1 class="loginTitle">ログアウト完了</h1>

			<div class="loginContents">
				<!-- エラー表示 -->
				<?php
				$error = getFlash('error');
				if ($error) {
				?>
					<div class="errorMessage"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
				<?php } ?>

				<div class="loginBox">
					<p class="contentInLoginBox">ログアウトしました。</p>
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