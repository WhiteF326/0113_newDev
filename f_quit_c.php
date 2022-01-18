<?php session_start(); ?>

<?php

//MySQLデータベースに接続する
require 'dbconnect.php';
try{

    //SQL文を作る（プレースホルダを使った式）
    $sql ="DELETE FROM comment WHERE family_id = :family_id AND (to_id = :to_id OR from_id = :from_id)";

    //プリペアードステートメントを作る
    $stm = $pdo->prepare($sql);
    //プリペアードステートメントに値をバインドする
    $stm->bindValue(':family_id',$_POST['family_id'],PDO::PARAM_INT);
    $stm->bindValue(':to_id',$_SESSION['user_id'],PDO::PARAM_INT);
    $stm->bindValue(':from_id',$_SESSION['user_id'],PDO::PARAM_INT);

    //SQL文を実行する
    $stm->execute();

    //SQL文を作る（プレースホルダを使った式）
    $sql ="DELETE FROM family_user WHERE family_id = :family_id AND user_id = :user_id";

    //プリペアードステートメントを作る
    $stm = $pdo->prepare($sql);
    //プリペアードステートメントに値をバインドする
    $stm->bindValue(':family_id',$_POST['family_id'],PDO::PARAM_INT);
    $stm->bindValue(':user_id',$_SESSION['user_id'],PDO::PARAM_INT);

    //SQL文を実行する
    if($stm->execute()){
        echo '<META http-equiv="Refresh" content="3;URL=f_top.php"><p>グループから退会しました。<br>グループトップに戻ります。</p>';
    }

}catch(Exception $e){
    echo "エラーが発生しました。";
}
?>
