registerInput.php；ユーザ情報入力画面(GET)
	セッションからデータとエラーを読み込み
	入力画面を表示
		セッションのデータがあれば、これを初期値に表示
		セッションのエラーがあれば、これをnoteに表示
		csrfトークンをhiddenで埋め込み
	submit⇒registerCheck.phpへPOST


registerCheck.php；入力内容のチェックと次画面への遷移
	CSRFチェックがNG、または、POST呼び出しではない場合
		不正呼び出し
		exit
	$_POSTから値を取得
	セッションに入力内容を保存
	整合性チェック
	OKの場合
		registerConfirm.phpを呼び出し
		exit
	NGの場合
		エラー内容をセッションに保存
		registerInput.phpを呼び出し
		exit


registerConfirm.php；登録前確認(GET)
    読み取り専用で入力内容を表示
	登録の場合
		registerComplete.phpを呼び出し
		exit
	修正の場合
		registerInput.phpを呼び出し
		exit


registerComplete.php；DBへの書き込み
	メールアドレスがかぶっていないかチェック
	OKの場合
		DB書き込み
		OKの場合
			書き込み完了表示
		NGの場合
			エラー発生をアナウンス
	NGの場合
		メールアドレスがかぶっていることをアナウンス
