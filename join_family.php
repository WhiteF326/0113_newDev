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
        $dbController->registerUserIntoFamily(
            $result, $_SESSION["user_id"], $_POST["name"]
        );
    }
} catch (Exception $e) {
    echo "グループ登録時にエラーが発生しました。";
}
