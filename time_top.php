<?php session_start(); ?>

<!doctype html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>時間登録</title>
    <link rel="stylesheet" media="all" href="css/ress.min.css" />
    <link rel="stylesheet" media="all" href="css/style.css" />
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/style.js"></script>

    <link rel="icon" type="image/png" href="img/abcd2.png">

</head>

<body>
    <header>
        <div class="container">
            <div class="row">
                <div class="col span-12">
                    <div class="head">
                        <h1><a href="index.php">None Leave<img src="img/abcd2.png" alt="バナー画像"></a></h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col span-12">
                    <nav>
                        <div id="open"></div>
                        <div id="close"></div>
                        <div id="navi">
                            <ul>
                                <li><a href="index.php">ホーム</a></li>
                                <li><a href="confirm.php?id=<?= $_SESSION['user_id']; ?>">登録物一覧</a></li>
                                <li><a href="time_top.php">時間登録</a></li>
                                <li><a href="family_top.php">グループトップ</a></li>
                                <li><a href="alexa_cooperation.php">Alexaと連携</a></li>
                                <li><a href="maker.php">製作者情報</a></li>

                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <div class="mainimg">
        <img src="img/23.png" alt="サブページ画像">
    </div>
    <main>
        <article>
            <div class="container">
                <div class="row">
                    <div class="col span-8">
                        <div class="breadcrumb">
                            <ul>
                                <li><a href="index.php">ホーム</a> > <a href="confirm.php?id=<?= $_SESSION['user_id']; ?>">登録物一覧</a> > 時間登録</li>
                            </ul>
                        </div>

                        <h2 class="underline">時間登録</h2>
                        <?php
                        require 'dbconect.php';
                        require 'serach_time.php';

                        /*
            try{
                //SQL文を作る（プレースホルダを使った式）
                $sql ="SELECT notice_time, return_time, check_time FROM user WHERE id = :user_id";
                //プリペアードステートメントを作る
                $stm = $pdo->prepare($sql);
                //プリペアードステートメントに値をバインドする
                $stm->bindValue(':user_id',$_SESSION['user_id'],PDO::PARAM_INT);
                //SQL文を実行する
                $stm->execute();
                //結果の取得（連想配列で受け取る）
                $result = $stm->fetch(PDO::FETCH_ASSOC);
                if(!empty($result["notice_time"] && !empty($result["check_time"]))){
                    echo "現在設定されている時間<br>";
                    echo "その日の持ち物を通知する時間 : ", $result["notice_time"], "<br>";
                    if(($result["return_time"]) != null){
                        echo "帰りだす時間 　　　　　　　　: ", $result["return_time"], "<br>";
                    }else{
                        echo "帰りだす時間 　　　　　　　　: 未設定　<br>";
                    }
                    echo "次の日の持ち物を確認する時間 : ", $result["check_time"], "<br>";


                }else{
                    echo "時間を登録してください。", "<br>";
                }

            }catch(Exception $e){
                echo "エラーが発生しました。";
            }
            */
                        if (!empty($result["notice_time"]) && !empty($result["check_time"])) : ?>
                            現在設定されている時間<br>
                            その日の持ち物を通知する時間 : <?= $result["notice_time"]; ?><br>
                            <?php
                            if ($result["return_time"] != null) : ?>
                                帰りだす時間 　　　　　　　　: <?= $result["return_time"] ?><br>
                            <?php
                            else : ?>
                                帰りだす時間 　　　　　　　　: 未設定　<br>
                            <?php
                            endif; ?>
                            次の日の持ち物を確認する時間 : <?= $result["check_time"] ?><br>
                        <?php
                        else : ?>
                            持ち物を通知する時間を登録してください。<br>
                        <?php
                        endif; ?>
                        <hr>
                        ?>

                        <a href="time_set.php">時間を設定する</a><br>

                    </div>

                    <div class="col span-4">
                        <a href="confirm.php?id=<?= $_SESSION['user_id']; ?>"><img src="img/2.png" alt="バナー画像"></a>
                    </div>
                </div>
            </div>
        </article>
    </main>
    <footer>
        <div class="container">
            <div class="row">
                <div class="col span-4">
                    <h5>登録物一覧</h5>
                    <p>登録物の確認、登録、変更、削除ができます</p>
                </div>
                <div class="col span-4">
                    <h5>時間登録</h5>
                    <p>時間を登録することで、設定された曜日の指定された時間に通知が来るようになります</p>
                </div>
                <div class="col span-4">
                    <h5>グループトップ</h5>
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