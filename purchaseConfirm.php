<?php
// purchaseConfirm.php - 購入前最終確認（GET専用）
//
// 処理概要：
// - ログイン済みでなければログインを促す
// - セッション内のカート情報を取得し、数量・合計金額を再計算
// - ログイン済みなら customers テーブルから住所・氏名を取得
// - このページでは口座情報は出さない（purchaseDone.php で案内）
// - 「購入を確定する」ボタンで purchaseProcess.php へ POST（CSRF付き）
declare(strict_types=1);

require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php'; // redirect(), setFlash(), csrf_* など
require_once __DIR__ . '/app/auth.php';            // isLoggedIn()

// 予期せぬPOSTなどGETにして自分自身にリダイレクト
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
	redirect('purchaseConfirm.php');
	exit;
}

// カート取得（cartView.phpと同じ考え方：['items'=>[...]]）
$cart  = $_SESSION['cart'] ?? ['items' => []];
$items = $cart['items'] ?? [];
if (empty($items)) {
	setFlash('error', 'カートが空です。');
	redirect('cartView.php', 303);
	exit;
}

// ログイン/顧客情報
$loggedIn = isLoggedIn();
$customer = null;
if ($loggedIn) {
	$customerId = $_SESSION['customer']['id'] ?? null;
	if ($customerId) {
		try {
			$pdo  = getDbConnection();
			$stmt = $pdo->prepare('SELECT id, name, furigana, postcode_a, postcode_b, address, mail FROM customers WHERE id = ?');
			$stmt->execute([$customerId]);
			$customer = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
		} catch (Throwable $e) {
			error_log("[purchaseConfirm.php / DB Error] " . $e->getMessage());
			echo 'DBエラーが発生しました。';
			exit;
		}
	}
}

// 合計再計算（サーバ側を正）
$totalQty = 0;
$total    = 0;
foreach ($items as $it) {
	$qty   = (int)($it['qty'] ?? $it['quantity'] ?? 1);
	$price = (int)($it['price'] ?? 0);
	$totalQty += $qty;
	$total    += $price * $qty;
}

// 表示用
$customerName = htmlspecialchars($customer['name'] ?? 'お名前', ENT_QUOTES, 'UTF-8');
$furigana     = htmlspecialchars($customer['furigana'] ?? 'フリガナ', ENT_QUOTES, 'UTF-8');
$postcode     = htmlspecialchars((($customer['postcode_a'] ?? '000') . '-' . ($customer['postcode_b'] ?? '0000')), ENT_QUOTES, 'UTF-8');
$address      = htmlspecialchars($customer['address'] ?? '住所', ENT_QUOTES, 'UTF-8');
$mail         = htmlspecialchars($customer['mail'] ?? 'メールアドレス', ENT_QUOTES, 'UTF-8');

// 画面タイトル
$pageTitle = 'CCドーナツ | 購入確認';
require __DIR__ . '/head.php';
?>

<body>
	<?php require __DIR__ . '/header.php'; ?>

	<main>
		<!-- パンくずリスト -->
		<?php
		$breadcrumbs = [
			['label' => 'TOP',   'url' => 'index.php'],
			['label' => 'カート', 'url' => 'cartView.php'],
			['label' => '購入確認', 'url' => null],
		];
		require __DIR__ . '/breadcrumbs.php';
		?>

		<!-- ログインユーザ名 -->
		<div class="loginUserContainer">
			<p><?= $loggedIn ? $customerName : 'ようこそ　ゲスト' ?> 様</p>
		</div>

		<?php
		if (!$loggedIn) {
			setFlash('error', 'ログイン後に再度お試しください。');
		}
		?>

		<section class="purchaseConfirmSection">
			<!-- エラー表示 -->
			<?php
			$error = getFlash('error');
			if ($error) {
			?>
				<div class="errorMessage"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
			<?php } ?>

			<h1 class="purchaseTitle">ご購入確認</h1>

			<!-- ご購入商品 -->
			<section class="pch2Section moreGap40">
				<h2 class="pch2Title">ご購入商品</h2>
				<?php foreach ($items as $it):
					$name = htmlspecialchars((string)($it['name'] ?? $it['title'] ?? '商品'), ENT_QUOTES, 'UTF-8');
					$qty  = (int)($it['qty'] ?? $it['quantity'] ?? 1);
					$price = (int)($it['price'] ?? 0);
					$subtotal = $price * $qty;
				?>
					<dl class="pcItem">
						<dt class="pcItemLeft">商品名</dt>
						<dd class="pcItemRight"><?= $name ?></dd>
						<dt class="pcItemLeft">数量</dt>
						<dd class="pcItemRight"><?= $qty ?>個</dd>
						<dt class="pcItemLeft">金額</dt>
						<dd class="pcItemRight">税込　￥<?= number_format($subtotal) ?></dd>
					</dl>
				<?php endforeach; ?>
				<dl class="pcItem">
					<p class="pcItemLeft bold">合計数量</p>
					<p class="pcItemRight bold"><?= $totalQty ?>個</p>
					<p class="pcItemLeft bold">合計金額</p>
					<p class="pcItemRight bold">税込　￥<?= number_format($total) ?></p>
				</dl>
			</section>

			<!-- お届け先（customers テーブルより） -->
			<section class="pch2Section">
				<h2 class="pch2Title">お届け先</h2>
				<dl class="pcItem">
					<dt class="pcItemLeft">お名前</dt>
					<dd class="pcItemRight"><?= $customerName ?></dd>
					<dt class="pcItemLeft">郵便番号</dt>
					<dd class="pcItemRight"><?= $postcode ?></dd>
					<dt class="pcItemLeft">住所</dt>
					<dd class="pcItemRight"><?= $address ?></dd>
				</dl>
			</section>

			<!-- お支払い方法（この画面では口座情報は出さない） -->
			<section class="pch2Section">
				<h2 class="pch2Title">お支払い方法</h2>
				<dl class="pcItem">
					<dt class="pcItemLeft">お支払い</dt>
					<dd class="pcItemRight">銀行振込</dd>
					<dt class="pcItemLeft">銀行名</dt>
					<dd class="pcItemRight">ゆうちょ銀行</dd>
					<dt class="pcItemLeft">支店名</dt>
					<dd class="pcItemRight">〇〇支店</dd>
					<dt class="pcItemLeft">口座種別</dt>
					<dd class="pcItemRight">当座</dd>
					<dt class="pcItemLeft">口座番号</dt>
					<dd class="pcItemRight">1234567</dd>
					<dt class="pcItemLeft">口座名義</dt>
					<dd class="pcItemRight">カ）シーシードーナツ</dd>
				</dl>
			</section>

			<!-- 確定フォーム（CSRFユーティリティを使用） -->
			<form class="confirmForm" method="post" action="purchaseProcess.php">
				<?= csrf_field(); ?>
				<button type="submit" class="pcSubmitBtn" <?= $loggedIn ? '' : 'disabled' ?>>
					購入を確定する
				</button>
			</form>
		</section>
	</main>

	<?php require __DIR__ . '/footer.php'; ?>
</body>

</html>