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
$id=$contents["id"];
//書き込み
$str="alexa=".$alexa_id;
write($str);



try{
    
    $sql="UPDATE user,alexa_coop SET Alexa_id = :Alexa_id WHERE alexa_coop.pass_id = :id AND alexa_coop.user_id = user.id";
    //プリペアードステートメントを作る
    $stm = $pdo->prepare($sql);
    //プリペアードステートメントに値をバインドする
    $stm->bindValue(':Alexa_id',$alexa_id,PDO::PARAM_STR);
    $stm->bindValue(':id',$id,PDO::PARAM_INT);
    //SQL文を実行する
    $stm->execute();

    $sql="UPDATE alexa_control,alexa_coop SET Alexa_id = :Alexa_id WHERE alexa_coop.pass_id = :id AND alexa_coop.user_id = alexa_control.user_id";
    //プリペアードステートメントを作る
    $stm = $pdo->prepare($sql);
    //プリペアードステートメントに値をバインドする
    $stm->bindValue(':Alexa_id',$alexa_id,PDO::PARAM_STR);
    $stm->bindValue(':id',$id,PDO::PARAM_INT);
    //SQL文を実行する
    $stm->execute();

    if($stm->execute()){
        write("ok");
    }
   //require "write.php";
}catch(Exception $e){
     write( "エラーが発生しました
    。");
}
?>