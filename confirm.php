<?php session_start(); ?>

<!doctype html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>登録物一覧</title>
    <link rel="stylesheet" media="all" href="css/ress.min.css" />
    <link rel="stylesheet" media="all" href="css/style.css" />
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/style.js"></script>

    <link rel="icon" type="image/png" href="img/abcd2.png">
</head>

<style>
    .item_name {
        font-size: 1.2em;
        padding: 10px 0px;
        text-indent: 5px;
    }

    .notification_timing {
        background-color: #EEEEEE;
        padding: 8px 0px;
        text-indent: 5px;
    }

    .item_modifier_button_cell {
        text-align: center;
        vertical-align: middle;
        padding: 0;
        padding-top: 8px;
    }
</style>

<body>
    <?php
    //ヘッダ表示
    $_SESSION['user_id'] = $_REQUEST['id'];
    include 'header_top.php';
    ?>

    <div class="mainimg">
        <img src="img/16.jpg" alt="サブページ画像">
    </div>
    <main>
        <article>
            <div class="container">
                <div class="row">
                    <div class="col span-8">
                        <div class="breadcrumb">
                            <ul>
                                <li><a href="index.php">ホーム</a> > 登録物一覧</li>
                            </ul>
                        </div>
                        <h2 class="underline">あなたの登録物一覧</h2>
                        <div>
                            <button type="submit" name="send" class="button1">
                                <a href="registration_items.php">持ち物登録</a>
                            </button>
                        </div>
                        <?php require 'items_list.php'; ?>

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
</body>

</html>