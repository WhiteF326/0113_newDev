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

            <?php
            require 'dbconnect.php';
            require 'search_user_name.php';
            ?>
            <h2 class="underline"><?=$list; ?></h2>
            <div>
              <button type="submit" name="send" class="button1">
                <a href="registration_items.php">持ち物登録</a>
              </button>
            </div>
            <?php
            require 'items_list.php';
            ?>

          </div>
          <div class="col span-4">
            <a href="confirm.php?id=<?=$_SESSION['user_id']; ?>"><img src="img/15.png" alt="バナー画像"></a>
            <a href="time_top.php"><img src="img/14.png" alt="バナー画像"></a>
            <a href="family_top.php"><img src="img/16.png" alt="バナー画像"></a>
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