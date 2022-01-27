<?php

// sanitizing and cut for output
function output_adjust($output)
{
    if(is_null($output)){
        return "";
    }
    if (strlen($output) > 40) {
        return substr(htmlspecialchars($output), 0, 40) . "...";
    } else {
        return htmlspecialchars($output);
    }
}

try {
    //所属しているグループのIDと名前を検索する
    $sql = "SELECT DISTINCT a.family_id, b.name 
    FROM family_user a, family b
    WHERE a.user_id = :user_id
    AND a.family_id = b.id";

    $stm = $pdo->prepare($sql);
    $stm->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stm->execute();
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);

    //取得できたなら、グループの情報を表示する
    if (isset($result[0]['family_id'])) : ?>

        <form action="family_top.php" method="post">
            メンバー検索 : <br>
            <input type="text" name="keyword" title="検索したいメンバー名を入力してください。" placeholder="メンバー名で検索">
            <input type="submit" value="検索" class="search_button">
            <input id="helpButton" type="button" name="" value="？">
        </form>
        <span id="helpMessage">
        グループ内のメンバーのうち、入力内容を名前に含むユーザのみを表示するようにします。何も入力せず検索すると全員が表示されます。
        </span>
        <?php
        if (!empty($_POST['keyword'])) : ?>
            <br>メンバー名で絞り込み : <?= $_POST['keyword']; ?>
        <?php
        endif;

        foreach ($result as $row1) : ?>
            <hr>
            <?php
            //グループに所属しているユーザーのIDと名前を検索する
            $sql = "SELECT user.id, family_user.user_name
            FROM user, family_user
            WHERE family_user.family_id = :family_id
            AND user.id = family_user.user_id";

            $stm = $pdo->prepare($sql);
            $stm->bindValue(':family_id', $row1['family_id'], PDO::PARAM_INT);
            $stm->execute();
            $result1 = $stm->fetchAll(PDO::FETCH_ASSOC);

            $f_name = $row1["name"];

            if (isset($result1)) : ?>
                <h5>グループ : <?= output_adjust($f_name); ?></h5>
                <table>

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
                            <td><?= output_adjust($row2['user_name']); ?>

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
                                    $sql = "SELECT comment, alert FROM comment
                                    WHERE family_id = :family_id
                                    AND from_id = :from_id
                                    AND to_id = :to_id";

                                    $stm = $pdo->prepare($sql);
                                    $stm->bindValue(':family_id', $row1['family_id'], PDO::PARAM_INT);
                                    $stm->bindValue(':from_id', $_SESSION["user_id"], PDO::PARAM_INT);
                                    $stm->bindValue(':to_id', $row2['id'], PDO::PARAM_INT);
                                    $stm->execute();
                                    $value = $stm->fetch(PDO::FETCH_ASSOC);
                                } catch (Exception $e) {
                                    echo "メッセージの取得でエラーが発生しました。";
                                }
                                ?>

                                <form action="comment_send.php" method="post">
                                    <textarea name="comment" maxlength="80" placeholder="80文字まで"><?= $value['comment']; ?></textarea>
                                    <input type="submit" title="送信されるコメントを設定します。" value="更新" class="message_update_button">
                                    <input type="hidden" name="from_id" value="<?= $_SESSION['user_id']; ?>">
                                    <input type="hidden" name="to_id" value="<?= $row2['id']; ?>">
                                    <input type="hidden" name="family_id" value="<?= $row1['family_id']; ?>">
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
                                    <input type="hidden" name="family_id" value="<?= $row1["family_id"] ?>">
                                    <input type="hidden" name="target_user_id" value="<?= $row2['id']; ?>">
                                    <input type="submit" value="<?= output_adjust($row2['user_name']); ?>さんの持ち物を登録">
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
    <?php
            endif;
        endforeach;
    endif; ?>

    <hr>

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
<?php
} catch (Exception $e) {
    echo "エラーが発生しました。";
}
