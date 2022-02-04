<?php
session_start();

require 'dbconnect.php';
date_default_timezone_set('Asia/Tokyo');

ini_set("display_errors", 1);

function getPDOtype($obj)
{
    if (is_null($obj)) {
        return PDO::PARAM_NULL;
    } else if (is_int($obj)) {
        return PDO::PARAM_INT;
    } else {
        return PDO::PARAM_STR;
    }
}

$res = array(
    "succeed" => false,
    "errors" => []
);

if (!isset($_REQUEST["item_name"])) {
    $res["errors"][] = "持ち物の名前が指定されていません。";
}
if (!isset($_REQUEST["is_update"])) {
    $res["errors"][] = "不正なリクエストです。";
}

if (!count($res["errors"])) {
    $item_name = $_REQUEST["item_name"];
    if (empty($item_name)) {
        $res["errors"][] = "持ち物の名前が指定されていません。";
    }
    $is_update = intval($_REQUEST["is_update"]);
}

if (!isset($_REQUEST["days"]) && !strlen($_REQUEST["datetime"])) {
    $res["errors"][] = "日付と日時の両方が指定されていません。";
} else if (isset($_REQUEST["days"]) && strlen($_REQUEST["datetime"])) {
    $res["errors"][] = "日付と日時の両方が指定されています。";
}

if (!count($res["errors"])) {
    if (isset($_REQUEST["days"])) {
        $days = $_REQUEST["days"];
        $week = ["sun", "mon", "tue", "wed", "thu", "fri", "sat"];

        $weekdays = 0;
        for ($i = 0; $i < 7; $i++) {
            if (gettype(array_search($week[$i], $days)) != "boolean") {
                $weekdays += 1 << $i;
            }
        }
        $datetime = null;
    } else {
        $weekdays = null;
        $datetime = $_REQUEST["datetime"];
    }

    $user_id = $_REQUEST["user_id"];

    // check if already the item has same name exists
    $chk = $pdo->query(
        "SELECT count(*) as ct FROM item, user_item
        WHERE item.name = '$item_name' AND item.id = user_item.item_id"
    )->fetch(PDO::FETCH_ASSOC)["ct"] ? true : false;
    if ($chk) {
        $item_id = $pdo->query("SELECT id FROM item WHERE name = '$item_name'")
            ->fetch(PDO::FETCH_ASSOC)["id"];
        $sql = "INSERT INTO user_item
            VALUES(:user_id, :item_id, null, :datetime, :weekdays)
            ON DUPLICATE KEY
                UPDATE notice_datetime = :datetime2, weekdays = :weekdays2";
        $stm = $pdo->prepare($sql);
        $stm->bindValue(":weekdays", $weekdays, getPDOtype($weekdays));
        $stm->bindValue(":datetime", $datetime, getPDOtype($datetime));
        $stm->bindValue(":weekdays2", $weekdays, getPDOtype($weekdays));
        $stm->bindValue(":datetime2", $datetime, getPDOtype($datetime));
        $stm->bindValue(":user_id", $user_id, getPDOtype($user_id));
        $stm->bindValue(":item_id", $item_id, getPDOtype($item_id));

        $res["succeed"] = $stm->execute();
    } else {
        $item_id = $pdo->query("SELECT COALESCE(max(id), 0) + 1 as next_id FROM item")
            ->fetch(PDO::FETCH_ASSOC)["next_id"];
        $pdo->query(
            "INSERT INTO item VALUES($item_id, '$item_name')"
        );
        $sql = "INSERT INTO user_item
            VALUES(:user_id, :item_id, null, :datetime, :weekdays)";
        $stm = $pdo->prepare($sql);
        $stm->bindValue(":weekdays", $weekdays, getPDOtype($weekdays));
        $stm->bindValue(":datetime", $datetime, getPDOtype($datetime));
        $stm->bindValue(":user_id", $user_id, getPDOtype($user_id));
        $stm->bindValue(":item_id", $item_id, getPDOtype($item_id));

        $res["succeed"] = $stm->execute();
    }
}

// now ends adding -- html follows.
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>持ち物<?= $is_update ? "更新" : "登録" ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/jpg" href="img/abcd2.png">
</head>

<?php include 'header_form.php'; ?>

<body>
    <section style="text-align: center">
        <h2><span>持ち物<?= $is_update ? "更新" : "登録" ?></span></h2>
        <?php if ($res["succeed"]) : ?>
            <p>持ち物の<?= $is_update ? "更新" : "登録" ?>が完了しました。</p>
            <button type="submit" name="send" class="button3">
                <a href="confirm.php?id=<?= $_SESSION['user_id']; ?>" class="a">戻る</a>
            </button>
            <?php else :
            if (count($res["errors"])) :
                foreach ($res["errors"] as $err) : ?>
                    <p><?= $err ?></p>
                <?php endforeach ?>
            <?php else : ?>
                <p>不明なエラーにより失敗しました。</p>
            <?php endif; ?>
            <hr>
            <p>お手数ですが再度お試しください。</p>
            <form action="registration_items.php">
                <?php if (isset($_SESSION["to_item_id"])) : ?>
                    <input type="text" hidden name="item_id" value="<?= $_SESSION["to_item_id"] ?>">
                <?php else : ?>
                    <input type="text" hidden name="item_id" value="">
                <?php endif; ?>
                <input type="submit" value="戻る" class="button3">
            </form>
        <?php endif; ?>
    </section>
</body>

</html>