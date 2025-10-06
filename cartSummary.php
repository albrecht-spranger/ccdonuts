<div class="cartSummary">
	<div class="cartSummaryText">
		<p>現在　商品<?= htmlspecialchars((string) count($items), ENT_QUOTES, 'UTF-8') ?>点</p>
		<p>ご注文小計：税込 <span
				class="totalPurchase">￥<?= htmlspecialchars(number_format((int) $total), ENT_QUOTES, 'UTF-8') ?></span>
		</p>
	</div>
	<form action="purchaseConfirmation.php" method="post">
		<input type="hidden" name="csrfToken"
			value="<?= htmlspecialchars($_SESSION['csrfToken'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
		<input type="hidden" name="action" value="purchaseConfirmation">
		<button type="submit" class="purchaseConfirmationBtn">購入確認</button>
	</form>
</div>