<?php
require "dbconnect.php";

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


write($alexa_id);



try{
    //SQL文を作る（プレースホルダを使った式）
    $sql = "SELECT Alexa_check FROM user WHERE Alexa_id = :alexa_id";
    //LIKE 'amzn1.ask.person.AKJSQKKTKSR2ACJPQM4YQ7IT7GIH2VANC5HXSYKUAZ6ACLSW37WT7E34DDGD3GKJD6VNQBJHWBY4QBMKASVJN2OMIB2UG2EMQ3FY64JC%'";


    //プリペアードステートメントを作る
    $stm = $pdo->prepare($sql);
    //プリペアードステートメントに値をバインドする
    $stm->bindValue(':alexa_id',$alexa_id,PDO::PARAM_STR);
    //SQL文を実行する
    $stm->execute();
    //結果の取得（連想配列で受け取る）
    $result = $stm->fetch(PDO::FETCH_COLUMN);
    if(isset($result)){
        write($result);
    }

}catch(Exception $e){
    //write("error");
}

$Data='{
    "flag": "'.$result.'"
}';

echo $Data;
?>