<?php

require 'dbconnect.php';
require 'DBController.php';
$DBControl = new DBController();

try {
    //名前を検索する
    $result = $DBControl->getUserNameFromId($_SESSION["user_id"]);
    if (empty($result)) {
        $list = "あなたの登録物一覧";
    } else {
        $list = $result . "さんの登録物一覧";
    }
} catch (Exception $e) {
    $list = "error!";
}
