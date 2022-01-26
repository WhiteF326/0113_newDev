<script>
    window.onload = () => {
        if (window.location.href.endsWith("dbconnect.php")) {
            window.location.href = "LINE_registration.php";
        }
    };
</script>
<?php
  // データベースユーザ
  $user = 'fukuiohr2';
  $password = 'Fukui2021d';
  // 利用するデータベース
  $dbName = 'fukuiohr2_wasurenai';
  // MySQLサーバ
  $host = 'mysql640.db.sakura.ne.jp';
  // MySQLのDSN文字列
  $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";
  //MySQLデータベースに接続する
  try {
    $pdo = new PDO($dsn, $user, $password);
    // プリペアドステートメントのエミュレーションを無効にする
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // 例外がスローされる設定にする
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    echo '<span class="error">エラーがありました。</span><br>';
    echo $e->getMessage();
    exit();
  }
  ?>