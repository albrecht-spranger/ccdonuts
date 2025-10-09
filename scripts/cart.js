document.addEventListener('DOMContentLoaded', () => {
	const section = document.querySelector('.cartSection');

	// 入力のたびに判定（input推奨、changeでも可）
	section.addEventListener('input', (e) => {
		const input = e.target.closest('.qtyInput');
		if (!input) return;

		// 同じ行の再計算ボタンを探す
		const form = input.closest('form');
		const button = form?.querySelector('.itemUpdateBtn');
		if (!button) return;

		// 入力が妥当かどうか（min/maxなど）も見ておくと良い
		if (input.validity.valid && input.value !== '') {
			button.disabled = false;
		} else {
			button.disabled = true;
		}
	});
});
