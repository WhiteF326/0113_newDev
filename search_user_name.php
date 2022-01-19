<?php
try {
    //名前を検索する
    $sql = "SELECT name FROM user WHERE id = :id";
    
    $stm = $pdo->prepare($sql);
    $stm->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stm->execute();
    $result = $stm->fetch(PDO::FETCH_COLUMN);
    if (empty($result)) {
        $list = "あなたの登録物一覧";
    } else {
        $list = $result . "さんの登録物一覧";
    }
} catch (Exception $e) {
    $list = "error!";
}
