<?php session_start(); ?>

<?php

    require 'dbconnect.php';
    try{
        $sql = "UPDATE comment SET alert = :alert WHERE family_id = :family_id AND from_id = :from_id AND to_id = :to_id";
        //プリペアードステートメントを作る
        $stm = $pdo->prepare($sql);
        //プリペアードステートメントに値をバインドする
        $stm->bindValue(':family_id',$_POST['family_id'],PDO::PARAM_INT);
        $stm->bindValue(':from_id',$_POST['from_id'],PDO::PARAM_INT);
        $stm->bindValue(':to_id',$_POST['to_id'],PDO::PARAM_INT);
        $stm->bindValue(':alert',$_POST['alert'],PDO::PARAM_INT);

        if($stm->execute()){
            echo '<META http-equiv="Refresh" content="0;URL=f_top.php">';
        }else{
            echo '<META http-equiv="Refresh" content="3;URL=f_top.php">';
        }
    }catch(Exception $e){
        echo "エラーが発生しました。";
    }
              
?>