<?php session_start(); ?>

<!doctype html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>制作者について</title>
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
    </div></div>
<div class="row">
    <div class="col span-12">
        <nav>
            <div id="open"></div>
            <div id="close"></div>   
            <div id="navi">
        <ul>
            <li><a href="index.php">ホーム</a></li>
            <li><a href="confirm.php?id=<?= $_SESSION['user_id'];?>">登録物一覧</a></li>
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
              <img src="img/24.jpg" alt="メイン画像">
        </div>
    <main>
<div class="container">
<div class="row">
    <div class="col span-8">
    <div class="breadcrumb">
		<ul>
        <li><a href="index.php">ホーム</a> > <a href="confirm.php?id=<?= $_SESSION['user_id'];?>">登録物一覧</a> > <a href="time_top.php">時間登録</a> > <a href="family_top.php">グループトップ</a> > <a href="alexa_cooperation.php">Alexaと連携</a> > 製作者情報</li>
		</ul>
		</div>
    <div class="news">
		<h2>Team Wasurenai</h2>
		<ul>
			<li>　藤田　俊</li>
		    <li>　林　哲史</li>
		    <li>　一　春香</li>
		    <li>　竹内　友望</li>
		</ul>	
		</div>
    </div>
	<div class="col span-4">
		<a href="confirm.php?id=<?= $_SESSION['user_id'];?>"><img src="img/28.png" alt="バナー画像"></a>

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