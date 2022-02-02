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


$id=$contents["id"];

write($id);

// $id=123456;
//echo $id;

try{
    //SQL文を作る（プレースホルダを使った式）
    $sql = "SELECT count(*) FROM alexa_coop WHERE pass_id = :id";
   

    //プリペアードステートメントを作る
    $stm = $pdo->prepare($sql);
    //プリペアードステートメントに値をバインドする
    $stm->bindValue(':id',(int)$id,PDO::PARAM_INT);
    //SQL文を実行する
    $stm->execute();
    //結果の取得（連想配列で受け取る）
    $result = $stm->fetch(PDO::FETCH_COLUMN);
    // if(isset($result)){
    //     $a = "";
    //     foreach($result as $row){
           
    //         $a.=$row["name"]."、";
    //     }
    //     write($a);
    // }

}catch(Exception $e){
    //write("error");
}

$Number='{
    "number": "'.$result.'"
}';

echo $Number;
?>