<?php
ini_set("display_errors", 1);
$req = json_decode(file_get_contents("php://input"), true);
var_dump($req);

$user_id = $req['user_id'];
$item_id = $req['item_id'];

require 'dbconnect.php';
try{
    $sql ="DELETE FROM user_item
        WHERE user_id = :user_id AND item_id = :item_id";

    $stm = $pdo->prepare($sql);
    $stm->bindValue(':user_id',$user_id,PDO::PARAM_INT);
    $stm->bindValue(':item_id',$item_id,PDO::PARAM_INT);

    $stm->execute();
}catch(Exception $e){
    echo "エラーが発生しました。";
}
?>