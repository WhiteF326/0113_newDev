<?php
$week = ["sun", "mon", "tue", "wed", "thu", "fri", "sat"];
$week_jp = ["日", "月", "火", "水", "木", "金", "土"];
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
    return implode(" ", $weekdays);
} ?>

<script>
    // 持ち物の削除を実行する
    async function remove(userId, itemId) {
        await fetch("delete_items.php", {
            "method": "post",
            "mode": "cors",
            "cache": "no-cache",
            "headers": {
                "Content-Type": "application/json"
            },
            "body": JSON.stringify({
                "item_id": itemId,
                "user_id": userId,
            }),
        });
        window.location.reload();
    }
</script>

<?php
try {
    //グループに所属しているユーザーが登録している持ち物を検索する
    $sql = "SELECT a.name, b.item_id, b.weekdays, b.notice_datetime 
    FROM item a, user_item b
    WHERE b.user_id = :id 
    AND a.id = b.item_id";

    $stm = $pdo->prepare($sql);
    $stm->bindValue(':id', /*$_SESSION["user_id"]*/ $_POST["target_user_id"], PDO::PARAM_INT);
    $stm->execute();
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    //リストで表示する
    if (isset($result)) : ?>
        <table align='center'>
            <th>持ち物</th>
            <th>曜日/日時</th>
            <th></th>
            <?php
            foreach ($result as $row) : ?>
                <tr>
                    <td><?= output_adjust($row['name']) ?></td>

                    <td>
                        <?php if (!is_null($row["weekdays"])) : ?>
                            <?= get_weekdays_str($row["weekdays"]); ?>
                        <?php else : ?>
                            <?= date("Y年m月d日\nH時i分", strtotime($row['notice_datetime'])) ?>
                        <?php endif; ?>
                    </td>

                    <td>
                        <form action="family_registration_items.php" method="post">
                            <input type="hidden" name="item_id" value="<?= $row['item_id']; ?>">
                            <input type="submit" value="変更" class="button2">
                        </form>
                        <button class="button2" onclick="remove(<?= $_POST["target_user_id"] ?>, <?= $row['item_id']; ?>)">
                            削除
                        </button>
                    </td>
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
