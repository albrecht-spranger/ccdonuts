document.addEventListener('DOMContentLoaded', () => {
	const btn = document.getElementById('favoriteBtn');
	const productId = Number(btn.dataset.productId || '0');
	const isLoggedIn = btn.dataset.loggedIn === '1';
	btn.addEventListener('click', async () => {
		if (!isLoggedIn) {
			alert('お気に入りを登録するにはログインしてください。');
			return;
		}
		if (!productId) {
			alert('内部エラー：商品が指定されていません。');
			return;
		}

		btn.disabled = true;
		try {
			const url = `app/favorite.php?productId=${encodeURIComponent(productId)}`;
			const res = await fetch(url, {
				method: 'POST',
				headers: { 'Content-Type': 'application/json' },
				body: JSON.stringify({ productId })
			});

			if (res.status === 401) {
				alert('お気に入りを登録するにはログインしてください！');
				return;
			}
			if (!res.ok) {
				alert('お気に入り更新に失敗しました。');
				return;
			}

			const data = await res.json();
			const notFavoritedIcon = document.getElementById('notFavoritedHeart');
			const favoritedIcon = document.getElementById('favoritedHeart');
			if (!!data.favorited) {
				notFavoritedIcon.classList.remove('show');
				favoritedIcon.classList.add('show');
			} else {
				notFavoritedIcon.classList.add('show');
				favoritedIcon.classList.remove('show');
			}
		} catch (e) {
			alert('通信エラーが発生しました。時間をおいて再度お試しください。');
		} finally {
			btn.disabled = false;
		}
	});
});
