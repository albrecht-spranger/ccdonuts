<?php
// cartView.php - カート表示
declare(strict_types=1);
require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php';
require_once __DIR__ . '/app/auth.php';

$cart = $_SESSION['cart'] ?? ['items' => []];
$items = $cart['items'] ?? [];

$total = 0;
foreach ($items as $it) {
	$total += (int)$it['price'] * (int)$it['qty'];
}
?>

<!DOCTYPE html>
<html lang="ja">

<?php
$pageTitle = "CCドーナツ | カート";
require "head.php";
?>

<body>
	<?php require "header.php"; ?>

	<main>
		<!-- パンくずリスト -->
		<?php
		$breadcrumbs = [
			['label' => 'TOP', 'url' => 'index.php'],
			['label' => 'カート', 'url' => null],
		];
		require "breadcrumbs.php"
		?>

		<!-- ログインユーザ名 -->
		<div class="loginUserContainer">
			<p><?= isLoggedIn() ? getLoginUserName() : 'ようこそ　ゲスト' ?> 様</p>
		</div>

		<!-- エラー表示 -->
		<?php
		$error = getFlash('error');
		$success = getFlash('success');
		if ($error)   echo '<div class="errorMessage">' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</div>';
		if ($success) echo '<div class="successMessage">' . htmlspecialchars($success, ENT_QUOTES, 'UTF-8') . '</div>';
		?>

		<!-- カートの中身表示 -->
		<div class="cartSection">
			<?php if (!$items): ?>
				<div class="cartSummary">
					<div class="cartSummaryText">
						<p>カートは空です。</p>
					</div>
				</div>
			<?php else: ?>
				<div class="cartSummary">
					<div class="cartSummaryText">
						<p>現在　商品<?= htmlspecialchars((string)count($items), ENT_QUOTES, 'UTF-8') ?>点</p>
						<p>ご注文小計：税込 <span class="totalPurchase">￥<?= htmlspecialchars(number_format((int)$total), ENT_QUOTES, 'UTF-8') ?><span></p>
					</div>
					<form action="purchaseConfirmation.php" method="post">
						<input type="hidden" name="csrfToken" value="<?= htmlspecialchars($_SESSION['csrfToken'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						<input type="hidden" name="action" value="purchaseConfirmation">
						<button type="submit" class="purchaseConfirmationBtn">購入確認</button>
					</form>
				</div>
				<?php foreach ($items as $it): ?>
					<?php
					$pid   = (int)$it['id'];
					$name  = (string)$it['name'];
					$price = (int)$it['price'];
					$qty   = (int)$it['qty'];
					$img   = trim((string)($it['image'] ?? ''));
					$imgSrc = $img !== '' ? "images/" . rawurlencode($img) : "images/noimage.jpg";
					$line  = $price * $qty;
					?>
					<div class="cartItem">
						<img src="<?= htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8'); ?>" alt="">
						<div class="cartItemDetail">
							<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>
							<div class="cartItemDetailLeft">
								<p class="totalPurchase">税込　￥<?= number_format($price) ?></p>
							</div>
							<div class="cartItemDetailRight">

							</div>
							<form action="cart.php" method="post" class="inlineForm">
								<input type="hidden" name="csrfToken" value="<?= htmlspecialchars($_SESSION['csrfToken'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
								<input type="hidden" name="action" value="update">
								<input type="hidden" name="product_id" value="<?= $pid ?>">
								<input type="number" name="quantity" min="0" max="99" value="<?= $qty ?>">
								<button type="submit" class="btn btnSmall">更新</button>
							</form>
							<td>￥<?= number_format($line) ?></td>
							<form action="cart.php" method="post" class="inlineForm">
								<input type="hidden" name="csrfToken" value="<?= htmlspecialchars($_SESSION['csrfToken'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
								<input type="hidden" name="action" value="remove">
								<input type="hidden" name="product_id" value="<?= $pid ?>">
								<button type="submit" class="btn btnSmall btnDanger">削除</button>
							</form>
						</div>
					</div>
					<?php endforeach; ?>

					<div class="cartActions">
						<form action="cart.php" method="post">
							<input type="hidden" name="csrfToken" value="<?= htmlspecialchars($_SESSION['csrfToken'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
							<input type="hidden" name="action" value="clear">
							<button type="submit" class="btn btnSecondary">カートを空にする</button>
						</form>
						<a href="checkout.php" class="btn btnPrimary">レジへ進む</a>
					</div>
				<?php endif; ?>
				<a href="products.php">
					<button type="button" class="continueShoppingBtn">買い物を続ける</button>
				</a>
					</div>
	</main>

	<?php require "footer.php"; ?>
</body>