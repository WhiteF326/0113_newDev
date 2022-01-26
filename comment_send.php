<?php session_start(); ?>
<script>
    window.onload = () => {
        if (window.location.href.endsWith("comment_send.php")) {
            window.location.href = "LINE_registration.php";
        }
    };
</script>

<?php
require 'dbconnect.php';
try {
    //メッセージが設定されていなければ新しくカラムを登録する、設定されていればカラムを更新する
    $sql = "INSERT INTO comment(family_id,from_id,to_id,comment)
        VALUES (:family_id,:from_id,:to_id,:comment)
        ON DUPLICATE KEY UPDATE comment = :comment2, alert = :alert";

    $stm = $pdo->prepare($sql);
    $stm->bindValue(':family_id', $_POST['family_id'], PDO::PARAM_INT);
    $stm->bindValue(':from_id', $_POST['from_id'], PDO::PARAM_INT);
    $stm->bindValue(':to_id', $_POST['to_id'], PDO::PARAM_INT);
    if (empty($_POST['comment'])) {
        //コメントが登録されていなければ通知をオフにする
        $stm->bindValue(':comment', null, PDO::PARAM_NULL);
        $stm->bindValue(':comment2', null, PDO::PARAM_NULL);
        $stm->bindValue(':alert', 0, PDO::PARAM_INT);
    } else {
        //コメントが設定されていれば通知をオンにする
        $stm->bindValue(':comment', $_POST['comment'], PDO::PARAM_STR);
        $stm->bindValue(':comment2', $_POST['comment'], PDO::PARAM_STR);
        $stm->bindValue(':alert', 1, PDO::PARAM_INT);
    }
    if ($stm->execute()) {
        echo '<META http-equiv="Refresh" content="0;URL=family_top.php">';
    } else {
        echo '<META http-equiv="Refresh" content="3;URL=family_top.php">';
    }
} catch (Exception $e) {
    echo "エラーが発生しました。";
}

?>