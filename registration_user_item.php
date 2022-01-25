<?php
try {
    //持ち物を登録する
    $sql = "INSERT INTO user_item(user_id, item_id, days, notice_datetime)
    VALUES (:user_id, :item_id, :days,:notice_datetime)
    ON DUPLICATE KEY UPDATE days = :days2, notice_datetime = :notice_datetime2";

    $stm = $pdo->prepare($sql);
    $stm->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stm->bindValue(':item_id', $contents/*$result*/, PDO::PARAM_INT);
    if (!empty($_POST['days'])) {
        $stm->bindValue(':days', $days, PDO::PARAM_STR);
        $stm->bindValue(':days2', $days, PDO::PARAM_STR);
        $stm->bindValue(':notice_datetime', NULL, PDO::PARAM_NULL);
        $stm->bindValue(':notice_datetime2', NULL, PDO::PARAM_NULL);
    } else {
        $stm->bindValue(':days', NULL, PDO::PARAM_NULL);
        $stm->bindValue(':days2', NULL, PDO::PARAM_NULL);
        $stm->bindValue(':notice_datetime', $_POST['datetime'], PDO::PARAM_STR);
        $stm->bindValue(':notice_datetime2', $_POST['datetime'], PDO::PARAM_STR);
    }
    /*
    if (!empty($_POST['datetime'])) {
        $stm->bindValue(':notice_datetime', $_POST['datetime'], PDO::PARAM_STR);
        $stm->bindValue(':notice_datetime2', $_POST['datetime'], PDO::PARAM_STR);
    } else {
        $stm->bindValue(':notice_datetime', NULL, PDO::PARAM_NULL);
        $stm->bindValue(':notice_datetime2', NULL, PDO::PARAM_NULL);
    }
    */
    if ($stm->execute()) {
    } else {
        echo "登録できませんでした。もう一度お試しください。";
    };
} catch (Exception $e) {
    echo "エラーが発生しました。";
}
