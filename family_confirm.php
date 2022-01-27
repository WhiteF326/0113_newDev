<?php session_start(); ?>

<?php
if (isset($_POST["target_user_id"])) {
    $_SESSION['target_user_id'] = $_POST['target_user_id'];
    $_SESSION["family_id"] = $_POST["family_id"];
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>メンバーのページ</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="table.css">
    <link rel="icon" type="image/jpg" href="img/abcd2.png">
</head>

<?php
// ヘッダ表示
require 'header_form.php';
?>

<body>
    <?php
    require 'dbconnect.php';
    $sql = "SELECT user_name FROM family_user
        WHERE user_id = :user_id AND family_id = :family_id";
    $stm = $pdo->prepare($sql);
    $stm->bindValue(":user_id", intval($_POST["target_user_id"]), PDO::PARAM_INT);
    $stm->bindValue(":family_id", intval($_POST["family_id"]), PDO::PARAM_INT);
    $stm->execute();

    $target_name = $stm->fetch(PDO::FETCH_ASSOC)["user_name"];
    ?>
    <h2>
        <span><?= $target_name ?> さんの持ち物</span>
    </h2>
    <?php
    require 'family_items_list.php'
    ?>
    <h3>
        <div>
            <button type="submit" name="send" class="button1">
                <a href="family_registration_items.php">持ち物登録</a>
            </button>
        </div>
    </h3>
    <hr>
    <?php
    require 'family_time_display.php';
    ?>
    <hr>
    <div>
        <button type="submit" name="send" class="button3">
            <a href="family_top.php" class="a">グループ機能に戻る</a>
        </button>
    </div>
    <br>
</body>

</html>