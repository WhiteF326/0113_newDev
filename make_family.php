
<?php
require 'dbconnect.php';
require 'DBController.php';
$DBControl = new DBController();

try {
    //グループを作成する
    $DBControl->registerFamily($_POST['make_name'], $_POST['make_pass']);

    //グループに加入する
    $family_id = $DBControl->searchFamily($_POST['make_name'], $_POST['make_pass']);
    $DBControl->registerUserIntoFamily($family_id, $_SESSION["user_id"], $_POST['name']);
} catch (Exception $e) {
    echo "そのパスワードは使用できません。別のパスワードで登録してください。";
}
