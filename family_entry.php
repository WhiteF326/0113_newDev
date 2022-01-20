<?php session_start(); ?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>グループに参加</title>
  <link rel="stylesheet" href="style.css">
  <link rel="icon" type="image/jpg" href="img/abcd2.png">
</head>

<?php
//ヘッダ表示
require 'header_form.php';
?>



<body>
  <?php ?>
  <h2><span>グループ参加<br>グループ名とパスワード、あなたの名前を入力してください。</span></h2>
  <form action="family_top.php" method="post">

    <div class="cp_iptxt">
      <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
      <input type="text" name="entry_name" placeholder="グループ名" required><br>
    </div>

    <div class="cp_iptxt">
      <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
      <input type="password" name="entry_pass" placeholder="グループのパスワード" required><br>
    </div>

    <div class="cp_iptxt">
      <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
      <input type="text" name="name" placeholder="あなたの名前" required><br>
    </div>
    <div>
      <input type="submit" value="グループに参加" class="button1">
      <br>

  </form>
  <hr>
  <a href="family_top.php">グループトップに戻る</a><br>
  </div>

</body>

</html>