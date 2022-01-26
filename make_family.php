<?php
require 'check_error.php';
//グループ名、パスワード、名前をチェックする
if (
    !check_error($_POST['make_name']) ||
    !check_error($_POST['make_pass']) ||
    !check_error($_POST['name'])
) : ?>
    <p>特殊な文字、文字コードを入力しないでください。</p>
    <META http-equiv="Refresh" content="0;URL=family_make.php">
<?php endif;

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
