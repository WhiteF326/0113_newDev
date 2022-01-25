<?php session_start(); ?>

<?php
require 'check_error.php';
//commentをチェックする
if (!check_error($_POST["comment"])) : ?>
    <p>特殊な文字は使用しないでください。</p>
    <META http-equiv="Refresh" content="0;URL=family_top.php">
<?php elseif($_POST["comment"]) :
    $alert_value = true;
else :
    $alert_value = false;
endif;

require 'dbconnect.php';
require 'DBController.php';
$DBControl = new DBController();

try {
    //メッセージが設定されていなければ新しくカラムを登録する、設定されていればカラムを更新する
    $DBControl->setComment(
        $_POST["family_id"],
        $_POST["from_id"],
        $_POST["to_id"],
        $_POST["comment"],
        $alert_value
    );
    ?>
    <META http-equiv="Refresh" content="0;URL=family_top.php">
<?php
} catch (Exception $e) {
    echo "エラーが発生しました。";
}

?>