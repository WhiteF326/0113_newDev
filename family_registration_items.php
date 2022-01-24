<?php session_start(); ?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>メンバーの持ち物登録</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/jpg" href="img/abcd2.png">
</head>
<?php
include 'header_form.php';
date_default_timezone_set('Asia/Tokyo');
require_once("dbconnect.php");
?>
<h2>
    <span>メンバーの持ち物登録</span>
</h2>

<?php
if (!empty($_POST['item_id'])) {
    require 'search_item_name.php';
} else {
    $value = null;
}
$error = [];
$user_id = $_SESSION['f_id'];
$days = "";
if ($_POST["days"][0] == "ALL") {
    $days = "ALL";
} else {
    for ($i = 0; $i < count($_POST['days']); $i++) {
        $days .= $_POST['days'][$i];
    }
}

if (empty($_POST['contents'])) {
    $error[] = "持ち物が入力されていません。";
} else {
    $contents = $_POST["contents"];
}

if (empty($_POST['days']) && empty($_POST['datetime'])) {
    $error[] = "曜日と日付が入力されていません。";
} else if (!empty($_POST['days']) && !empty($_POST['datetime'])) {
    $error[] = "設定できるのは曜日か日付のどちらか片方だけです。";
}

if (!count($error)) {
    /*
    try {

        //現在登録されている名前を検索
        $sql = "SELECT id FROM item WHERE name = :name";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':name', $contents, PDO::PARAM_STR);
        //SQL文を実行する
        $stm->execute();
        //結果を連想配列で取得
        $result = $stm->fetch(PDO::FETCH_COLUMN);
        if (empty($result)) {
            //未登録の場合
            $sql = "SELECT MAX(id) FROM item";
            //プリペアドステートメントを作る
            $stm = $pdo->prepare($sql);
            //SQL文を実行する
            $stm->execute();
            //結果を連想配列で取得
            $result = $stm->fetch(PDO::FETCH_COLUMN);
            //新規id番号
            if (isset($result)) {
                $result += 1;
            } else {
                $result = 1;
            }

            //SQL文を作る
            $sql = "INSERT INTO item(id,name) VALUES (:id,:name)";
            //プリペアドステートメントを作る
            $stm = $pdo->prepare($sql);
            //プレースホルダに値をバインドする
            $stm->bindValue(':id', $result, PDO::PARAM_INT);
            $stm->bindValue(':name', $contents, PDO::PARAM_STR);
            if ($stm->execute()) {
            } else {
                $error[] =  "エラーが発生しました。";
            }
        }

        $sql = "INSERT INTO user_item(user_id, item_id, days, notice_datetime) VALUES (:user_id, :item_id, :days,:notice_datetime) ON DUPLICATE KEY UPDATE days = :days2, notice_datetime = :notice_datetime2";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stm->bindValue(':item_id', $result, PDO::PARAM_INT);
        if (!empty($_POST['days'])) {
            $stm->bindValue(':days', $days, PDO::PARAM_STR);
            $stm->bindValue(':days2', $days, PDO::PARAM_STR);
        } else {
            $stm->bindValue(':days', NULL, PDO::PARAM_NULL);
            $stm->bindValue(':days2', NULL, PDO::PARAM_NULL);
        }
        if (!empty($_POST['datetime'])) {
            $stm->bindValue(':notice_datetime', $_POST['datetime'], PDO::PARAM_STR);
            $stm->bindValue(':notice_datetime2', $_POST['datetime'], PDO::PARAM_STR);
        } else {
            $stm->bindValue(':notice_datetime', NULL, PDO::PARAM_NULL);
            $stm->bindValue(':notice_datetime2', NULL, PDO::PARAM_NULL);
        }
        $stm->execute();
    } catch (Exception $e) {
    }*/
}

?>


<section>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

        <div class="cp_iptxt">
            <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
            <input type="text" name="contents" value="<?php echo $value; ?>" placeholder="登録内容"><br>
        </div>
        <div class="cp_iptxt">
            <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
            <label><input type="checkbox" name="days[]" value="sun"><span>月</span></label>
            <label><input type="checkbox" name="days[]" value="mon"><span>火</span></label>
            <label><input type="checkbox" name="days[]" value="tue"><span>水</span></label>
            <label><input type="checkbox" name="days[]" value="wed"><span>木</span></label>
            <label><input type="checkbox" name="days[]" value="thu"><span>金</span></label>
            <label><input type="checkbox" name="days[]" value="fri"><span>土</span></label>
            <label><input type="checkbox" name="days[]" value="sat"><span>日</span></label>
        </div>
        <div class="cp_iptxt">
            <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
            <date><input type="datetime-local" name="datetime"></date>
            <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
        </div>
        <div><button type="submit" name="send" class="button1">登録</button></div>
    </form>

    <div>
        <?php
        if (empty($error)) : ?>
            <HR>
            <h3>[<?= $contents ?>]をリストに登録しました。<br>
                <h3>
                <?php
            elseif (isset($_POST['send'])) : ?>
                    <HR>
                    <?php
                    foreach ($error as $value) : ?>
                        <?= $value ?><br>
                <?php
                    endforeach;
                endif ?>
                <br>
                <button type="submit" name="send" class="button3">
                    <a href="family_top.php" class="a">戻る</a>
                </button>
                <br>
    </div>
</section>