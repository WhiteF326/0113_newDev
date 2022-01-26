<?php session_start(); ?>
<?php

//$_POST['family_id']が存在する

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
<header>
    <h1><a href="index.php">None Leave<img src="img/abcd2.png" alt="バナー画像"></a></h1>
</header>

<body>
    <?php ?>
    <h2><span>グループ退会<br>グループ<?= $family_name; ?>から退会しますか？</span></h2>
    <form action="family_exit_db.php" method="post">

        <div>
            <input type="hidden" name="family_id" value="<?= $_POST['family_id']; ?>">
            <input type="submit" value="グループを退会" class="button1"><br>

            <hr>
            <a href="family_top.php">グループトップに戻る</a><br>
        </div>

    </form>

</body>

</html>