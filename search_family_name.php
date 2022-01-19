<?php
try {
    //グループ名を検索する
    $sql = "SELECT name FROM family WHERE id = :id";

    $stm = $pdo->prepare($sql);
    $stm->bindValue(':id', $_POST['family_id'], PDO::PARAM_INT);
    $stm->execute();
    $result = $stm->fetch(PDO::FETCH_COLUMN);
    if (empty($result)) {
    } else {
        $family_name = "[" . $result . "]";
    }
} catch (Exception $e) {
    echo "エラーが発生思案した。";
}
