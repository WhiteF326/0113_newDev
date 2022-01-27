<?php session_start(); ?>

<!doctype html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alexaと連携</title>
    <link rel="stylesheet" media="all" href="css/ress.min.css" />
    <link rel="stylesheet" media="all" href="css/style.css" />
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/style.js"></script>

    <link rel="icon" type="image/png" href="img/abcd2.png">

</head>

<body>
    <?php require "header_top.php"; ?>
    <div class="mainimg">
        <img src="img/Alexa.jpg" alt="メイン画像">
    </div>
    <main>
        <div class="container">
            <div class="row">
                <div class="col span-8">
                    <div class="breadcrumb">
                        <ul>
                            <li><a href="index.php">ホーム</a> > Alexaと連携</li>
                        </ul>
                    </div>
                    <div class="news">
                        <h2>Alexaと連携</h2>

                        <?php
                        require 'dbconnect.php';
                        require 'Alexa_cooperation_db.php';
                        ?>

                    </div>
                </div>
                <div class="col span-4">
                    <a href="confirm.php?id=<?php echo $_SESSION['user_id']; ?>"><img src="img/15.png" alt="バナー画像"></a>
                    <a href="time_top.php"><img src="img/14.png" alt="バナー画像"></a>
                    <a href="family_top.php"><img src="img/16.png" alt="バナー画像"></a>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <div class="container">
            <div class="row">
                <div class="col span-4">
                    <h5>登録物一覧</h5>
                    <p>登録物の確認、登録、変更、削除ができます</p>

                </div>
                <div class="col span-4">
                    <h5>通知時刻確認 / 変更</h5>
                    <p>時間を登録することで、設定された曜日の指定された時間に通知が来るようになります</p>
                </div>
                <div class="col span-4">
                    <h5>グループ機能</h5>
                    <p>同グループのメンバーの忘れたくないもの登録、変更、削除、メッセージの送信ができます</p>
                </div>
            </div>
        </div>
    </footer>
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