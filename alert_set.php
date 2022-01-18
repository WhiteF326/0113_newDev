<?php session_start(); ?>

<?php

require 'dbconnect.php';
try {
    //グループメンバーが持ち物の確認をしていないときに通知するかの設定を更新する
    $sql = "UPDATE comment 
        SET alert = :alert 
        WHERE family_id = :family_id 
        AND from_id = :from_id 
        AND to_id = :to_id";

    $stm = $pdo->prepare($sql);
    $stm->bindValue(':family_id', $_POST['family_id'], PDO::PARAM_INT);
    $stm->bindValue(':from_id', $_POST['from_id'], PDO::PARAM_INT);
    $stm->bindValue(':to_id', $_POST['to_id'], PDO::PARAM_INT);
    $stm->bindValue(':alert', $_POST['alert'], PDO::PARAM_INT);

    if ($stm->execute()) : ?>
        <META http-equiv="Refresh" content="0;URL=family_top.php">
    <?php
    else : ?>
        <META http-equiv="Refresh" content="3;URL=family_top.php">
<?php
    endif;
} catch (Exception $e) {
    echo "エラーが発生しました。";
}

?>