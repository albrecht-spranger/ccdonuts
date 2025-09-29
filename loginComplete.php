<!DOCTYPE html>
<html lang="ja">

<?php
$pageTitle = "CCドーナツ | ログイン完了";
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
			['label' => 'ログイン完了', 'url' => null],
		];
		require "breadcrumbs.php"
		?>

		<!-- ログインユーザ名 -->
		<div class="loginUserContainer">
			<p>ようこそ　<?= isLoggedIn() ? getLoginUserName() : 'ゲスト' ?> 様</p>
		</div>



	<h1>ログイン完了</h1>

	<p class="loginDoneMessage">ログインが完了しました。</p>
	<p class="loginDoneLead"><?= getLoginUserName(); ?> 様</p>
	<p class="loginDoneSub">引き続きお楽しみください。</p>

	<div class="doneActions">
		<a class="btnPrimary" href="checkout.php">購入確認ページへすすむ</a>
		<a class="btnSecondary" href="index.php">TOPページへもどる</a>
	</div>
</main>
<?php require 'footer.php'; ?>