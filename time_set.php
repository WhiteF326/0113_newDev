<?php session_start(); ?>
<?php

require 'dbconnect.php';
date_default_timezone_set('Asia/Tokyo');

require 'search_time.php';

?>

<!DOCTYPE html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>時間登録</title>
  <link rel="stylesheet" href="style.css">
  <link rel="icon" type="image/png" href="img/abcd2.png">

</head>

<?php
//ヘッダ表示
require 'header_form.php';
?>


<h2><span>時間登録</span></h2>

<?php
$error = [];

$user_id = $_SESSION['user_id'];

if ($_POST['m_time'] == 000000 or $_POST['e_time'] == 000000) {

  $error[] = "出発時と更新時の時間は必ず入力してください。";
} else {
  $notice_time = $_POST["m_time"];
  $check_time = $_POST["e_time"];
  $return_time = $_POST["r_time"];

  require 'update_time.php';
}
?>

  <section>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

      <div class="cp_iptxt">
        <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
        その日の持ち物を通知する時間<br>
        (ex.通勤等で家を出る時間、持ち物を通知してほしい時間)<br>
        <date><input type="time" name="m_time" value="<?= $mtime; ?>" required></date><br>
      </div>

      <div class="cp_iptxt">
        <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
        家に帰りだす時間<br>
        (ex.会社や学校を出る時間)<br>
        <date>
          <input type="time" name="r_time">
        </date><br>
        <!-- <input type="time" name="r_time" value="<?= $rtime; ?>" ><br> -->
      </div>

      <div class="cp_iptxt">
        <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
        次の日の持ち物を確認する時間<br>
        (ex.寝る前に持ち物の準備をする時間)<br>
        <date>
          <input type="time" name="e_time" value="<?= $etime; ?>" required>
        </date><br>
      </div>

      <div><button type="submit" name="send" class="button1">登録</button></div>
    </form>

    <div>
      <?php

      if (empty($error)) : ?>
        <HR>
        <h3>その日の持ち物を通知する時間<br>[<?= $notice_time; ?>] <br>次の日の持ち物を確認する時間<br>[<?= $check_time; ?>]<br>で登録しました。<br>
          <h3>
          <?php
        elseif (isset($_POST['send'])) : ?>
            <HR>
            <?php
            foreach ($error as $value) : ?>
              <?= $value; ?><br>

        <?php
            endforeach;
          endif;
        ?>

        <br>
        <div>
          <button type="submit" name="send" class="button3">
            <a href='time_top.php' title='時間登録ページへ' class="a">戻る</a></button>
        </div><br>

    </div>
  </section>