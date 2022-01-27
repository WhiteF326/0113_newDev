<?php session_start(); ?>

<?php
require 'dbconnect.php';
require "DBController.php";
$dbController = new DBController();
if (isset($_POST['make_pass'])) {
    require 'make_family.php';
} else if (isset($_POST["entry_pass"])) {
    require 'join_family.php';
}

?>

<!doctype html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>グループ機能</title>
    <link rel="stylesheet" media="all" href="css/ress.min.css" />
    <link rel="stylesheet" media="all" href="css/style.css" />
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/style.js"></script>

    <link rel="icon" type="image/png" href="img/abcd2.png">
</head>

<style>
    table {
        width: inherit;
    }

    textarea {
        width: inherit;
        margin-bottom: 0 !important;
        vertical-align: top;
    }

    .message_update_button {
        padding: 0 5px !important;
    }

    .search_button {
        padding: 0 5px !important;
    }

    input {
        margin-bottom: 5px !important;
    }

    #helpButton {
        background-color: #aaaaaa !important;
        color: #ffffff !important;
        border-radius: 100% !important;
        padding: 0 9px !important;
    }

    #helpMessage {
        transition: 1s;
        opacity: 0%;
        font-size: 0em;
    }

    .helpOn {
        opacity: 100% !important;
        font-size: 1em !important;
    }
</style>

<script>
    let helpFlg = false;
    window.onload = () => {
        document.getElementById("helpButton").onclick = () => {
            helpFlg = !helpFlg;
            if (helpFlg) {
                document.getElementById("helpMessage").classList.add("helpOn");
            } else {
                document.getElementById("helpMessage").classList.remove("helpOn");
            }
        }
    }
</script>

<body>

    <?php
    //ヘッダ表示
    include 'header_top.php';
    ?>

    <div class="mainimg">
        <img src="img/26.jpg" alt="サブページ画像">
    </div>

    <main>
        <article>
            <div class="container">
                <div class="row">
                    <div class="col span-8">
                        <div class="breadcrumb">
                            <ul>
                                <li><a href="index.php">ホーム</a> > グループ機能</li>
                            </ul>
                        </div>

                        <h2 class="underline">グループ機能</h2>
                        <?php
                        require 'family_display.php';
                        ?>

                    </div>
                    <div class="col span-4">
                        <a href="confirm.php?id=<?= $_SESSION['user_id']; ?>">
                            <img src="img/15.png" alt="バナー画像">
                        </a>
                        <a href="time_top.php">
                            <img src="img/14.png" alt="バナー画像">
                        </a>
                        <a href="family_top.php">
                            <img src="img/16.png" alt="バナー画像">
                        </a>
                    </div>
                </div>
            </div>
        </article>
    </main>

    <?php
    include "footer.php";
    ?>

    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="col">
                    Fukuiohr2021 © <a href="https://fukuiohr2.sakura.ne.jp/2021/wasurenai/index.php" target="_blank">Wasurenai </a>
                </div>
            </div>
        </div>
    </div>
    <p id="pagetop"><a href="#">TOP</a></p>
</body>

</html>