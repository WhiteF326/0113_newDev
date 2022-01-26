<?php
require 'dbconnect.php';
require 'DBController.php';
$DBControl = new DBController();

try {
    //登録されている時間を検索
    $result = $DBControl->getAllTimeByUserId($_SESSION['user_id']);

    $mtime = $result['notice_time'];
    $etime = $result['check_time'];
    $rtime = $result['return_time'];
} catch (Exception $e) {
    $error[] =  "エラーが発生しました。";
}
