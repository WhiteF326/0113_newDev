<?php session_start(); ?>

<?php
require 'dbconnect.php';
require 'DBController.php';
$DBControl = new DBController();

try {
    //退会するユーザーが登録したメッセージとグループ情報をすべて削除する
    $DBControl->familyWithdrawal($_POST["family_id"], $_SESSION["user_id"]);
    echo '<META http-equiv="Refresh" content="3;URL=family_top.php"><p>グループから退会しました。<br>グループトップに戻ります。</p>';

} catch (Exception $e) {
    echo "エラーが発生しました。";
}
?>
