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
include "dbconnect.php";

$family_id = $_POST["family_id"];
$family_name = $pdo->query("SELECT name FROM family WHERE id = $family_id")
    ->fetch(PDO::FETCH_ASSOC)["name"];
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
        <span>グループ退会</span>
        <br>
        グループ<?php echo output_adjust($family_name); ?>から退会しますか？
    </h2>
    <br>
    <form action="family_exit_db.php" method="post">

        <div>
            <input type="hidden" name="family_id" value="<?php echo $_POST['family_id']; ?>">
            <input type="submit" value="グループを退会" class="button1"><br>

            <br>
            <button type="submit" name="send" class="button3">
                <a href="family_top.php" class="a">グループ機能に戻る</a><br>
            </button>
            <br>
        </div>

    </form>

</body>

</html>