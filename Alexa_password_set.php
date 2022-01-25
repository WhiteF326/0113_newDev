<?php session_start(); ?>

<?php
require 'check_error.php';
//pass_idをチェックする
if (!check_error($_POST["pass_id"])) : ?>
    <p>6桁の数字を入力してください。</p>
    <META http-equiv="Refresh" content="0;URL=alexa_cooperation.php">
    <?php endif;
require 'dbconnect.php';
require 'DBController.php';
$DBControl = new DBController();

try {
    //既に使用されているパスワードがないか検索
    if ($DBControl->alexaPasswordUniqueCheck($_POST["pass_id"])) : ?>
        <p>このコードは使えません。別のコードを設定してください。</p>
        <META http-equiv="Refresh" content="3;URL=alexa_cooperation.php">
    <?php
    else :
        //既にパスワードが使用されていない場合、パスワードを登録する
        $DBControl->addAlexaCooperation(
            $_SESSION["user_id"],
            $_POST["pass_id"]
        ); ?>
        <META http-equiv="Refresh" content="0;URL=alexa_cooperation.php">
<?php endif;
} catch (Exception $e) {
    echo "エラーが発生しました。";
    echo '<META http-equiv="Refresh" content="3;URL=alexa_cooperation.php">';
}
?>