<?php
function getDbConnection(){
    // 課題のDDLに合わせた既定値（必要に応じて環境に合わせて変更）
    $host = "localhost";
    $dbname = "ccdonuts";
    $user = "ccStaff";
    $password = "ccDonuts";
    $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8";
    try{
        $dbConnection = new PDO($dsn,$user,$password,[
            PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
        ]);
        return $dbConnection;
    }catch(PDOException $e){
        exit("DB接続エラー: ".$e->getMessage());
    }
}
?>