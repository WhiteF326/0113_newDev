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
        $error = "グループ名またはパスワードが間違っています。";
    } else {

        //グループに加入する
        $sql = "INSERT INTO family_user(family_id, user_id, user_name)
        VALUES (:family_id, :user_id, :user_name)";

        $stm = $pdo->prepare($sql);
        $stm->bindValue(':family_id', $result, PDO::PARAM_INT);
        $stm->bindValue(':user_id', $_SESSION["user_id"], PDO::PARAM_INT);
        $stm->bindValue(":user_name", $_POST["user_name"], PDO::PARAM_STR);

        $stm->execute();
    }
} catch (Exception $e) {
    echo $e;
    $error = "グループ参加時にエラーが発生しました。\n
    そのグループに既に参加している可能性があります。";
} finally {
    if ($error) {
        require "family_entry.php";
        exit;
    }
}
