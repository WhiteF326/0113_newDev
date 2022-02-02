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
    <?php
    ini_set("display_errors", 1);
        require "RaspberryPi_db.php";
    ?>
    <div class="mainimg">
        <img src="img/RaspberryPi.png" alt="メイン画像">
    </div>
    <main>
        <div class="container">
            <div class="row">
                <div class="col span-8">
                    <div class="breadcrumb">
                        <ul>
                            <li><a href="index.php">ホーム</a> > RaspberryPiと連携</li>
                        </ul>
                    </div>
                    <div class="news">
                        <h2>
                            RaspberryPiと連携
                        </h2>
                        <p>RaspberryPiと連携するために携帯とRaspberryPiの12桁のMACアドレスを入力してください。</p>
                        <form action="RaspberryPi_register.php" method="post">
                            <span>RaspberryPiのMACアドレス</span><br>
                            <input type="text" name="RaspberryPi_MAC_address" placeholder="RaspberryPiのMACアドレス" pattern="[A-Fa-f0-9]{12}" required><br>
                            <span>携帯のMACアドレス</span><br>
                            <input type="text" name="smartphone_MAC_address" placeholder="携帯のMACアドレス" pattern="[A-Fa-f0-9]{12}" required>
                            <input type="submit" title="設定を完了します。" value="設定">
                        </form>
                        <?php if (isUserCooperatedWithRaspberryPi(intval($_SESSION["user_id"]))) : ?>
                            <p>既に連携されています。</p>
                        <?php else : ?>
                            <p>連携されていません。</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col span-4">
                    <a href="confirm.php?id=<?= $_SESSION['user_id']; ?>"><img src="img/15.png" alt="バナー画像"></a>
                    <a href="time_top.php"><img src="img/14.png" alt="バナー画像"></a>
                    <a href="family_top.php"><img src="img/16.png" alt="バナー画像"></a>
                </div>
            </div>
        </div>
    </main>
    <?php require "footer.php"; ?>
</body>

</html>