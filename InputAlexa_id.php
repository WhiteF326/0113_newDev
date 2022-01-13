<?php
require "dbconect.php";

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
    //$alexa_id=$_POST;
    //SQL文を作る（プレースホルダを使った式）
    //$sql = "UPDATE user SET Alexa_id=:Alexa_id WHERE Alexa_coop.pass_id = :id AND ";
    $sql="UPDATE user,Alexa_coop SET Alexa_id = :Alexa_id WHERE Alexa_coop.pass_id = :id AND Alexa_coop.user_id = user.id";
    //プリペアードステートメントを作る
    $stm = $pdo->prepare($sql);
    //プリペアードステートメントに値をバインドする
    $stm->bindValue(':Alexa_id',$alexa_id,PDO::PARAM_STR);
    $stm->bindValue(':id',$id,PDO::PARAM_INT);
    //SQL文を実行する
    $stm->execute();
    //結果の取得（連想配列で受け取る）
    // $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    // if($result){
    //     foreach($result as $row){
    //         echo $row['name'];
    //     }
    // }
    if($stm->execute()){
        write("ok");
    }
   //require "write.php";
}catch(Exception $e){
     write( "エラーが発生しました
    。");
}
?>