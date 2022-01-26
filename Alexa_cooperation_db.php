<script>
    window.onload = () => {
        if (window.location.href.endsWith("Alexa_cooperation_db.php")) {
            window.location.href = "LINE_registration.php";
        }
    };
</script>
<?php
try {
    //ユーザーがAlexaと連携しているかどうか検索する
    $sql = "SELECT Alexa_id FROM user
    WHERE id = :id";

    $stm = $pdo->prepare($sql);
    $stm->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stm->execute();
    $result = $stm->fetch(PDO::FETCH_COLUMN);
    if ($result == NULL) : ?>

        <form action="Alexa_password_set.php" method="post">
            <input type="number" name="pass_id" placeholder="6桁の数字を入力してください。" min="100000" max="999999" required>
            <input type="submit" title="設定を完了します。" value="設定">
        </form>

        <?php
        //既に設定されているパスワードがないか検索する
        $sql = "SELECT pass_id FROM Alexa_coop
        WHERE user_id = :user_id";

        $stm = $pdo->prepare($sql);
        $stm->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_COLUMN);
        if (!$result) {
            $result = "現在設定されている番号はありません。";
        }
        ?>

        <p>現在設定されている番号 : <?= $result ?></p>
    <?php
    else : ?>
        <p>既にAlexaと連携しています。</p>
<?php
    endif;
} catch (Exception $e) {
    echo "エラーが発生しました。";
}
?>