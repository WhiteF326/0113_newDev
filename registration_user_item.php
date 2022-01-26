<?php
require 'dbconnect.php';
require 'DBController.php';
$DBControl = new DBController();

try {
    //持ち物を登録する
    $DBControl->registerItem($user_id, $contents, $days, $_POST['datetime']);
    /*
    if ($stm->execute()) {
    } else {
        echo "登録できませんでした。もう一度お試しください。";
    };
    */
} catch (Exception $e) {
    echo "エラーが発生しました。";
}
