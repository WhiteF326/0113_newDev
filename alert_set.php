<?php session_start(); ?>

<?php
require "DBController.php";
$dbController = new DBController();
try {
    if ($dbController->setAlertFlag(
        $_POST["alert"],
        $_POST["family_id"],
        $_POST["from_id"],
        $_POST["to_id"]
    )) : ?>
        <META http-equiv="Refresh" content="0;URL=family_top.php">
    <?php else : ?>
        <META http-equiv="Refresh" content="3;URL=family_top.php">
<?php endif;
} catch (Exception $e) {
    echo "エラーが発生しました。";
}
