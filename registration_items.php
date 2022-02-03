<?php session_start(); ?>


<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>忘れたくないもの登録画面</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/jpg" href="img/abcd2.png">
</head>

<style>
    .itemHelp {
        background-color: #aaaaaa;
        color: #ffff;
        border-radius: 100%;
    }

    .toolTip {
        position: relative;
    }

    .toolTip span {
        color: #333;
        position: absolute;
        padding: 8px;
        width: 150px;
        top: 30px;
        left: -100px;
        font-size: .4em;
        line-height: 1.2em;
        border: 3px solid #BCB;
        border-radius: 10px;
        background-color: #ffffff;
        z-index: 0;
        visibility: hidden;
    }

    .toolTip span:before {
        position: absolute;
        top: -8px;
        left: 25%;
        margin-left: -9px;
        width: 0px;
        height: 0px;
        border-style: solid;
        border-width: 0 9px 9px 9px;
        border-color: transparent transparent #efffef transparent;
        z-index: 0;
    }

    .toolTip span:after {
        content: "";
        position: absolute;
        top: -12px;
        left: 25%;
        margin-left: 61px;
        width: 0px;
        height: 0px;
        border-style: solid;
        border-width: 0 10px 10px 10px;
        border-color: transparent transparent #BCB;
        z-index: -1;
    }

    .toolTip:hover {
        cursor: help;
    }

    .toolTip:hover span {
        visibility: visible;
        cursor: help;
        z-index: 20;
    }
</style>

<?php
ini_set("display_errors", 1);
//ヘッダ表示
include 'header_form.php';
?>

<!-- get item name if item_id specified -->
<?php
require "dbconnect.php";
if (isset($_REQUEST["item_id"])) {
    try {
        $item_id = $_REQUEST["item_id"];
        $item_name = $pdo->query("SELECT name FROM item WHERE id = $item_id")
            ->fetch(PDO::FETCH_ASSOC)["name"];
        $_SESSION["to_item_id"] = $_REQUEST["item_id"];
    } catch (Exception $e) {
        $item_name = "";
        $item_id = "";
        unset($_SESSION["to_item_id"]);
    }
} else {
    $item_id = "";
    $item_name = "";
    unset($_SESSION["to_item_id"]);
}
?>

<h2>
    <span>持ち物<?= strlen($item_name) ? "更新" : "登録" ?>
        <a class="toolTip">
            <input class="itemHelp" type="button" name="" value="？">
            <span>
                登録内容に、登録する<br>持ち物を入力して下さい<br>
                曜日と日付はどちらか一つしか<br>選択できません
            </span>
        </a>
    </span>
</h2>

<body>
    <section>
        <form action="register_item_result.php" method="post">
            <div class="cp_iptxt">
                <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
                <input type="text" name="item_name" value="<?= $item_name ?>" placeholder="登録内容"><br>
            </div>
            <div class="cp_iptxt">
                <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
                <label><input type="checkbox" name="days[]" value="sun"><span>日</span></label>
                <label><input type="checkbox" name="days[]" value="mon"><span>月</span></label>
                <label><input type="checkbox" name="days[]" value="tue"><span>火</span></label>
                <label><input type="checkbox" name="days[]" value="wed"><span>水</span></label>
                <label><input type="checkbox" name="days[]" value="thu"><span>木</span></label>
                <label><input type="checkbox" name="days[]" value="fri"><span>金</span></label>
                <label><input type="checkbox" name="days[]" value="sat"><span>土</span></label>
            </div>
            <div class="cp_iptxt">
                <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
                <date><input type="datetime-local" name="datetime"></date>
                <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
            </div>
            <div>
                <button type="submit" name="send" class="button1">
                    <?= strlen($item_name) ? "更新" : "登録" ?>
                </button>
            </div>
            <input hidden name="user_id" value="<?= $_SESSION['user_id']; ?>">
            <input hidden name="is_update" value="<?= strlen($item_name) ? 1 : 0 ?>">
        </form><br>

        <div>
            <button type="submit" name="send" class="button3">
                <a href="confirm.php?id=<?= $_SESSION['user_id']; ?>" class="a">戻る</a>
            </button>
        </div>
        <div><br>
            <hr><br><a href="time_set.php">曜日で登録した持ち物の時間設定</a>
        </div>

        <iframe style="display: none;" name="dummy"></iframe>
    </section>
</body>