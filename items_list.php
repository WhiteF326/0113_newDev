<?php
$week = ["sun", "mon", "tue", "wed", "thu", "fri", "sat"];
$week_jp = ["日", "月", "火", "水", "木", "金", "土"];

require 'dbconnect.php';
require 'DBController.php';
$DBControl = new DBController();

try {
    //ユーザーが登録している持ち物を検索する
    $result = $DBControl->getNotificationData($SESSION["user_id"]);
    //リストで表示する
    if (isset($result)) : ?>
        <table class="full-width">
            <th>持ち物</th>
            <th></th>
            <th>曜日</th>
            <th>日時</th>
            <?php
            foreach ($result as $row) : ?>
                <tr>
                    <td><?= $row['name']; ?></td>
                    <td>
                        <form action="registration_items.php" method="post"><input type="hidden" name="item_id" value="<?= $row['item_id']; ?>"><input type="submit" value="変更" class="con"></form>
                        <form action="delete_items.php" method="post"><input type="hidden" name="item_id" value="<?= $row['item_id']; ?>"><input type="submit" value="削除" class="con"></form>
                    </td>

                    <?php
                    if (preg_match('/ALL/u', $row["days"])) {
                        $days = "毎日";
                    } else {
                        $days = "";
                        for ($i = 0; $i < count($week); $i++) {
                            //文字列が含まれるなら
                            if (preg_match('/' . $week[$i] . '/u', $row["days"])) {
                                $days .= $week_jp[$i];
                            }
                        }
                    }
                    ?>

                    <td><?= $days; ?></td>
                    <?php
                    if (isset($row['notice_datetime'])) : ?>
                        <td><?= date("Y年m月d日 H時i分", strtotime($row['notice_datetime'])); ?></td>
                    <?php
                    else : ?>
                        <td>,</td>
                    <?php
                    endif; ?>
                </tr>
            <?php
            endforeach; ?>
        </table>
    <?php
    else : ?>
        <p>持ち物は登録されていません。</p>
<?php
    endif;
} catch (Exception $e) {
    echo "エラーが発生しました。";
}
