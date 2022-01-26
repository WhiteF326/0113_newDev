<?php
if (empty($_SESSION['user_id'])) {
  require "LINE_registration.php";
  exit;
}
?>
<header>
  <div class="container">
    <div class="row">
      <div class="col span-12">
        <div class="head">
          <h1>
            <a href="index.php">
              <img src="img/icon.png" alt="バナー画像">
            </a>
          </h1>
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
              <li><a href="confirm.php?id=<?php echo $_SESSION['user_id']; ?>">登録物一覧</a></li>
              <li><a href="time_top.php">時間登録</a></li>
              <li><a href="family_top.php">グループトップ</a></li>
              <li><a href="Alexa_cooperation.php">Alexaと連携</a></li>
              <li><a href="maker.php">製作者情報</a></li>
            </ul>
        </nav>
      </div>
    </div>
  </div>
  </div>
</header>