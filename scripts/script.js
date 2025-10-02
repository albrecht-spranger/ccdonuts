const elLogoutBtn = document.getElementById('logoutBtn');
if (elLogoutBtn) {
	elLogoutBtn.addEventListener('click', function (e) {
		e.preventDefault(); // 直接遷移するのを止める
		if (confirm("本当にログアウトしますか？")) {
			window.location.href = 'logout.php';
		}
	});
};