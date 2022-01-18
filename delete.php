<?php session_start(); ?>

<?php
//MySQLデータベースに接続する
$user_id = $_SESSION['user_id'];
$item_id =$_POST['item_id'];

require 'dbconect.php';
try{
    //SQL文を作る（プレースホルダを使った式）
    $sql ="DELETE FROM user_item WHERE user_id = :user_id AND item_id = :item_id";

    //プリペアードステートメントを作る
    $stm = $pdo->prepare($sql);
    //プリペアードステートメントに値をバインドする

    $stm->bindValue(':user_id',$user_id,PDO::PARAM_INT);
    $stm->bindValue(':item_id',$item_id,PDO::PARAM_INT);

    //SQL文を実行する
    $stm->execute();
    if($stm->execute()){
        echo '<META http-equiv="Refresh" content="0;URL=confrim.php?id=',$user_id,'">';
    }

}catch(Exception $e){
    echo "エラーが発生しました
    。";
}
?>