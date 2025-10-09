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
	$total += (int) $it['price'] * (int) $it['qty'];
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

		<!-- カートの中身表示 -->
		<div class="cartSection">
			<!-- エメッセージ表示 -->
			<?php
			$error = getFlash('error');
			$success = getFlash('success');
			if ($error)
				echo '<div class="errorMessage">' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</div>';
			if ($success)
				echo '<div class="successMessage">' . htmlspecialchars($success, ENT_QUOTES, 'UTF-8') . '</div>';
			?>

			<?php if (!$items): ?>
				<div class="cartSummary">
					<div class="cartSummaryText">
						<p>カートは空です。</p>
					</div>
				</div>
			<?php else: ?>
				<?php require "cartSummary.php"; ?>

				<?php foreach ($items as $it): ?>
					<?php
					$pid = (int) $it['id'];
					$name = (string) $it['name'];
					$price = (int) $it['price'];
					$qty = (int) $it['qty'];
					$img = trim((string) ($it['image'] ?? ''));
					$imgSrc = $img !== '' ? "images/" . rawurlencode($img) : "images/noimage.jpg";
					$line = $price * $qty;
					?>
					<div class="cartItem">
						<div class="cartImageContainer">
							<img src="<?= htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8'); ?>" alt="">
						</div>
						<div class="cartItemDetail">
							<p class="cartItemTitle">
								<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>
							</p>
							<form action="cart.php" method="post">
								<div class="priceNQty">
									<p class="itemPrice">税込　￥<?= number_format($price) ?></p>
									<input type="hidden" name="csrfToken"
										value="<?= htmlspecialchars($_SESSION['csrfToken'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
									<input type="hidden" name="action" value="update">
									<input type="hidden" name="productId" value="<?= $pid ?>">
									<p class="qtyText">数量
										<input type="number" class="qtyInput" name="quantity" min="0" max="99"
											value="<?= $qty ?>">
										個
									</p>
								</div>
								<button type="submit" class="itemUpdateBtn" disabled>再計算</button>
							</form>
							<form action="cart.php" class="deleteItemContainer" method="POST">
								<input type="hidden" name="action" value="remove">
								<input type="hidden" name="productId" value="<?= $pid ?>">
								<button type="submit" class="deleteItemBtn">削除する</button>
							</form>
						</div>
					</div>
				<?php endforeach; ?>
				<?php require "cartSummary.php"; ?>
			<?php endif; ?>
			<a href="products.php">
				<button type="button" class="continueShoppingBtn">買い物を続ける</button>
			</a>
		</div>
		</div>
	</main>

	<?php require "footer.php"; ?>

	<!-- 再計算の操作 -->
	<script src="scripts/cart.js"></script>
</body>