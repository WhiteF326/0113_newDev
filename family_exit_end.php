<?php session_start(); ?>
<?php
//MySQLデータベースに接続する
include 'header_form.php';
require 'dbconnect.php';
require 'search_family_name.php';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>グループ退会</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/jpg" href="img/abcd2.png">
</head>

<body>
    <?php ?>
    <h2>
        グループ<?php echo $family_name; ?>から退会しました。
        <br>
        グループ機能に戻ってください。
    </h2>
    <div>
        <button type="submit" name="send" class="button3">
            <a href="family_top.php" class="a">グループ機能に戻る</a><br>
        </button>
    </div>

</body>

</html>