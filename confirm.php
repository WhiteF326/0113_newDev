<?php session_start(); ?>

<?php
if (isset($_REQUEST['id'])) {
  $_SESSION['user_id'] = $_REQUEST['id'];
} else {
  $st = "idを取得できませんでした。";
  return;
}
?>

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
  <header>
    <div class="container">
      <div class="row">
        <div class="col span-12">
          <div class="head">
            <h1>
              <a href="index.php">
                None Leave
                <img src="img/abcd2.png" alt="バナー画像">
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
                <li><a href="confrim.php?id=<?php echo $_SESSION['user_id']; ?>">登録物一覧</a></li>
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
            //MySQLデータベースに接続する
            require 'dbconnect.php';
            try {
              //SQL文を作る（プレースホルダを使った式）
              $sql = "SELECT name FROM user WHERE id = :id";
              //プリペアードステートメントを作る
              $stm = $pdo->prepare($sql);
              //プリペアードステートメントに値をバインドする
              $stm->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
              //SQL文を実行する
              $stm->execute();
              //結果の取得（連想配列で受け取る）
              $result = $stm->fetch(PDO::FETCH_COLUMN);
              if (empty($result)) {
                $st = "あなたの登録物一覧";
              } else {
                $st = $result . "さんの登録物一覧";
              }
            } catch (Exception $e) {
              $st = "error!";
            }

            ?>

            <h2 class="underline"><?php echo $st; ?></h2>

            <?php
            try {
              //SQL文を作る（プレースホルダを使った式）
              $sql = "SELECT a.name, b.item_id, b.days, b.notice_datetime 
              FROM item a, user_item b
				      WHERE b.user_id = :id 
				      AND a.id = b.item_id";
              //プリペアードステートメントを作る
              $stm = $pdo->prepare($sql);
              //プリペアードステートメントに値をバインドする

              $stm->bindValue(':id', $_SESSION["user_id"], PDO::PARAM_INT);
              //SQL文を実行する
              $stm->execute();
              //結果の取得（連想配列で受け取る）
              $result = $stm->fetchAll(PDO::FETCH_ASSOC);
              //リストで表示する
              $week = ["sun", "mon", "tue", "wed", "thu", "fri", "sat"];
              $week_jp = ["日", "月", "火", "水", "木", "金", "土"];
              date_default_timezone_set('Asia/Tokyo');

              if (isset($result)) {
                echo '<table class="full-width">';
                echo "<th>", "持ち物", "</th>";
                echo "<th>", "", "</th>";
                echo "<th>", "曜日", "</th>";
                echo "<th>", "日時", "</th>";
                foreach ($result as $row) {
                  echo "<tr>";
                  echo "<td>", $row['name'], "</td>";
                  echo '<td>';
                  echo '<form action="registration_items.php" method="post"><input type="hidden" name="item_id" value="', $row['item_id'], '"><input type="submit" value="変更" class="con"></form>';
                  // echo '</td><td>';
                  echo '<form action="delete_items.php" method="post"><input type="hidden" name="item_id" value="', $row['item_id'], '"><input type="submit" value="削除" class="con"></form>';
                  echo '</td>';

                  if (preg_match('/ALL/u', $row["days"])) {
                    $days = "毎日";
                  } else {
                    $days = "";
                    for ($i = 0; $i < count($week); $i++) {
                      if (preg_match('/' . $week[$i] . '/u', $row["days"])) { //文字列が含まれるなら
                        $days .= $week_jp[$i];
                      }
                    }
                  }

                  echo "<td>", $days, "</td>";
                  if (isset($row['notice_datetime'])) {
                    echo "<td>", date("Y年m月d日 H時i分", strtotime($row['notice_datetime'])), "</td>";
                  } else {
                    echo "<td>", "</td>";
                  }
                  echo "</tr>";
                }
                echo "</table>";
              } else {
                echo "持ち物は登録されていません。";
              }
            } catch (Exception $e) {
              echo "エラーが発生しました。";
            }

            ?>

            <div>
              <button type="submit" name="send" class="button1">
                <a href="registration_items.php">持ち物登録</a>
              </button>
            </div>
          </div>
          <div class="col span-4">
            <a href="confrim.php?id=<?php echo $_SESSION['user_id']; ?>"><img src="img/15.png" alt="バナー画像"></a>
            <a href="time_top.php"><img src="img/14.png" alt="バナー画像"></a>
            <a href="family_top.php"><img src="img/16.png" alt="バナー画像"></a>
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