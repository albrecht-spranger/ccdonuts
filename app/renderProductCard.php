<?php
/**
 * 商品カード群を描画する関数
 *
 * @param array $products  商品配列
 * @return void
 */
function renderProductCards(array $products): void
{
    foreach ($products as $p):
        $pid = (int) $p['id'];
        $name = (string) ($p['name'] ?? '');
        $price = (int) ($p['price'] ?? 0);
        $img = trim((string) ($p['image'] ?? ''));
        $imgSrc = $img !== '' ? "images/" . rawurlencode($img) : "images/noimage.jpg";
        $isNew = (int) ($p['isNew'] ?? 0);
        ?>
        <article class="cardItem">
            <a href="productDetail.php?id=<?= $pid ?>" class="thumb"
                aria-label="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>">
                <img src="<?= htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8'); ?>"
                    alt="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>" loading="lazy">
            </a>
            <h4 class="cardTitle">
                <a href="productDetail.php?id=<?= $pid ?>">
                    <?= ($isNew ? '【新作】' : '') . htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>
                </a>
            </h4>
            <p class="cardPrice">税込　￥<?= number_format($price) ?></p>

            <form action="cart.php" method="post" class="cartButton">
                <input type="hidden" name="csrfToken"
                    value="<?= htmlspecialchars($_SESSION['csrfToken'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="productId" value="<?= $pid ?>">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" name="action" value="add" class="btnAddToCart">カートに入れる</button>
            </form>
        </article>
        <?php
    endforeach;
}
?>