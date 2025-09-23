<?php
require_once __DIR__ . "/app/commonFunctions.php";
require_once __DIR__ . "/app/sessionManager.php";
require_once __DIR__ . "/app/dbConnect.php";
$pageTitle = "CC Donuts | カート";
require "header.php";

$db = getDbConnection();
$cart = $_SESSION["cart"];
$items = [];
$total = 0;

if (!empty($cart)) {
	$ids = implode(",", array_map("intval", array_keys($cart)));
	$stmt = $db->query("SELECT id, name, price FROM products WHERE id IN ($ids)");
	$map = [];
	foreach ($stmt as $row) {
		$map[$row["id"]] = $row;
	}
	foreach ($cart as $pid => $qty) {
		if (isset($map[$pid])) {
			$name = $map[$pid]["name"];
			$price = (int)$map[$pid]["price"];
			$subtotal = $price * (int)$qty;
			$items[] = ["id" => $pid, "name" => $name, "price" => $price, "quantity" => $qty, "subtotal" => $subtotal];
			$total += $subtotal;
		}
	}
}
?>
<main class="container">
	<h2>ショッピングカート</h2>
	<?php if (empty($items)): ?>
		<p>現在カートは空です。</p>
	<?php else: ?>
		<form action="app/updateCart.php" method="post">
			<input type="hidden" name="csrfToken" value="<?php echo h($_SESSION['csrfToken']); ?>">
			<table class="cart-table">
				<thead>
					<tr>
						<th>商品</th>
						<th>価格</th>
						<th>数量</th>
						<th>小計</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($items as $it): ?>
						<tr>
							<td><?php echo h($it["name"]); ?></td>
							<td><?php echo number_format($it["price"]); ?> 円</td>
							<td><input type="number" name="quantities[<?php echo (int)$it['id']; ?>]" min="0" value="<?php echo (int)$it['quantity']; ?>"></td>
							<td><?php echo number_format($it["subtotal"]); ?> 円</td>
							<td>
								<button formaction="app/removeFromCart.php" name="productId" value="<?php echo (int)$it['id']; ?>">削除</button>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<p class="total">合計：<?php echo number_format($total); ?> 円</p>
			<p>
				<button type="submit">数量を更新</button>
				<button formaction="app/clearCart.php">カートを空にする</button>
			</p>
		</form>
	<?php endif; ?>
	<p><a href="products.php">商品一覧へ戻る</a></p>
</main>
<?php require "footer.php"; ?>