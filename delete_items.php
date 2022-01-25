<?php session_start(); ?>

<?php

require 'dbconnect.php';
require 'DBController.php';
$DBControl = new DBController();

try{
    //持ち物の登録を解除する
    $DBControl->deleteItem($_SESSION["user_id"], $_POST["item_id"]);
    
    echo '<META http-equiv="Refresh" content="0;URL=confirm.php?id=', $_SESSION["user_id"], '">';
} catch (Exception $e) {
    echo "エラーが発生しました。";
}
?>