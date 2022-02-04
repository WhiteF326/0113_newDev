<?php session_start(); ?>

<!doctype html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>製作者情報</title>
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
    <img src="img/24.jpg" alt="メイン画像">
  </div>
  <main>
    <div class="container">
      <div class="row">
        <div class="col span-8">
          <div class="breadcrumb">
            <ul>
              <li><a href="index.php">ホーム</a> > 製作者情報</li>
            </ul>
          </div>
          <div class="news">
            <h2>None Leave Team</h2>
            <ul>
              <li>2022年度 大原情報ITクリエイター専門学校卒業生</li>
            </ul>
          </div>
        </div>
        <div class="col span-4">
          <a href="confirm.php?id=<?php echo $_SESSION['user_id']; ?>"><img src="img/28.png" alt="バナー画像"></a>

        </div>
      </div>
    </div>
  </main>

  <?php
  include "footer.php";
  ?>
</body>

</html>
