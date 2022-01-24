<?php
try {
    //入力された時間に変更
    $sql = "UPDATE user
    SET notice_time = :notice_time, return_time = :return_time, check_time = :check_time
    WHERE id = :id";

    $stm = $pdo->prepare($sql);
    $stm->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stm->bindValue(':notice_time', $notice_time, PDO::PARAM_INT);
    if ($return_time == 000000) {
        $stm->bindValue(':return_time', null, PDO::PARAM_NULL);
    } else {
        $stm->bindValue(':return_time', $return_time, PDO::PARAM_INT);
    }
    $stm->bindValue(':check_time', $check_time, PDO::PARAM_INT);
    if ($stm->execute()) {
    } else {
        echo "時間登録でエラーが発生しました。";
    }
} catch (Exception $e) {
    $error[] =  "エラーが発生しました。";
}
