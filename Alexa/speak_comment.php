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
$Alexa_id=$contents["name"];
//書き込み
//$Alexa_id="amzn1.ask.person.ALVQI5F6NHYHCSPHCGSGZIMK2WY2PRSR4G6NVYYEKF7DXKPJDBVWGSUGFN4IQLUJP3TJJ6ZZVBBTYYPFWRGBX7M7MJJJFIYJDPNJXS6A";
$str="Alexa=".$Alexa_id;
write($str);


try{
    //$Alexa_id=$_POST;
    //SQL文を作る（プレースホルダを使った式）
    $sql='SELECT b.name, comment FROM (SELECT from_id, to_id, comment, name, id, Alexa_id  FROM comment ,user  WHERE Alexa_id=:Alexa_id AND to_id=id) a,user b WHERE a.from_id=b.id AND comment != ""';
    //プリペアードステートメントを作る
    $stm = $pdo->prepare($sql);
    //プリペアードステートメントに値をバインドする
    $stm->bindValue(':Alexa_id',$Alexa_id,PDO::PARAM_STR);
    //SQL文を実行する
    $stm->execute();
    //結果の取得（連想配列で受け取る）
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    if($result){
        foreach($result as $row){
            $a.=$row["name"]."さん、";
            $b.=$row["comment"]."、";
        }
            
    }
    if($stm->execute()){
        write("ok");
    }
   //require "write.php";
}catch(Exception $e){
     write( "エラーが発生しました
    。");
}



$value='{
    "name": "'.$a.'",
    "comment": "'.$b.'"
}';

echo $value;
?>