<?php session_start(); ?>
<?php

//MySQLデータベースに接続する
require 'dbconnect.php';
try{
    //SQL文を作る（プレースホルダを使った式）
    $sql = "SELECT name FROM family WHERE id = :id";
    //プリペアードステートメントを作る
    $stm = $pdo->prepare($sql);
    //プリペアードステートメントに値をバインドする
    $stm->bindValue(':id',$_POST['family_id'],PDO::PARAM_INT);
    //SQL文を実行する
    $stm->execute();
    //結果の取得（連想配列で受け取る）
    $result = $stm->fetch(PDO::FETCH_COLUMN);
    if(empty($result)){

        }else{
        $family_name = "[".$result."]";

    }
}catch(Exception $e){
    $st = "error!";
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>グループ退会</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/jpg" href="img/abcd2.png">    
</head>
<header>
    <h1><a href="index.php">None Leave<img src="img/abcd2.png" alt="バナー画像"></a></h1>
</header>

<body>
    <?php ?>
    <h2><span>グループ退会<br>グループ<?php echo $family_name ;?>から退会しますか？</span></h2>
    <form action="f_quit_c.php" method="post">

        <div>
        <input type="hidden" name="family_id" value="<?php echo $_POST['family_id'];?>">
        <input type="submit" value="グループを退会" class="button1"><br>
        
        <hr>
        <a href="f_top.php">グループトップに戻る</a><br>
        </div>

    </form>
    
</body>

</html>