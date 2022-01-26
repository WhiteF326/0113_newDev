<?php session_start(); ?>

<?php

require 'dbconnect.php';
require 'DBController.php';
$DBControl = new DBController();

try {
    //グループメンバーの登録物を削除する
    $DBControl->deleteItem($_SESSION["f_id"], $_POST["item_id"]);
    echo '<META http-equiv="Refresh" content="0;URL=family_confirm.php?id=',$_SESSION['f_id'], '">';
} catch (Exception $e) {
    echo "エラーが発生しました。";
}
?>
