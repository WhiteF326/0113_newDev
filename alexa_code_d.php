<?php

// POSTされたJSON文字列を取り出し
$json = file_get_contents("php://input");

// JSON文字列をobjectに変換
//   ⇒ 第2引数をtrueにしないとハマるので注意
$contents = json_decode($json, true);


$alexa_id=$contents["name"];

require 'dbconnect.php';
try{

    //SQL文を作る（プレースホルダを使った式）
    $sql ="SELECT id FROM user WHERE Alexa_id = :Alexa_id";

    //プリペアードステートメントを作る
    $stm = $pdo->prepare($sql);
    //プリペアードステートメントに値をバインドする
    $stm->bindValue(':Alexa_id',$alexa_id,PDO::PARAM_STR);

    //SQL文を実行する
    $stm->execute();
    $result = $stm->fetch(PDO::FETCH_COLUMN);

    //SQL文を作る（プレースホルダを使った式）
    $sql ="DELETE FROM Alexa_coop WHERE user_id = :user_id";

    //プリペアードステートメントを作る
    $stm = $pdo->prepare($sql);
    //プリペアードステートメントに値をバインドする
    $stm->bindValue(':user_id',$result,PDO::PARAM_INT);

    //SQL文を実行する
    $stm->execute();

}catch(Exception $e){

}
?>
