# CC Donuts トップページひな形（PHP版）

- `index.php` … 共通の `header.php` / `footer.php` を `require` で読込み
- `header.php` … 画像のない環境でも表示できるよう、ロゴは画像がなければテキストで代替
- `footer.php` … フッターリンク／SNS／コピーライト
- `styles/style.css` … 配色（ベージュ / ラベンダー / ブラウン）を変数化
- `scripts/menu.js` … ハンバーガーメニュー開閉（モバイル）

## 使い方
1. 本フォルダを XAMPP の公開パス（例 `C:\Users\...\ccdonuts`）に配置
2. `index.php` をブラウザで表示
3. ロゴや画像は `images/` に置き、`index.php` 内のファイル名を差し替え

> 画像例: `images/logo.png`, `images/hero.jpg`, `images/feature1.jpg` など
