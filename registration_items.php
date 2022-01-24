<?php session_start(); ?>


<!DOCTYPE html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">

<title>忘れたくないもの登録画面</title>
<link rel="stylesheet" href="style.css">
<link rel="icon" type="image/jpg" href="img/abcd2.png">    

</head>
<header>
<h1><a href="index.php">None Leave<img src="img/abcd2.png" alt="バナー画像"></a></h1>
</header>
<h2><span>持ち物登録</span></h2>

<?php 
require_once("dbconnect.php");

if(!empty($_POST['item_id'])){
    try{

        //現在登録されている名前を検索
        $sql = "SELECT name FROM item WHERE id = :id";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':id', $_POST['item_id'], PDO::PARAM_INT);
        //SQL文を実行す
        $stm->execute();
        //結果を連想配列で取得
        $value = $stm->fetch(PDO::FETCH_COLUMN);
    }catch(Exception $e){

    }
}else{
    $value = null;
}
$error = [];
$user_id = $_SESSION['user_id'];
$days = "";
if($_POST["days"][0] == "ALL"){
    $days = "ALL";
}
else{
  for($i = 0 ; $i < count($_POST['days']) ; $i++){
    $days .= $_POST['days'][$i];
}  
}

if(empty($_POST['contents'])){
    $error[] = "持ち物が入力されていません。";
}else{
$contents = $_POST["contents"];
}

if(empty($_POST['days']) && empty($_POST['datetime'])){
    $error[] = "曜日と日付が入力されていません。";
}else if (!empty($_POST['days']) && !empty($_POST['datetime'])){
    $error[] = "設定できるのは曜日か日付のどちらか片方だけです。";
}else{

date_default_timezone_set('Asia/Tokyo');

try{
    //現在登録されている名前を検索
    $sql = "SELECT id FROM item WHERE name = :name";
    //プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    //プレースホルダに値をバインドする
    $stm->bindValue(':name', $contents, PDO::PARAM_STR);
    //SQL文を実行する
    $stm->execute();
    //結果を連想配列で取得
    $result = $stm->fetch(PDO::FETCH_COLUMN);
    if(empty($result)){
        //未登録の場合
        $sql = "SELECT MAX(id) FROM item";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //SQL文を実行する
        $stm->execute();
        //結果を連想配列で取得
        $result = $stm->fetch(PDO::FETCH_COLUMN);
        //新規id番号
        if(isset($result)){
            $result += 1;
        }else{
            $result = 1;
        }
        
        //SQL文を作る
        $sql = "INSERT INTO item(id,name) VALUES (:id, :name)";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':id', $result, PDO::PARAM_INT);
        $stm->bindValue(':name', $contents, PDO::PARAM_STR);
        if ($stm->execute()) {
            
        } else {
            $error[] =  "エラーが発生しました。";
        }  

    }

    $sql = "INSERT INTO user_item(user_id, item_id, days, notice_datetime) VALUES (:user_id, :item_id, :days,:notice_datetime) ON DUPLICATE KEY UPDATE days = :days2, notice_datetime = :notice_datetime2";
    //プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    //プレースホルダに値をバインドする
    $stm->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stm->bindValue(':item_id', $result, PDO::PARAM_INT);
    if(!empty($_POST['days'])){
        $stm->bindValue(':days', $days, PDO::PARAM_STR);
        $stm->bindValue(':days2', $days, PDO::PARAM_STR);
    }
    else{
        $stm->bindValue(':days', NULL, PDO::PARAM_NULL);
        $stm->bindValue(':days2', NULL, PDO::PARAM_NULL);
    }
    if(!empty($_POST['datetime'])){
        $stm->bindValue(':notice_datetime', $_POST['datetime'], PDO::PARAM_STR);
        $stm->bindValue(':notice_datetime2', $_POST['datetime'], PDO::PARAM_STR);
    }
    else{
        $stm->bindValue(':notice_datetime', NULL, PDO::PARAM_NULL);
        $stm->bindValue(':notice_datetime2', NULL, PDO::PARAM_NULL);
    }
    $stm->execute();

}catch(Exception $e){
    
 }
}

?>


<section>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
    
    <div class="cp_iptxt">
    <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
	<input type="text" name="contents" value="<?php echo $value; ?>" placeholder="登録内容"><br>
    </div>
    <div class="cp_iptxt">
    <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
    <label><input type="checkbox" name="days[]" value="ALL">毎日</label>
    <label><input type="checkbox" name="days[]" value="sun">日</label>
    <label><input type="checkbox" name="days[]" value="mon">月</label>
    <label><input type="checkbox" name="days[]" value="tue">火</label>
    <label><input type="checkbox" name="days[]" value="wed">水</label>
    <label><input type="checkbox" name="days[]" value="thu">木</label>
    <label><input type="checkbox" name="days[]" value="fri">金</label>
    <label><input type="checkbox" name="days[]" value="sat">土</label>

    </div>
    <div class="cp_iptxt">
    <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
    <input type="datetime-local" name="datetime">
	<i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
    </div>
    <div><button type="submit" name="send" class="button1">登録</button></div>
    </form>

    <div>
    <?php

        if(empty($error)){
            echo "<HR>";
            echo "<h3>[$contents] をリストに登録しました。<br><h3>";
            
        }else if(isset($_POST['send'])){
            echo "<HR>";
            foreach($error as $value){
    
                echo $value."<br>";
            }

        }
        echo "<br><a href='confirm.php?id=$user_id' title='登録物一覧ページへ'>戻る</a><br>";
    ?>
    </div>
    <div><br><hr><br><a href="time_set.php">曜日で登録した持ち物の時間設定</a></div>

</section>