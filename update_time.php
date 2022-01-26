<?php
require 'dbconnect.php';
require 'DBController.php';
$DBControl = new DBController();

try {
    //入力された時間に変更
    $DBControl->setTime($_SESSION['user_id'], $notice_time, $return_time, $check_time);
    
} catch (Exception $e) {
    $error[] =  "エラーが発生しました。";
}
