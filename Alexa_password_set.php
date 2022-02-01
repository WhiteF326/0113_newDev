<?php session_start(); ?>

<?php

require 'dbconnect.php';
try{
    //SQL文を作る（プレースホルダを使った式）
    $sql ="SELECT pass_id FROM alexa_coop WHERE pass_id = :pass_id";
    //プリペアードステートメントを作る
    $stm = $pdo->prepare($sql);
    //プリペアードステートメントに値をバインドする
    $stm->bindValue(':pass_id',$_POST['pass_id'],PDO::PARAM_INT);
    $stm->execute();
    $result = $stm->fetch(PDO::FETCH_COLUMN);
    if($result == true){
        $error = "このコードは使えません。別のコードを設定してください。";
        //echo '<META http-equiv="Refresh" content="3;URL=Alexa_cooperation.php">';
    } else {

        //SQL文を作る（プレースホルダを使った式）
        $sql ="INSERT INTO alexa_coop(user_id,pass_id) VALUES(:user_id,:pass_id)";

        //プリペアードステートメントを作る
        $stm = $pdo->prepare($sql);
        //プリペアードステートメントに値をバインドする
        $stm->bindValue(':user_id',$_SESSION['user_id'],PDO::PARAM_INT);
        $stm->bindValue(':pass_id',$_POST['pass_id'],PDO::PARAM_INT);
        $stm->execute();


        // $sql ="SELECT * FROM alexa_control WHERE user_id = :user_id";

        // //プリペアードステートメントを作る
        // $stm = $pdo->prepare($sql);
        // //プリペアードステートメントに値をバインドする
        // $stm->bindValue(':user_id',$_SESSION['user_id'],PDO::PARAM_INT);

        // if(!$stm->execute()){
            $sql ="INSERT INTO alexa_control(user_id) VALUES(:user_id)";

            //プリペアードステートメントを作る
            $stm = $pdo->prepare($sql);
            //プリペアードステートメントに値をバインドする
            $stm->bindValue(':user_id',$_SESSION['user_id'],PDO::PARAM_INT);
        // }


        //SQL文を実行する
        if($stm->execute()){
            echo '<META http-equiv="Refresh" content="0;URL=Alexa_cooperation.php">';
        }
    }

} catch (Exception $e) {
    $error = "不明なエラーが発生しました、再度お試しください。";
    //echo '<META http-equiv="Refresh" content="3;URL=Alexa_cooperation.php">';
} finally {
    if ($error) {
        require 'Alexa_error.php';
        exit;
    }
}?>
