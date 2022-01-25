<?php session_start(); ?>

<?php

require 'DBController.php';
$DBControl = new DBController();

try {
    //グループメンバーが持ち物の確認をしていないときに通知するかの設定を更新
    $DBControl->setAlertFlag(
        $_POST['alert'],
        $_POST['family_id'],
        $_POST['from_id'],
        $_POST['to_id']
    );
?>
    <META http-equiv="Refresh" content="0;URL=family_top.php">
<?php
} catch (Exception $e) {
    echo "エラーが発生しました。";
}
?>