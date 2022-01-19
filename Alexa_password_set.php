<?php session_start(); ?>

<?php
require 'dbconnect.php';
try {
    //既に使用されているパスワードがないか検索する
    $sql = "SELECT pass_id FROM Alexa_coop
    WHERE pass_id = :pass_id";

    $stm = $pdo->prepare($sql);
    $stm->bindValue(':pass_id', $_POST['pass_id'], PDO::PARAM_INT);
    $stm->execute();
    $result = $stm->fetch(PDO::FETCH_COLUMN);
    if ($result == true) : ?>
        <p>このコードは使えません。別のコードを設定してください。</p>
        <META http-equiv="Refresh" content="3;URL=alexa_cooperation.php">
<?php
    else :
        //Alexaで連携するパスワードを登録する
        $sql = "INSERT INTO Alexa_coop(user_id,pass_id)
        VALUES(:user_id,:pass_id)";

        $stm = $pdo->prepare($sql);
        $stm->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stm->bindValue(':pass_id', $_POST['pass_id'], PDO::PARAM_INT);
        if ($stm->execute()) :?>
            <META http-equiv="Refresh" content="0;URL=alexa_cooperation.php">
        <?php
        endif;
    endif;
} catch (Exception $e) {
    echo "エラーが発生しました。";
    echo '<META http-equiv="Refresh" content="3;URL=alexa_cooperation.php">';
}
?>