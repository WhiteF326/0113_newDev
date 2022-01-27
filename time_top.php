<?php session_start(); ?>

<!doctype html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>通知時刻確認 / 変更</title>
    <link rel="stylesheet" media="all" href="css/ress.min.css" />
    <link rel="stylesheet" media="all" href="css/style.css" />
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/style.js"></script>

    <link rel="icon" type="image/png" href="img/abcd2.png" />
</head>

<style>
    .notice_time {
        padding: 10px 0px;
        text-indent: 5px;
    }

    .return_time {
        padding: 0px 10px;
        text-indent: 5px;
    }

    .check_time {
        padding-left: 0px;
        text-indent: 5px;
        /* text-align: right;
        padding-right: 20px */
    }
</style>

<script type="text/javascript">
    window.addEventListener("load", () => {
        document.getElementById("modifyTimeSettings").onclick = () => {
            console.log("test");
            location.href = "time_set.php";
        }
    });
</script>

<?php
// strings declaration
$time_names = array(
    "notice_time" => "当日の持ち物の通知",
    "return_time" => "帰りだす時間",
    "check_time" => "翌日の持ち物の確認"
);

// make string for time output
function make_time_str($value)
{
    if (!$value) {
        return "登録されていません";
    }

    $time = strtotime($value);
    $h = substr(" " . date("G", $time), -2);
    $m = substr(" " . date("i", $time), -2);
    $s = substr(" " . date("s", $time), -2);

    return $h . "時 " . $m . "分 " . $s . "秒";
}
?>

<body>
    <?php
    // ヘッダを表示
    include 'header_top.php';
    ?>

    <div class="mainimg">
        <img src="img/23.png" alt="サブページ画像">
    </div>

    <main>
        <article>
            <div class="container">
                <div class="row">
                    <div class="col span-8">
                        <div class="breadcrumb">
                            <ul>
                                <li><a href="index.php">ホーム</a> > 通知時刻確認 / 変更</li>
                            </ul>
                        </div>

                        <h2 class="underline">通知時刻確認 / 変更</h2>
                        <h5>現在設定されている時間</h5>

                        <?php
                        // ユーザが登録している時刻を取得
                        require "dbconnect.php";
                        require "search_time.php";
                        /*
                        return in:
                        {
                            "notice_time" => 通知時刻,
                            "return_time" => 帰りの時刻,
                            "check_time" => 更新時刻
                        }
                        */
                        // 通知時間を表示するテーブルを表示
                        if ($result["notice_time"] == null) : ?>
                            <p>時刻が全く登録されていません。</p>
                            <div>
                                <button id="modifyTimeSettings" class="button1">
                                    時刻を登録
                                </button>
                            </div>
                        <?php else : ?>
                            <div class="table">
                                <table class="full-width">
                                    <?php
                                    foreach ($result as $key => $value) : ?>
                                        <tr>
                                            <td class="notice_time"><?= $time_names[$key] ?></td>
                                            <td class="return_time">/</td>
                                            <td class="check_time">
                                                <?= make_time_str($value) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach;
                                    ?>
                                </table>
                            </div>

                            <div>
                                <button id="modifyTimeSettings" class="button1">
                                    時刻を変更
                                </button>
                            </div>
                        <?php endif; ?>
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