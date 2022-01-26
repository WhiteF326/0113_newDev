<?php
require 'check_error.php';
//グループ名、パスワード、名前をチェックする
if (
    !check_error($_POST['entry_name']) ||
    !check_error($_POST['entry_pass']) ||
    !check_error($_POST['name'])
) : ?>
    <p>特殊な文字、文字コードを入力しないでください。</p>
    <META http-equiv="Refresh" content="0;URL=family_entry.php">
<?php endif;

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
