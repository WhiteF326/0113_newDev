<?php session_start(); ?>
<?php

require 'dbconnect.php';
date_default_timezone_set('Asia/Tokyo');

require 'search_time.php';
/*
try{
    //登録されている時間を拾うsql
    $sql = "SELECT notice_time, return_time, check_time FROM user WHERE id = :id";
    //プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
     //プレースホルダに値をバインドする
    $stm->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stm->execute();
    //結果を連想配列で取得
    $result = $stm->fetch(PDO::FETCH_ASSOC);
    $mtime = $result['notice_time'];
    $etime = $result['check_time'];
    $rtime = $result['return_time'];
}catch(Exception $e){
    $error[] =  "エラーが発生しました。";
}
*/
?>

<!DOCTYPE html>

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>時間登録</title>
<link rel="stylesheet" href="style.css">
<link rel="icon" type="image/png" href="img/abcd2.png">

</head>
<header>
<h1><a href="index.php">None Leave<img src="img/abcd2.png" alt="バナー画像"></a></h1>
</header>

<h2><span>時間登録</span></h2>

<?php 
$error = [];

$user_id = $_SESSION['user_id'];

if($_POST['m_time'] == 000000 OR $_POST['e_time'] == 000000){

    $error[] = "出発時と更新時の時間は必ず入力してください。";
}else{

$notice_time = $_POST["m_time"];
$check_time = $_POST["e_time"];
$return_time = $_POST["r_time"];

require 'update_time.php';

/*
try{
    //入力された時間に変更するsql
    $sql = "UPDATE user
    SET notice_time = :notice_time, return_time = :return_time, check_time = :check_time
    WHERE id = :id";
    //プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
     //プレースホルダに値をバインドする
    $stm->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stm->bindValue(':notice_time', $notice_time, PDO::PARAM_INT);
    if($return_time == 000000){
        $stm->bindValue(':return_time', null, PDO::PARAM_NULL);
    }else{
        $stm->bindValue(':return_time', $return_time, PDO::PARAM_INT);
        }
    $stm->bindValue(':check_time', $check_time, PDO::PARAM_INT);
    //SQL文を実行する
    $stm->execute();

}catch(Exception $e){
    $error[] =  "エラーが発生しました。";
 }
 */
}
?>

        <section>
            <form action="<?= $_SERVER['PHP_SELF'];?>" method="post">
            
            <div class="cp_iptxt">
            <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
            その日の持ち物を通知する時間<br>
            (ex.通勤等で家を出る時間、持ち物を通知してほしい時間)<br>
            <input type="time" name="m_time" value="<?= $mtime; ?>" required><br>
            </div>

            <div class="cp_iptxt">
            <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
            家に帰りだす時間<br>
            (ex.会社や学校を出る時間)<br>
            <input type="time" name="r_time"><br>
            <!-- <input type="time" name="r_time" value="$rtime;" ><br> -->
            </div>

            <div class="cp_iptxt">
            <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
            次の日の持ち物を確認する時間<br>
            (ex.寝る前に持ち物の準備をする時間)<br>
            <input type="time" name="e_time" value="<?= $etime; ?>" required><br>
            </div>

            <div><button type="submit" name="send" class="button1">登録</button></div>
            </form>

            <div>
            <?php

                if(empty($error)) :?>
                    <HR>
                    <h3>その日の持ち物を通知する時間<br>[<?=$notice_time?>]  <br>次の日の持ち物を確認する時間<br>[<?=$check_time?>]<br>で登録しました。<br><h3>

                <?php
                else if(isset($_POST['send'])):?>
                    <HR>
                    <?php
                    foreach($error as $value):?>
                        <?=$value;?><br>
                    <?php
                    endforeach;
                endif;
            ?>

            <br><a href='time_top.php' title='時間登録ページへ'>戻る</a><br>

            </div>
        </section>
