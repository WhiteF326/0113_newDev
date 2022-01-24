<?php
try {
    //持ち物名を検索する
    $sql = "SELECT name FROM item WHERE id = :id";

    $stm = $pdo->prepare($sql);
    $stm->bindValue(':id', $_POST['item_id'], PDO::PARAM_INT);
    $stm->execute();
    $value = $stm->fetch(PDO::FETCH_COLUMN);
} catch (Exception $e) {
    echo "エラーが発生しました。";
}
