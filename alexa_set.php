<?php session_start(); ?>

<?php

require 'dbconnect.php';
try{
    //SQL文を作る（プレースホルダを使った式）
    $sql ="SELECT pass_id FROM Alexa_coop WHERE pass_id = :pass_id";
    //プリペアードステートメントを作る
    $stm = $pdo->prepare($sql);
    //プリペアードステートメントに値をバインドする
    $stm->bindValue(':pass_id',$_POST['pass_id'],PDO::PARAM_INT);
    $stm->execute();
    $result = $stm->fetch(PDO::FETCH_COLUMN);
    if($result == true){
        echo "このコードは使えません。別のコードを設定してください。";
        echo '<META http-equiv="Refresh" content="3;URL=alexa_r.php">';
    } else {

        //SQL文を作る（プレースホルダを使った式）
        $sql ="INSERT INTO Alexa_coop(user_id,pass_id) VALUES(:user_id,:pass_id)";

        //プリペアードステートメントを作る
        $stm = $pdo->prepare($sql);
        //プリペアードステートメントに値をバインドする
        $stm->bindValue(':user_id',$_SESSION['user_id'],PDO::PARAM_INT);
        $stm->bindValue(':pass_id',$_POST['pass_id'],PDO::PARAM_INT);

        //SQL文を実行する
        if($stm->execute()){
            echo '<META http-equiv="Refresh" content="0;URL=alexa_r.php">';
        }
    }

}catch(Exception $e){
    echo "エラーが発生しました。";
    echo '<META http-equiv="Refresh" content="3;URL=alexa_r.php">';
}
?>
