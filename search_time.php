<?php
try {
    //登録されている時間を検索
    $sql = "SELECT notice_time, return_time, check_time
    FROM user
    WHERE id = :id";

    $stm = $pdo->prepare($sql);
    $stm->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stm->execute();
    $result = $stm->fetch(PDO::FETCH_ASSOC);

    $mtime = $result['notice_time'];
    $etime = $result['check_time'];
    $rtime = $result['return_time'];
} catch (Exception $e) {
    $error[] =  "エラーが発生しました。";
}
