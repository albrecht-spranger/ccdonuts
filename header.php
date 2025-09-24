<?php
require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php';
// 共通ヘッダー（doctype〜<header>まで）
// 期待する事前変数: $pageTitle（任意）
if (!isset($pageTitle) || $pageTitle === "") {
	$pageTitle = "CC Donuts";
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php
			require_once __DIR__ . '/app/sessionManager.php';
			require_once __DIR__ . '/app/commonFunctions.php';
			echo $pageTitle; ?></title>
	<link rel="stylesheet" href="styles/reset.css">
	<link rel="stylesheet" href="styles/style.css">
	<link rel="stylesheet" href="styles/products.css">
	<link rel="stylesheet" href="styles/product_detail.css">
	<link rel="stylesheet" href="styles/cart.css">
	<link rel="stylesheet" href="styles/login.css">
</head>

<body>
	<header class="siteHeader">
		<div class="headerBar">
			<button class="menuButton" id="menuButton" aria-label="メニューを開く" aria-controls="globalNav" aria-expanded="false">
				<!-- hamburger -->
				<svg width="28" height="20" viewBox="0 0 28 20" aria-hidden="true">
					<rect x="0" y="0" width="28" height="2" rx="1" />
					<rect x="0" y="9" width="28" height="2" rx="1" />
					<rect x="0" y="18" width="28" height="2" rx="1" />
				</svg>
			</button>

			<a href="index.php" class="logoWrap" aria-label="CC Donuts ホーム">
				<!-- ロゴ画像がある場合は images/logo.png に差し替え -->
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

		<p class="welcomeText">ようこそ　ゲスト様</p>

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