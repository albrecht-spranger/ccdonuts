<header class="siteHeader">
	<!-- ドロワーメニュー(縦型) -->
	<nav id="globalNavV" class="globalNavV">
		<!-- 中央アイコン -->
		<img src="images/pcHeaderLogo.svg" alt="C.C. Donuts" class="globalNavVIcon">
		<!-- メニュークローズボタン -->
		<button id="globalNavVClose">
			<img src="images/closeBtn.svg">
		</button>
		<!-- メニューリスト -->
		<ul>
			<li><a href="index.php">TOP</a></li>
			<li><a href="products.php">商品一覧</a></li>
			<li><a href="#">よくある質問</a></li>
			<li><a href="#">お問い合わせ</a></li>
			<li><a href="#">当サイトのポリシー</a></li>
		</ul>
	</nav>

	<!-- ドロワーメニュー(横型) -->
	<!-- <nav id="globalNavH" class="globalNavH"> -->
		<!-- <img src="images/pcHeaderLogo.svg" alt="C.C. Donuts" class="globalNavHIcon"> -->
		<!-- <button id="globalNavHClose"> -->
			<!-- <img src="images/closeBtn.svg"> -->
		<!-- </button> -->
		<!-- <ul> -->
			<!-- <li><a href="index.php">TOP</a></li> -->
			<!-- <li><a href="products.php">商品一覧</a></li> -->
			<!-- <li><a href="#">よくある質問</a></li> -->
			<!-- <li><a href="#">お問い合わせ</a></li> -->
			<!-- <li><a href="#">当サイトのポリシー</a></li> -->
		<!-- </ul> -->
	<!-- </nav> -->

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
						<img src="images/runout.png" alt="ログアウト">
						<span class="actionLabel">ログアウト</span>
					</div>
				</a>
			<?php
			} else {
			?>
				<a href="login.php">
					<div class="iconWrap">
						<img src="images/runin.png" alt="ログイン">
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

	<!-- 検索ボックス -->
	<div class="searchArea">
		<form class="searchForm" action="search.php" method="get" role="search">
			<button class="searchButton" type="submit">
				<img src="images/searchIcon.svg" class="searchIcon">
			</button>
			<input class="searchInput" type="text" name="q" placeholder="キーワードを入力" />
		</form>
	</div>
</header>