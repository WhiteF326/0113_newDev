<?php session_start(); ?>

<?php
$user_id = $_SESSION['f_id'];
$item_id = $_POST['item_id'];

require 'dbconnect.php';
try {
    //グループメンバーの登録物を削除する
    $sql = "DELETE FROM user_item 
    WHERE user_id = :user_id 
    AND item_id = :item_id";

    $stm = $pdo->prepare($sql);
    $stm->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stm->bindValue(':item_id', $item_id, PDO::PARAM_INT);
    if ($stm->execute()) {
        echo '<META http-equiv="Refresh" content="0;URL=family_confirm.php?id=', $user_id, '">';
    }
} catch (Exception $e) {
    echo "エラーが発生しました。";
}
?>
