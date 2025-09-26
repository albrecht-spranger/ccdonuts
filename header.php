<?php
require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php';
require_once __DIR__ . '/app/auth.php';

if (!isset($pageTitle) || $pageTitle === "") {
	$pageTitle = "CCドーナツ";
}

// ログイン済みか判断
if (isLoggedIn()) {
	$userName = getLoginUserName();  // 登録しておいたユーザー名
} else {
	$userName = 'ゲスト';
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $pageTitle; ?></title>
	<link rel="stylesheet" href="styles/reset.css">
	<link rel="stylesheet" href="styles/style.css">
	<link rel="stylesheet" href="styles/header.css">
	<link rel="stylesheet" href="styles/products.css">
	<link rel="stylesheet" href="styles/productDetail.css">
	<link rel="stylesheet" href="styles/cart.css">
	<link rel="stylesheet" href="styles/login.css">
	<link rel="stylesheet" href="styles/registerStyles.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
</head>

<body>
	<header class="siteHeader">
		<div class="headerBar">
			<button class="menuButton" id="menuButton">
				<!-- hamburger -->
				<svg width="28" height="20" viewBox="0 0 28 20">
					<rect x="0" y="0" width="28" height="2" rx="1" />
					<rect x="0" y="9" width="28" height="2" rx="1" />
					<rect x="0" y="18" width="28" height="2" rx="1" />
				</svg>
			</button>

			<a href="index.php" class="logoWrap">
				<img src="images/pcHeaderLogo.svg" alt="C.C. Donuts">
			</a>

			<div class="headerActions">
				<a href="login.php" class="actionItem" aria-label="ログイン">
					<div class="iconWrap">
						<img src="images/loginLogo.svg" alt="ログイン" width="36" height="36">
					</div>
					<span class="actionLabel">ログイン</span>
				</a>
				<a href="cart.php" class="actionItem" aria-label="カート">
					<div class="iconWrap">
						<img src="images/cartLogo.svg" alt="カート" width="36" height="36">
					</div>
					<span class="actionLabel">カート</span>
				</a>
			</div>
		</div>

		<div class="searchArea">
			<form class="searchForm" action="search.php" method="get" role="search" aria-label="サイト内検索">
				<button class="searchButton" type="submit" aria-label="検索">
					<!-- search icon -->
					<svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true">
						<path d="M21 21l-4.35-4.35m1.35-5.65a7 7 0 1 1-7-7 7 7 0 0 1 7 7Z" />
					</svg>
				</button>
				<input class="searchInput" type="text" name="q" placeholder="キーワードを入力" />
			</form>
		</div>

		<!-- パンくずリスト -->
		<?php
		// パンくずが2つ以上あれば、パンくずを表示（1つだけはTOPのみ）
		if (isset($breadcrumbs) && count($breadcrumbs) > 1) { ?>
			<div class="breadcrumbContainer">
				<p class="breadcrumb">
					<?php
					if (!$breadcrumbs) {
						echo 'undefined';
					} else {
						foreach ($breadcrumbs as $i => $bc) {
							if ($i !== 0) {
								echo '＞';
							}
							$label = htmlspecialchars($bc['label'] ?? '', ENT_QUOTES, 'UTF-8');
							$url   = $bc['url']   ?? null;
							if ($url) {
								$href = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
								echo '<a href="' . $href . '">' . $label . '</a>';
							} else {
								echo $label;
							}
						}
					}
					?>
				</p>
			</div>
		<?php } ?>

		<!-- ログインユーザ名 -->
		<div class="loginUserContainer">
			<p>ようこそ　<?= $userName ?> 様</p>
		</div>

		<!-- スマホ用開閉メニュー（必要に応じて項目追加） -->
		<nav id="globalNav" class="globalNav" hidden>
			<button class="menuClose" id="menuClose" aria-label="メニューを閉じる">✕</button>
			<ul>
				<li><a href="products.php">商品一覧</a></li>
				<li><a href="faq.php">よくある質問</a></li>
				<li><a href="policy.php">当サイトのポリシー</a></li>
				<li><a href="contact.php">お問い合わせ</a></li>
			</ul>
		</nav>
	</header>
	<main class="siteMain">