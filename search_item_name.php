<?php
require 'dbconnect.php';
require 'DBController.php';
$DBControl = new DBController();

try {
    //持ち物名を検索する
    $value = $DBControl->searchItemName($_POST['item_id']);

} catch (Exception $e) {
    echo "エラーが発生しました。";
}
