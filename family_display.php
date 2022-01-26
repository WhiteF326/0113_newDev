<?php
require 'dbconnect.php';
require 'DBController.php';
$DBControl = new DBController();

try {
  //所属しているグループのIDと名前を検索する
  $result = $DBControl->getAllFamilyFromUserId($_SESSION["user_id"]);

  //取得できたなら、グループの情報を表示する
  if (isset($result[0]['family_id'])) : ?>

    <form action="family_top.php" method="post">
      メンバー検索 : <br>
      <input type="text" name="keyword" title="検索したいメンバー名を入力してください。" placeholder="メンバー名で検索" required>
      <input type="submit" value="検索">
    </form>
    <?php
    if (isset($_POST['keyword'])) : ?>
      キーワード : <?= $_POST['keyword']; ?>
      <hr>
      <?php
    endif;

    foreach ($result as $row1) :
      //グループに所属しているユーザーのIDと名前を検索する
      $result1 = $DBControl->getAllUserFromFamilyId($row1["family_id"]);

      $f_name = $row1["name"];

      if (isset($result1)) : ?>
        <h5>グループ名 : <?= $f_name; ?></h5>
        <table class="full-width">

          <th>名前</th>
          <th>メッセージの設定</th>
          <?php
          // if (isset($_POST['keyword'])):

          foreach ($result1 as $row2) :
            if (isset($_POST['keyword'])) :
              $k = $_POST['keyword'];
              // $test = "陽子";

              $keyword = "/$k/u";

              if (!preg_match($keyword, $row2['user_name'])) :
                continue;
              endif;
            endif; ?>
            <tr>
              <td><?= $row2['user_name']; ?>

                <?php
                if ($row2['id'] == $_SESSION['user_id']) : ?>
                  <br>(あなた)
                <?php
                endif; ?>
              </td>
              <td>

                <?php
                try {
                  //メッセージと通知の設定を検索
                  $value = $DBControl->getMessageOnFamily(
                    $row1['family_id'],
                    $_SESSION["user_id"],
                    $row2['id']
                  );
                } catch (Exception $e) {
                  echo "メッセージの取得でエラーが発生しました。";
                }
                ?>

                <form action="comment_send.php" method="post">
                  <textarea name="comment" cols="40" rows="2" maxlength="80" placeholder="入力可能なのは80文字までです。"><?= $value['comment']; ?></textarea><br>
                  <input type="hidden" name="from_id" value="<?= $_SESSION['user_id']; ?>">
                  <input type="hidden" name="to_id" value="<?= $row2['id']; ?>">
                  <input type="hidden" name="family_id" value="<?= $row1['family_id']; ?>">
                  <input type="submit" title="送信されるコメントを設定します。" value="設定する">
                </form>

                <?php if (!empty($value['comment']) && ($row2['id'] != $_SESSION['user_id'])) : ?>
                  <form action="alert_set.php" method="post">
                    <input type="hidden" name="from_id" value="<?= $_SESSION['user_id']; ?>">
                    <input type="hidden" name="to_id" value="<?= $row2['id']; ?>">
                    <input type="hidden" name="family_id" value="<?= $row1['family_id']; ?>">
                    <?php if ($value['alert'] == 1) : ?>
                      <input type="hidden" name="alert" value="0">
                      <input type="submit" title="このメンバーからの通知をオフにします。" value="通知をオフ">
                    <?php elseif ($value['alert'] == 0) : ?>
                      <input type="hidden" name="alert" value="1">
                      <input type="submit" title="このメンバーからの通知をオンにします。" value="通知をオン">
                    <?php endif; ?>
                  </form>

                <?php endif; ?>
                <form action="family_confirm.php?id=<?= $row2['id']; ?>" method="post">
                  <input type="hidden" name="f_id" value="<?= $row2['id']; ?>">
                  <input type="submit" value="<?= $row2['user_name']; ?>さんの持ち物を登録">
                </form>
              </td>
            </tr>
          <?php
          endforeach;
          ?>
        </table>
        <form action="family_exit.php" method="post">
          <input type="hidden" name="family_id" value="<?= $row1['family_id']; ?>">
          <input type="submit" title="グループから退会する" value="グループから退会する">
        </form>
        <hr>
  <?php
      endif;
    endforeach;
  endif; ?>

  <div>
    <button type="submit" name="send" class="button1">
      <a href="family_make_form.php">グループを作成する</a>
    </button>
  </div>
  <div>
    <button type="submit" name="send" class="button1">
      <a href="family_entry.php">グループに参加する</a>
    </button>
  </div>
  <div>
    <button type="submit" name="send" class="button1">
      <a href="confirm.php?id=<?= $_SESSION['user_id']; ?>">登録物一覧に戻る</a>
    </button>
  </div>
<?php
} catch (Exception $e) {
  echo "エラーが発生しました。";
}
