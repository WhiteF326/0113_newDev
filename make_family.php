
<?php
try {
    //グループのIDの最大値を検索
    //AIすればまじでいらんよねこれ
    $sql = "SELECT MAX(id) FROM family";

    $stm = $pdo->prepare($sql);
    $stm->execute();
    $result = $stm->fetch(PDO::FETCH_COLUMN);
    if (isset($result)) {
        $result += 1;
    } else {
        $result = 1;
    }

    //グループを作成する
    $sql = "INSERT INTO family(id,name,pass)
    VALUES (:id,:name,:pass)";

    $stm = $pdo->prepare($sql);
    $stm->bindValue(':id', $result, PDO::PARAM_INT);
    $stm->bindValue(':name', $_POST['make_name'], PDO::PARAM_STR);
    $stm->bindValue(':pass', $_POST['make_pass'], PDO::PARAM_STR);

    if ($stm->execute()) {
        $dbController->registerUserIntoFamily(
            $result, $_SESSION["user_id"], $_POST["name"]
        );
    } else {
        echo "グループ作成時にエラーが発生しました。";
    }
} catch (Exception $e) {
    echo "そのパスワードは使用できません。別のパスワードで登録してください。";
}
