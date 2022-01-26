<?php
require "dbconnect.php";
$a="";
$week_name = ["sun", "mon", "tue", "wed", "thu", "fri", "sat"];//date("w")でとったときの0-6の対応配列

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
    //現在登録されている名前を検索
    $sql = "SELECT a.name, b.days, b.notice_datetime FROM item a, user_item b, user c WHERE c.Alexa_id = :alexa_id AND b.user_id = c.id AND a.id = b.item_id";
    //プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    //プレースホルダに値をバインドする
    $stm->bindValue(':alexa_id',$alexa_id, PDO::PARAM_STR);
    //SQL文を実行する
    $stm->execute();
    //結果を連想配列で取得
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    //登録済みの場合
    date_default_timezone_set('Asia/Tokyo');
    if(isset($result)){
        $a = "";
        foreach($result as $value){
            if(preg_match("/". $week_name[date('w')]. "/u", $value["days"]) || preg_match("/ALL/u", $value["days"]) ||
                (strtotime(date("Y-m-d 00:00:00")) <= strtotime($value["notice_datetime"]) &&
                (strtotime("+1day",strtotime(date("Y-m-d 00:00:00"))) > strtotime($value["notice_datetime"])))){
                $a .= $value['name'] . "、";
            }
        }
        write($a);
    }
    // //SQL文を作る（プレースホルダを使った式）
    // $sql = "SELECT item.name FROM item, user_item, user 
    // WHERE item.id = user_item.item_id AND user.id = user_item.user_id
    // AND user.Alexa_id = :alexa_id";
    // //LIKE 'amzn1.ask.person.AKJSQKKTKSR2ACJPQM4YQ7IT7GIH2VANC5HXSYKUAZ6ACLSW37WT7E34DDGD3GKJD6VNQBJHWBY4QBMKASVJN2OMIB2UG2EMQ3FY64JC%'";


    // //プリペアードステートメントを作る
    // $stm = $pdo->prepare($sql);
    // //プリペアードステートメントに値をバインドする
    // $stm->bindValue(':alexa_id',$alexa_id,PDO::PARAM_STR);
    // //SQL文を実行する
    // $stm->execute();
    // //結果の取得（連想配列で受け取る）
    // $result = $stm->fetchAll(PDO::FETCH_ASSOC);
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

$itemData='{
    "item1": "'.$a.'"
}';

echo $itemData;
?>