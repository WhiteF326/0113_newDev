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
  <title>グループトップ</title>
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
    <img src="img/26.jpg" alt="サブページ画像">
  </div>

  <main>
    <article>
      <div class="container">
        <div class="row">
          <div class="col span-8">
            <div class="breadcrumb">
              <ul>
                <li><a href="index.php">ホーム</a> > <a href="confirm.php?id=<?php echo $_SESSION['user_id']; ?>">登録物一覧</a> > <a href="time_top.php">時間登録</a> > グループトップ</li>

              </ul>
            </div>

            <h2 class="underline">グループトップ</h2>
            <?php
            require 'family_display.php';
            ?>

          </div>
          <div class="col span-4">
            <a href="confirm.php?id=<?php echo $_SESSION['user_id']; ?>"><img src="img/15.png" alt="バナー画像"></a>
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