<?php
$week = ["sun", "mon", "tue", "wed", "thu", "fri", "sat"];
$week_jp = ["日", "月", "火", "水", "木", "金", "土"];
date_default_timezone_set('Asia/Tokyo');

try {
  //ユーザーが登録している持ち物を検索する
  $sql = "SELECT a.name, b.item_id, b.days, b.notice_datetime 
    FROM item a, user_item b
    WHERE b.user_id = :id 
    AND a.id = b.item_id";

  $stm = $pdo->prepare($sql);
  $stm->bindValue(':id', $_SESSION["user_id"], PDO::PARAM_INT);
  $stm->execute();
  $result = $stm->fetchAll(PDO::FETCH_ASSOC);
  //リストで表示する
  if (isset($result)) : ?>
    <div class="table">
      <table class="full-width">
        <?php
        foreach ($result as $row) : ?>
          <tr>
            <td><?= $row['name'] ?></td>
            <td>
              <form action="registration_items.php" method="post">
                <input type="hidden" name="item_id" value="<?= $row['item_id'] ?>">
                <input type="submit" value="変更" class="con">
              </form>
            </td>
          </tr>
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
          <tr>
            <td>
              <?php
              if (isset($row['notice_datetime'])) : ?>
                <?= date("Y年m月d日 H時i分", strtotime($row['notice_datetime'])) ?>
              <?php
              else : ?>
                <?= $days ?>

              <?php
              endif; ?>
            </td>
            <td>
              <form action="delete_items.php" method="post">
                <input type="hidden" name="item_id" value="<?= $row['item_id'] ?>">
                <input type="submit" value="削除" class="con">
              </form>
            </td>
          </tr>
        <?php
        endforeach; ?>
      </table>
    </div>
  <?php
  else : ?>
    <p>持ち物は登録されていません。</p>
<?php
  endif;
} catch (Exception $e) {
  echo "エラーが発生しました。";
}
