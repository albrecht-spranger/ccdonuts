<header class="siteHeader">
	<div class="headerBar">
			<!-- hamburger -->
		<button class="menuButton" id="menuButton">
			<img src="images/drawerIcon.svg" alt="Drawer Menu Icon">
		</button>

		<!-- ヘッダ中央アイコン -->
		<a href="index.php" class="logoWrap">
			<img src="images/pcHeaderLogo.svg" alt="C.C. Donuts">
		</a>

		<!-- ヘッダ右上アイコン -->
		<div class="headerActions">
			<a href="<?= isLoggedIn() ? 'logout.php' : 'login.php' ?>" class="actionItem">
				<div class="iconWrap <?= isLoggedIn() ? 'loggedIn' : '' ?>">
					<img src="images/loginLogo.svg" alt="<?= isLoggedIn() ? 'ログアウト' : 'ログイン' ?>">
				</div>
				<span class="actionLabel"><?= isLoggedIn() ? 'ログアウト' : 'ログイン' ?></span>
			</a>
			<a href="cart.php" class="actionItem">
				<div class="iconWrap">
					<img src="images/cartLogo.svg" alt="カート">
				</div>
				<span class="actionLabel">カート</span>
			</a>
		</div>
	</div>

	<div class="searchArea">
		<form class="searchForm" action="search.php" method="get" role="search">
			<button class="searchButton" type="submit">
				<img src="images/searchIcon.svg" class="searchIcon">
			</button>
			<input class="searchInput" type="text" name="q" placeholder="キーワードを入力" />
		</form>
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