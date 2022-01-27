<?php session_start(); ?>
<?php
// sanitizing and cut for output
function output_adjust(string $output)
{
    if (strlen($output) > 40) {
        return substr(htmlspecialchars($output), 0, 40) . "...";
    } else {
        return htmlspecialchars($output);
    }
}

//MySQLデータベースに接続する
include 'header_form.php';
require 'dbconnect.php';
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
        グループ<?php echo output_adjust($family_name); ?>から退会しました。
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