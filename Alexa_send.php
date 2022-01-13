<?php
date_default_timezone_set('Asia/Tokyo');
$time = strtotime("now");
require 'dbconect.php';

function write($a){
    define("TESTFILE", "./TEST.TEXT");
    $fh = fopen(TESTFILE, "a");
    date_default_timezone_set('Asia/Tokyo');
    $timestamp=time();
    $day=date("y/m/d/H時i分 ",$timestamp);
    fwrite($fh,$day.$a."\n");
}

// POSTされたJSON文字列を取り出し
$json = file_get_contents("php://input");

// JSON文字列をobjectに変換
//   ⇒ 第2引数をtrueにしないとハマるので注意
$contents = json_decode($json, true);


$alexa_id=$contents["name"];
//$alexa_id="amzn1.ask.person.AKJSQKKTKSR2ACJPQM4YQ7IT7GIH2VANC5HXSYKUAZ6ACLSW37WT7E34DDGD3GKJD6VNQBJHWBY4QBMKASVJN2OMIB2UG2EMQ3FY64JC";


try{
    $sql = "SELECT id FROM user WHERE Alexa_id = :Alexa_id";
    //プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    //プレースホルダに値をバインドする
    $stm->bindValue(':Alexa_id', $alexa_id, PDO::PARAM_STR);
    //SQL文を実行する
    $stm->execute();

    $result = $stm->fetchAll(PDO::FETCH_ASSOC);

    foreach($result as $row){
        $sql = "UPDATE comment SET LINE_check = false WHERE to_id = :id";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':id', $row["id"], PDO::PARAM_INT);
        //SQL文を実行する
        $stm->execute();

        $sql = "INSERT INTO send_log(to_id, message, datetime,confilm_check) VALUES (:id, :message, :datetime, true)";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':id', $row["id"], PDO::PARAM_INT);
        $stm->bindValue(':message', "Alexa持ち物確認", PDO::PARAM_STR);
        $stm->bindValue(':datetime', date("Y-m-d H:i:s", $time), PDO::PARAM_STR);
        //SQL文を実行する
        $stm->execute();
    }
    


    

}catch(Exception $e){
    write( "エラーが発生しました
    。");
}

echo "Alexa_send";
?>