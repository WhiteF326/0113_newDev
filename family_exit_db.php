<?php session_start(); ?>

<?php

require 'dbconnect.php';
try {

    //退会するユーザーが登録したメッセージをすべて削除する
    $sql = "DELETE FROM comment 
    WHERE family_id = :family_id 
    AND (to_id = :to_id OR from_id = :from_id)";

    $stm = $pdo->prepare($sql);
    $stm->bindValue(':family_id', $_POST['family_id'], PDO::PARAM_INT);
    $stm->bindValue(':to_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stm->bindValue(':from_id', $_SESSION['user_id'], PDO::PARAM_INT);

    $stm->execute();

    ///退会するユーザーのグループ情報を削除する
    $sql = "DELETE FROM family_user 
    WHERE family_id = :family_id 
    AND user_id = :user_id";

    $stm = $pdo->prepare($sql);
    $stm->bindValue(':family_id', $_POST['family_id'], PDO::PARAM_INT);
    $stm->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

    if ($stm->execute()) {
        echo '<META http-equiv="Refresh" content="3;URL=family_top.php"><p>グループから退会しました。<br>グループトップに戻ります。</p>';
    }
} catch (Exception $e) {
    echo "エラーが発生しました。";
}
?>
