セットアップ手順
1) MySQLでDDLを実行してDBとテーブル、ユーザーを作成してください（ファイル名例：ccdonutsForXampp.sql）。
2) PHPの内蔵サーバで起動する場合：
   php -S localhost:8000
3) http://localhost:8000/products.php へアクセスし、カート操作を確認。
既定の接続情報は app/dbConnect.php を参照（ホスト: localhost, DB: ccdonuts, ユーザー: ccStaff, パスワード: ccDonuts）。
