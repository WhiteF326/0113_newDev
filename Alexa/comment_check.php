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

// デバッグ用にダンプ
$alexa_id=$contents["name"];
//書き込み
//$alexa_id="amzn1.ask.person.ALVQI5F6NHYHCSPHCGSGZIMK2WY2PRSR4G6NVYYEKF7DXKPJDBVWGSUGFN4IQLUJP3TJJ6ZZVBBTYYPFWRGBX7M7MJJJFIYJDPNJXS6A";
$str="alexa=".$alexa_id;
write($str);


try{
    //$alexa_id=$_POST;
    //SQL文を作る（プレースホルダを使った式）
    $sql='SELECT count(comment) FROM comment a,user b WHERE a.to_id=b.id AND b.Alexa_id=:Alexa_id';
    //プリペアードステートメントを作る
    $stm = $pdo->prepare($sql);
    //プリペアードステートメントに値をバインドする
    $stm->bindValue(':Alexa_id',$alexa_id,PDO::PARAM_STR);
    //SQL文を実行する
    $stm->execute();
    //結果の取得（連想配列で受け取る）
    $result = $stm->fetch(PDO::FETCH_COLUMN);
    // if($result){
    //     foreach($result as $row){
    //         $a=$row["name"];
    //         $b=$row["comment"];
    //     }
            
    // }
    // if($stm->execute()){
    //     write("ok");
    // }
   //require "write.php";
}catch(Exception $e){
     write( "エラーが発生しました
    。");
}



$flag='{
    "comment_flag": "'.$result.'"
}';

echo $flag;
?>