<?php
try {
    //グループを検索する
    $sql = "SELECT DISTINCT id from family
    WHERE name = :name AND pass = :pass";

    $stm = $pdo->prepare($sql);
    $stm->bindValue(':name', $_POST['entry_name'], PDO::PARAM_STR);
    $stm->bindValue(':pass', $_POST['entry_pass'], PDO::PARAM_STR);
    $stm->execute();
    $result = $stm->fetch(PDO::FETCH_COLUMN);
    if (empty($result)) {
        echo "グループ名またはパスワードが間違っています。";
    } else {

        //グループに加入する
        $sql = "INSERT INTO family_user(family_id, user_id)
        VALUES (:family_id,:user_id)";

        $stm = $pdo->prepare($sql);
        $stm->bindValue(':family_id', $result, PDO::PARAM_INT);
        $stm->bindValue(':user_id', $_SESSION["user_id"], PDO::PARAM_INT);
        if ($stm->execute()) {
            //ユーザー情報に名前を登録する
            $sql = "UPDATE user SET name = :name
            WHERE id = :id";

            $stm = $pdo->prepare($sql);
            $stm->bindValue(':name', $_POST['name'], PDO::PARAM_STR);
            $stm->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
            if ($stm->execute()) {
            } else {
                echo "ユーザーの名前が正常に登録されませんでした。";
            }
        } else {
            echo "グループ登録時にエラーが発生しました。";
        }
    }
} catch (Exception $e) {
    echo "グループ登録時にエラーが発生しました。";
}