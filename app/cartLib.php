<?php
// app/cartLib.php
declare(strict_types=1);

require_once __DIR__ . '/dbConnect.php';

function cart_get(): array {
    return $_SESSION['cart'] ?? [];
}

function cart_set(array $cart): void {
    $_SESSION['cart'] = $cart;
}

/**
 * カートに追加
 * - 価格は必ずサーバ側（DB）から取得（改ざん防止）
 * - 画像/商品名も合わせて保持（一覧描画を速くする）
 */
function cart_add(int $productId, int $qty): void {
    if ($qty < 1) { $qty = 1; }
    $pdo = getDbConnection();
    $st = $pdo->prepare('SELECT id, name, price, image FROM products WHERE id = ?');
    $st->execute([$productId]);
    $p = $st->fetch();
    if (!$p) { return; }

    $cart = cart_get();
    if (!isset($cart[$productId])) {
        $cart[$productId] = [
            'id'    => (int)$p['id'],
            'name'  => (string)$p['name'],
            'price' => (int)$p['price'],
            'image' => (string)($p['image'] ?? ''),
            'qty'   => 0,
        ];
    }
    $cart[$productId]['qty'] += $qty;
    if ($cart[$productId]['qty'] < 1) { $cart[$productId]['qty'] = 1; }
    cart_set($cart);
}

/** 数量更新（0以下は1に丸め） */
function cart_update_qty(int $productId, int $qty): void {
    $cart = cart_get();
    if (!isset($cart[$productId])) return;
    $cart[$productId]['qty'] = max(1, $qty);
    cart_set($cart);
}

/** アイテム削除 */
function cart_remove(int $productId): void {
    $cart = cart_get();
    unset($cart[$productId]);
    cart_set($cart);
}

/** 小計/点数を集計 */
function cart_totals(): array {
    $cart = cart_get();
    $items = 0;
    $subtotal = 0;
    foreach ($cart as $row) {
        $items    += (int)$row['qty'];
        $subtotal += (int)$row['price'] * (int)$row['qty'];
    }
    return ['items' => $items, 'subtotal' => $subtotal];
}
