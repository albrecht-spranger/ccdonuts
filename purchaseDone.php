<?php
// purchaseDone.php - 購入完了（GET専用）
declare(strict_types=1);

require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php'; // redirect(), setFlash()
require_once __DIR__ . '/app/auth.php';            // isLoggedIn()

// 表示専用：想定外メソッドはGETへ
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
	redirect('purchaseDone.php', 303);
}

$pageTitle = 'CCドーナツ | ご購入手続き完了';
require __DIR__ . '/head.php';
?>

<body>
	<?php require __DIR__ . '/header.php'; ?>

	<main>
		<?php
		// パンくず
		$breadcrumbs = [
			['label' => 'TOP',   'url' => 'index.php'],
			['label' => '購入確認', 'url' => 'purchaseConfirm.php'],
			['label' => '購入完了', 'url' => null],
		];
		require __DIR__ . '/breadcrumbs.php';
		?>

		<!-- ログインユーザ名 -->
		<div class="loginUserContainer">
			<p><?= isLoggedIn() ? getLoginUserName() : 'ようこそ　ゲスト' ?> 様</p>
		</div>

		<section>
			<h1 class="loginTitle">ご購入完了</h1>
			<div class="loginContents">

				<div class="loginBox">
					<p>ご購入いただきありがとうございます。</p>
					<p>今後ともご愛顧の程、宜しくお願いいたします。</p>
				</div>
				<a class="loginToAnotherLink" href="index.php">TOPページへすすむ</a>
			</div>
		</section>
	</main>

	<?php require __DIR__ . '/footer.php'; ?>
</body>

</html>