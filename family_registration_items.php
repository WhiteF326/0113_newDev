<?php session_start(); ?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>メンバーの持ち物登録</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/jpg" href="img/abcd2.png">
</head>
<header>
    <h1><a href="index.php">None Leave<img src="img/abcd2.png" alt="バナー画像"></a></h1>
</header>
<h2><span>メンバーの持ち物登録</span></h2>

<?php

//$_POST['item_id']が登録されている
require 'dbconnect.php';
date_default_timezone_set('Asia/Tokyo');

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
    //持ち物登録
    require 'registration_items_db.php';
    require 'registration_user_item.php';
}

?>


<section>
    <form action="<?= $_SERVER['PHP_SELF']; ?>" method="post">

        <div class="cp_iptxt">
            <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
            <input type="text" name="contents" value="<?= $value; ?>" placeholder="登録内容"><br>
        </div>
        <div class="cp_iptxt">
            <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
            <label><input type="checkbox" name="days[]" value="ALL">毎日</label>
            <label><input type="checkbox" name="days[]" value="sun">日</label>
            <label><input type="checkbox" name="days[]" value="mon">月</label>
            <label><input type="checkbox" name="days[]" value="tue">火</label>
            <label><input type="checkbox" name="days[]" value="wed">水</label>
            <label><input type="checkbox" name="days[]" value="thu">木</label>
            <label><input type="checkbox" name="days[]" value="fri">金</label>
            <label><input type="checkbox" name="days[]" value="sat">土</label>

        </div>
        <div class="cp_iptxt">
            <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
            <input type="datetime-local" name="datetime">
            <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
        </div>
        <div><button type="submit" name="send" class="button1">登録</button></div>
    </form>

    <div>
        <?php
        if (empty($error)) : ?>
            <HR>
            <h3>[<?= $contents; ?>]をリストに登録しました。<br>
                <h3>
                <?php
            else if (isset($_POST['send'])) : ?>
                    <HR>
                    <?php
                    foreach ($error as $value) : ?>
                        <?= $value; ?><br>
                <?php
                    endforeach;
                endif ?>
                <br><a href='family_confirm.php?id=<?= $user_id; ?>' title='トップページに戻ります'>戻る</a><br>
    </div>
</section>