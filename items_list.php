<?php
ini_set("display_errors", 1);

require 'dbconnect.php';
date_default_timezone_set('Asia/Tokyo');

// sanitizing and cut for output
function output_adjust(string $output)
{
    if (strlen($output) > 40) {
        return substr(htmlspecialchars($output), 0, 40) . "...";
    } else {
        return htmlspecialchars($output);
    }
}

// convert weekdays integer to weekdays string
function get_weekdays_str(int $weekday_integer)
{
    // if everyday (= 127)
    if ($weekday_integer == 127) {
        return "毎日";
    }
    // not everyday.
    $weekdays = [];
    for ($i = 0; $i < 7; $i++) {
        if ($weekday_integer & (1 << $i)) {
            $weekdays[] = ["日", "月", "火", "水", "木", "金", "土"][$i];
        }
    }
    return implode(", ", $weekdays) . "曜日";
}

try {
    // ユーザが登録している持ち物の情報をすべて取得
    $sql = "SELECT a.name, b.item_id, b.weekdays, b.notice_datetime 
        FROM item a, user_item b
        WHERE b.user_id = :id 
        AND a.id = b.item_id";
    /*
        return in :
        {
            "name" => 持ち物の名前,
            "item_id" => 持ち物 ID,
            "days" => 通知する曜日,
            "notice_datetime" => 通知する日時
        }
    */
    $stm = $pdo->prepare($sql);
    $stm->bindValue(':id', $_SESSION["user_id"], PDO::PARAM_INT);
    $stm->execute();
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);

    // 以下、持ち物のリスト表示
    if (isset($result)) : ?>
        <div class="table">
            <table class="full-width">
                <?php
                // 各持ち物が表示される行を生成
                foreach ($result as $row) : ?>
                    <tr>
                        <!-- 持ち物の名前 -->
                        <td class="item_name">
                            <?= output_adjust($row["name"]) ?>
                        </td>
                        <!-- 持ち物の削除及び変更ボタン -->
                        <td rowspan="2" class="item_modifier_button_cell">
                            <form action="registration_items.php" method="post">
                                <input type="hidden" name="item_id" value="<?= $row['item_id'] ?>">
                                <input type="submit" value="変更" class="con">
                            </form>
                            <form action="delete_items.php" method="post">
                                <input type="hidden" name="item_id" value="<?= $row['item_id'] ?>">
                                <input type="submit" value="削除" class="con">
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td class="notification_timing">
                            <!-- 通知する時刻の表示 -->
                            <?php if (!is_null($row["weekdays"])) : ?>
                                <!-- 曜日指定である場合の処理 -->
                                通知する日 : <?= get_weekdays_str($row["weekdays"]) ?>
                            <?php else : ?>
                                <!-- 日付指定である場合の処理 -->
                                <?= date(
                                    "Y年m月d日 H時i分",
                                    strtotime($row['notice_datetime'])
                                ) . "に通知"
                                ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php
                endforeach; ?>
            </table>
        </div>
    <?php
    else : ?>
        <p>持ち物がひとつも登録されていません。</p>
<?php
    endif;
} catch (Exception $e) {
    echo "申し訳ありません。内部エラーが発生しました。";
    echo "お手数ですが、再度読み込みを行ってください。";
}
