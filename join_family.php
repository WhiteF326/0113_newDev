<?php
require 'dbconnect.php';
require 'DBController.php';
$DBControl = new DBController();

try {
    //グループを検索する
    $result = $DBControl->searchFamily($_POST['entry_name'], $_POST['entry_pass']);

    if (empty($result)) {
        echo "グループ名またはパスワードが間違っています。";
    } else {
        //グループに加入する
        $DBControl->registerUserIntoFamily($result, $_SESSION["user_id"], $_POST['name']);
    }
} catch (Exception $e) {
    echo "グループ登録時にエラーが発生しました。";
}
