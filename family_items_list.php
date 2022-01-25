<?php
$week = ["sun", "mon", "tue", "wed", "thu", "fri", "sat"];
$week_jp = ["日", "月", "火", "水", "木", "金", "土"];
date_default_timezone_set('Asia/Tokyo');

try {
    //グループに所属しているユーザーが登録している持ち物を検索する
    $sql = "SELECT a.name, b.item_id, b.days, b.notice_datetime 
    FROM item a, user_item
    WHERE b.user_id = :id 
    AND a.id = b.item_id";

    $stm = $pdo->prepare($sql);
    $stm->bindValue(':id', $_POST["f_id"], PDO::PARAM_INT);
    $stm->execute();
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    //リストで表示する
    if (isset($result)) : ?>
        <table align='center'>
            <th>持ち物</th>
            <th></th>
            <th></th>
            <th>曜日</th>
            <th>日時</th>
            <?php
            foreach ($result as $row) : ?>
                <tr>
                    <td><?= $row['name'] ?></td>
                    <td>
                        <form action="family_registration_items.php" method="post"><input type="hidden" name="item_id" value="<?= $row['item_id'] ?>"><input type="submit" value="変更" class="button2"></form>
                    </td><td>
                        <form action="family_delete_items.php" method="post"><input type="hidden" name="item_id" value="<?= $row['item_id'] ?>"><input type="submit" value="削除" class="button2"></form>
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

                    <td><?= $days ?></td>
                    <?php
                    if (isset($row['notice_datetime'])) : ?>
                        <td><?= date("Y年m月d日 H時i分", strtotime($row['notice_datetime'])) ?></td>
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
