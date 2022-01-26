<?php
try {
    //グループ名を検索する
    $sql = "SELECT user_name FROM family_user
    WHERE family_id = :family_id AND user_id = :user_id";

    $stm = $pdo->prepare($sql);
    $stm->bindValue(':family_id', $_POST['family_id'], PDO::PARAM_INT);
    $stm->execute();
    $result = $stm->fetch(PDO::FETCH_COLUMN);
    if (empty($result)) {
    } else {
        $family_name = "[" . $result . "]";
    }
} catch (Exception $e) {
    echo "エラーが発生しました。";
}
