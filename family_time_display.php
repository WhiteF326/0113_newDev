<?php
// strings declaration
$time_names = array(
    "notice_time" => "その日の持ち物を通知する時間",
    "return_time" => "帰りだす時間",
    "check_time" => "次の日の持ち物を確認する時間"
);

try {
    // 選択したグループメンバーの登録している時間を取得する
    $sql = "SELECT notice_time, return_time, check_time
    FROM user WHERE id = :family_id";

    $stm = $pdo->prepare($sql);
    $stm->bindValue(':family_id', $_SESSION['f_id'], PDO::PARAM_INT);
    $stm->execute();
    $time = $stm->fetch(PDO::FETCH_ASSOC);
    if (!empty($time["notice_time"]) && !empty($time["check_time"])) : ?>
        <l style="text-align: left">現在設定されている時間</l><br>
        <table style="border: 0">
            <?php foreach ($time as $key => $value) : ?>
                <tr style="border: 0; border-bottom: 1px #E1E1E1 solid;">
                    <td style="border: 0"><?= $time_names[$key] ?></td>
                    <td style="border: 0">/</td>
                    <td style="border: 0">
                        <?= $value ? date(
                            "H時 i分 s秒",
                            strtotime($value)
                        ) : "登録されていません" ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php
    else : ?>
        <l style="text-align: left">時間が登録されていません。</l><br>
<?php
    endif;
} catch (Exception $e) {
    echo "エラーが発生しました。";
}
