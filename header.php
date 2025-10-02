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
			<!-- ログイン／ログアウトアイコン -->
			<?php
			if (isLoggedIn()) {
			?>
				<a href="logout.php" class="actionItem" id="logoutBtn">
					<div class="iconWrap">
						<img src="images/logoutIcon.png" alt="ログアウト">
						<span class="actionLabel">ログアウト</span>
					</div>
				</a>
			<?php
			} else {
			?>
				<a href="login.php">
					<div class="iconWrap">
						<img src="images/loginLogo.svg" alt="ログイン">
						<span class="actionLabel">ログイン</span>
					</div>
				</a>
			<?php
			}
			?>
			<!-- カートアイコン -->
			<a href="cart.php" class="actionItem">
				<div class="iconWrap">
					<img src="images/cartLogo.svg" alt="カート">
					<span class="actionLabel">カート</span>
				</div>
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