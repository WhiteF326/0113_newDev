<?php
//いらんくなるかも
try {
    //アイテムが存在するかどうか調べる
    $sql = "SELECT id FROM item WHERE name = :name";

    $stm = $pdo->prepare($sql);
    $stm->bindValue(':name', $contents, PDO::PARAM_STR);
    $stm->execute();
    $result = $stm->fetch(PDO::FETCH_COLUMN);
    if (empty($result)) {
        //未登録の場合、アイテムのIDの最大値を検索
        $sql = "SELECT MAX(id) FROM item";

        $stm = $pdo->prepare($sql);
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_COLUMN);
        if (isset($result)) {
            $result += 1;
        } else {
            $result = 1;
        }

        //itemDBに登録
        $sql = "INSERT INTO item(id,name) VALUES (:id,:name)";

        $stm = $pdo->prepare($sql);
        $stm->bindValue(':id', $result, PDO::PARAM_INT);
        $stm->bindValue(':name', $contents, PDO::PARAM_STR);
        if ($stm->execute()) {
        } else {
            $error[] =  "エラーが発生しました。";
        }
    }
} catch (Exception $e) {
    echo "持ち物登録でエラーが発生しました。";
}
