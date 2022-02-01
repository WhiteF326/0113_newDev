<?php session_start(); ?>

<!doctype html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>None Leave</title>
  <link rel="stylesheet" media="all" href="css/ress.min.css" />
  <link rel="stylesheet" media="all" href="css/style.css" />
  <script src="js/jquery-2.1.4.min.js"></script>
  <script src="js/style.js"></script>

  <link rel="icon" type="image/png" href="img/abcd2.png">

</head>

<body>
  <?php
  //ヘッダ表示
  include 'header_top.php';
  ?>

  <div class="mainimg">
    <img src="img/10.jpg" alt="メイン画像">
  </div>

  <main>
    <div class="container">
      <div class="row">
        <div class="col span-8">
          <div class="news">
            <h2>各種説明</h2>
            <ul>
              <li>
                <a href="confirm.php?id=<?php echo $_SESSION['user_id']; ?>">
                  <img src="img/15.png" alt="バナー画像">
                  　登録物一覧が表示されます
                </a>
              </li>
              <li>
                <a href="time_top.php">
                  <img src="img/14.png" alt="バナー画像">
                  　登録物の通知が来る時間を設定します
                </a>
              </li>
              <li>
                <a href="family_top.php">
                  <img src="img/16.png" alt="バナー画像">
                  　グループ内のメンバーを表示します
                </a>
              </li>
              <li>
                <span>↓↓友達追加はここから↓↓</span>
                <br>
                　<img src="img/LineQR1.png" alt="LineのQR">
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </main>
  <?php
  include "footer.php";
  ?>

</html>