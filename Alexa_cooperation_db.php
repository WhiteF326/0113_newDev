<?php

require 'DBController.php';
$DBControl = new DBController();

try {
    //ユーザーがAlexaと連携しているかどうか検索
    if ($DBControl->alexaCooperationCheck($_SESSION["user_id"])) : ?>
        <form action="alexa_password_set.php" method="post">
            <input type="number" name="pass_id" placeholder="6桁の数字を入力してください。" min="100000" max="999999" required>
            <input type="submit" title="設定を完了します。" value="設定">
        </form>

        <?php
        $password = $DBControl->getAlexaPassword($_SESSION["user_id"]);

        if ($password) : ?>
            <p>現在設定されている番号 : <?= $$password ?></p>
        <?php else : ?>
            <p> 現在設定されている番号はありません。</p>
        <?php endif;

    else : ?>
        <p>既にAlexaと連携しています。</p>
<?php endif;
} catch (Exception $e) {
    echo "エラーが発生しました。";
}
?>