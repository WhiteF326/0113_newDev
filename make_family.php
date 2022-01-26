
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
    $sql = "INSERT INTO family(id,name,pass,salt)
    VALUES (:id,:name,:pass,'')";

    $stm = $pdo->prepare($sql);
    $stm->bindValue(':id', $result, PDO::PARAM_INT);
    $stm->bindValue(':name', $_POST['make_name'], PDO::PARAM_STR);
    $stm->bindValue(':pass', $_POST["make_pass"], PDO::PARAM_STR);

    if ($stm->execute()) {
        //グループに加入する
        $sql = "INSERT INTO family_user(family_id, user_id, user_name)
        VALUES (:family_id, :user_id, :user_name)";

        $stm = $pdo->prepare($sql);
        $stm->bindValue(':family_id', $result, PDO::PARAM_INT);
        $stm->bindValue(':user_id', $_SESSION["user_id"], PDO::PARAM_INT);
        $stm->bindValue(":user_name", $_POST["user_name"], PDO::PARAM_STR);

        if ($stm->execute()) {
        } else {
            $error = "グループが正常に作成できませんでした。";
        }

        //ユーザー情報に名前を登録する
        $sql = "UPDATE user SET name = :name
        WHERE id = :id";

        $stm = $pdo->prepare($sql);
        $stm->bindValue(':name', $_POST['name'], PDO::PARAM_STR);
        $stm->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
        if ($stm->execute()) {
        } else {
            $error = "ユーザーの名前が正常に登録されませんでした。";
        }
    } else {
        $error = "グループ作成時にエラーが発生しました。";
    }
} catch (Exception $e) {
    $error = "そのパスワードは使用できません。別のパスワードで登録してください。";
} finally {
    if ($error) {
        require "family_make_form.php";
        exit;
    }
}
