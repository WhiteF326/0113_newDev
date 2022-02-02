<?php session_start(); ?>
<?php

//MySQLデータベースに接続する
include 'header_form.php';
require 'dbconnect.php';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Alexaと連携</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/jpg" href="img/abcd2.png">
</head>

<body>
    <section style="text-align: center">
        <h2>
            RaspberryPiと連携
        </h2>
        <p>RaspberryPi の情報を登録しました。</p>
        <div>
            <button type="submit" name="send" class="button3">
                <a href="RaspberryPi_cooperation.php" class="a">RaspberryPi連携に戻る</a><br>
            </button>
        </div>
    </section>
</body>

</html>
