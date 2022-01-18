<?php session_start(); ?>

<?php

if(isset($_POST['make_pass'])){
    require 'dbconnect.php';
    try{
        //SQL文を作る
        $sql = "SELECT MAX(id) FROM family";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //SQL文を実行する
        $stm->execute();
       
        //結果を連想配列で取得
        $result = $stm->fetch(PDO::FETCH_COLUMN);
        if(isset($result)){
            $result += 1;
        }else{
            $result = 1;
        }

        //プリペアドステートメントのエミュレーションを無効にする
		$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		//例外がスローされる
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //SQL文を作る（プレースホルダを使った式）
        $sql = "INSERT INTO family(id,name,pass) VALUES (:id,:name,:pass)";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':id', $result, PDO::PARAM_INT);
        $stm->bindValue(':name', $_POST['make_name'], PDO::PARAM_STR);
        $stm->bindValue(':pass', $_POST['make_pass'], PDO::PARAM_STR);
        //SQL文を実行する
        if($stm->execute()){
            $sql = "INSERT INTO family_user(family_id, user_id) VALUES (:family_id,:user_id)";
            //プリペアドステートメントを作る
            $stm = $pdo->prepare($sql);
            //プレースホルダに値をバインドする
            $stm->bindValue(':family_id', $result, PDO::PARAM_INT);
            $stm->bindValue(':user_id', $_SESSION["user_id"], PDO::PARAM_INT);
            //SQL文を実行する
            $stm->execute();
            //SQL文を作る（プレースホルダを使った式）
            $sql = "UPDATE user SET name = :name
            WHERE id = :id";
            //プリペアードステートメントを作る
            $stm = $pdo->prepare($sql);
            //プリペアードステートメントに値をバインドする

            $stm->bindValue(':name',$_POST['name'],PDO::PARAM_STR);
            $stm->bindValue(':id',$_SESSION['user_id'],PDO::PARAM_INT);
            //SQL文を実行する
            $stm->execute();
        }else{
            echo "エラーが発生しました。1";
        }
        
    }catch(Exception $e){
        echo "そのパスワードは使用できません。別のパスワードで登録してください。";
    }
}
else if(isset($_POST["entry_pass"])){
    require 'dbconnect.php';
    try{
        //SQL文を作る（プレースホルダを使った式）
        $sql = "SELECT DISTINCT id from family WHERE name = :name AND pass = :pass";
        //プリペアードステートメントを作る
        $stm = $pdo->prepare($sql);
        //プリペアードステートメントに値をバインドする
        $stm->bindValue(':name',$_POST['entry_name'],PDO::PARAM_STR);
        $stm->bindValue(':pass',$_POST['entry_pass'],PDO::PARAM_STR);
        //SQL文を実行する
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_COLUMN);
        if(empty($result)){
            echo "グループ名またはパスワードが間違っています。";
        }else{
        //SQL文を作る（プレースホルダを使った式）
        $sql = "INSERT INTO family_user(family_id, user_id) VALUES (:family_id,:user_id)";
        //プリペアードステートメントを作る
        $stm = $pdo->prepare($sql);
        //プリペアードステートメントに値をバインドする
        $stm->bindValue(':family_id', $result, PDO::PARAM_INT);
        $stm->bindValue(':user_id', $_SESSION["user_id"], PDO::PARAM_INT);
        //SQL文を実行する
        if($stm->execute()){
            //SQL文を作る（プレースホルダを使った式）
            $sql = "UPDATE user SET name = :name
            WHERE id = :id";
            //プリペアードステートメントを作る
            $stm = $pdo->prepare($sql);
            //プリペアードステートメントに値をバインドする

            $stm->bindValue(':name',$_POST['name'],PDO::PARAM_STR);
            $stm->bindValue(':id',$_SESSION['user_id'],PDO::PARAM_INT);
            //SQL文を実行する
            $stm->execute();
        }else{
            echo "エラーが発生しました。2";
        }
        }

    }catch(Exception $e){
        echo "エラーが発生しました。3";
    }
}

?>

<!doctype html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>グループトップ</title>
<link rel="stylesheet" media="all" href="css/ress.min.css" />
<link rel="stylesheet" media="all" href="css/style.css" />
<script src="js/jquery-2.1.4.min.js"></script>
<script src="js/style.js"></script>

<link rel="icon" type="image/png" href="img/abcd2.png">
    
</head>
<body>
<header>    
<div class="container">
<div class="row">
    <div class="col span-12">
    <div class="head">
        <h1><a href="index.php">None Leave<img src="img/abcd2.png" alt="バナー画像"></a></h1>
        </div>
    </div></div>
<div class="row">
    <div class="col span-12">
        <nav>
            <div id="open"></div>
            <div id="close"></div>    
            <div id="navi">
        <ul>
            <li><a href="index.php">ホーム</a></li>
            <li><a href="confrim.php?id=<?php echo $_SESSION['user_id'];?>">登録物一覧</a></li>
            <li><a href="clock.php">時間登録</a></li>
            <li><a href="family_top.php">グループトップ</a></li>
            <li><a href="alexa_r.php">Alexaと連携</a></li>
            <li><a href="maker.php">製作者情報</a></li>

            </ul>
                </div>
        </nav>
    </div>
        </div>
    </div>
    </header>
    <div class="mainimg">
              <img src="img/26.jpg" alt="サブページ画像">
        </div>
    <main>
	<article>
<div class="container">
<div class="row">
    <div class="col span-8">
	<div class="breadcrumb">
		<ul>
        <li><a href="index.php">ホーム</a> > <a href="confrim.php?id=<?php echo $_SESSION['user_id'];?>">登録物一覧</a> > <a href="clock.php">時間登録</a> > グループトップ</li>

		</ul>
		</div>

        <h2 class="underline">グループトップ</h2>
            <?php 

            require 'dbconnect.php';
            try{
                //SQL文を作る（プレースホルダを使った式）
                $sql = "SELECT DISTINCT a.family_id, b.name FROM family_user a, family b
                WHERE a.user_id = :user_id AND a.family_id = b.id";
                //プリペアードステートメントを作る
                $stm = $pdo->prepare($sql);
                //プリペアードステートメントに値をバインドする

                $stm->bindValue(':user_id',$_SESSION['user_id'],PDO::PARAM_INT);
                //SQL文を実行する
                $stm->execute();
                //結果の取得（連想配列で受け取る）
                $result = $stm->fetchAll(PDO::FETCH_ASSOC);
                
                if(isset($result[0]['family_id'])){
                    echo '	<form action="family_top.php" method="post">
                            メンバー検索：<br>
                            <input type="text" name="keyword" title="検索したいメンバー名を入力してください。" placeholder="メンバー名で検索" required>
                            <input type="submit" value="検索">
                            </form>';
                            if (isset($_POST['keyword'])) {
                                echo "キーワード：".$_POST['keyword'];
                                echo "<hr>";
                            }
                    
                    foreach($result as $row1){
                        //SQL文を作る（プレースホルダを使った式）
                        $sql = "SELECT user.id,user.name FROM user,family_user
                        WHERE family_user.family_id = :family_id AND user.id = family_user.user_id";
                        //プリペアードステートメントを作る
                        $stm = $pdo->prepare($sql);
                        //プリペアードステートメントに値をバインドする
                        $stm->bindValue(':family_id',$row1['family_id'],PDO::PARAM_INT);
                        //SQL文を実行する
                        $stm->execute();
                        //結果の取得（連想配列で受け取る）
                        $result1 = $stm->fetchAll(PDO::FETCH_ASSOC);

                        $f_name = $row1["name"];

                        if (isset($result1)) {
                        echo "<h5>グループ名  :  $f_name</h5>";
                        echo '<table class="full-width">';

                            echo "<th>","名前","</th>";
                            echo "<th>","メッセージの設定","</th>";

                            if (isset($_POST['keyword'])) {
                                
                                foreach($result1 as $row2){
                                    $k = $_POST['keyword'];
                                    $test = "陽子";
                                    
                                    $keyword = "/$k/u";

                                 if(preg_match($keyword,$row2['name'])){
                                    echo "<tr>";
                                    echo "<td>",$row2['name'];
                                    if($row2['id'] == $_SESSION['user_id']){
                                        echo "<br>(あなた)";
                                    }
                                    echo "</td>";
                                    echo '<td>';

                                    try{
                                        $sql = "SELECT comment, alert FROM comment WHERE family_id = :family_id AND from_id = :from_id AND to_id = :to_id";
                                        //プリペアードステートメントを作る
                                        $stm = $pdo->prepare($sql);
                                        //プリペアードステートメントに値をバインドする
                                        $stm->bindValue(':family_id',$row1['family_id'],PDO::PARAM_INT);
                                        $stm->bindValue(':from_id',$_SESSION["user_id"],PDO::PARAM_INT);
                                        $stm->bindValue(':to_id',$row2['id'],PDO::PARAM_INT);
                                        //SQL文を実行する
                                        $stm->execute();
                                        //結果の取得（連想配列で受け取る）
                                        $value = $stm->fetch(PDO::FETCH_ASSOC);
                                    }catch(Exception $e){

                                    }
                                    ?>

                                    <form action="comment_send.php" method="post">
                                    <textarea name="comment" cols="40" rows="2" maxlength="80" placeholder="入力可能なのは80文字までです。"><?php echo $value['comment'];?></textarea><br>
                                    <input type="hidden" name="from_id" value="<?php echo $_SESSION['user_id'];?>">
                                    <input type="hidden" name="to_id" value="<?php echo $row2['id'];?>">
                                    <input type="hidden" name="family_id" value="<?php echo $row1['family_id'];?>">
                                    <input type="submit" title="送信されるコメントを設定します。" value="設定する">
                                    </form>
                                    
                                        <?php if(!empty($value['comment']) && $row2['id'] != $_SESSION['user_id']){ ?>
                                            <form action="alert_set.php" method="post">
                                                <input type="hidden" name="from_id" value="<?php echo $_SESSION['user_id'];?>">
                                                <input type="hidden" name="to_id" value="<?php echo $row2['id'];?>">
                                                <input type="hidden" name="family_id" value="<?php echo $row1['family_id'];?>">
                                                <?php if($value['alert'] == 1){ ?>
                                                <input type="hidden" name="alert" value="0">
                                                <input type="submit" title="このメンバーからの通知をオフにします。" value="通知をオフ">
                                                <?php }else if($value['alert'] == 0){ ?>
                                                <input type="hidden" name="alert" value="1">
                                                <input type="submit" title="このメンバーからの通知をオンにします。" value="通知をオン">
                                                <?php }?>
                                            </form>

                                        <?php } ?>

                                    <?php
                                    echo '<form action="family_confrim.php?id=',$row2['id'],'" method="post"><input type="hidden" name="f_id" value="',$row2['id'],'"><input type="submit" value="',$row2['name'],'さんの持ち物を登録"></form>';           
                                    echo "</td></tr>";
                                    }
                                }

                            }else{

                                foreach($result1 as $row2){
                                    echo "<tr>";
                                    echo "<td>",$row2['name'];
                                    if($row2['id'] == $_SESSION['user_id']){
                                        echo "<br>(あなた)";
                                    }
                                    echo "</td>";
                                    echo '<td>';

                                    try{
                                        $sql = "SELECT comment, alert FROM comment WHERE family_id = :family_id AND from_id = :from_id AND to_id = :to_id";
                                        //プリペアードステートメントを作る
                                        $stm = $pdo->prepare($sql);
                                        //プリペアードステートメントに値をバインドする
                                        $stm->bindValue(':family_id',$row1['family_id'],PDO::PARAM_INT);
                                        $stm->bindValue(':from_id',$_SESSION["user_id"],PDO::PARAM_INT);
                                        $stm->bindValue(':to_id',$row2['id'],PDO::PARAM_INT);
                                        //SQL文を実行する
                                        $stm->execute();
                                        //結果の取得（連想配列で受け取る）
                                        $value = $stm->fetch(PDO::FETCH_ASSOC);
                                    }catch(Exception $e){

                                    }
                                    ?>

                                    <form action="comment_send.php" method="post">
                                    <textarea name="comment" cols="40" rows="2" maxlength="80" placeholder="入力可能なのは80文字までです。"><?php echo $value['comment'];?></textarea><br>
                                    <input type="hidden" name="from_id" value="<?php echo $_SESSION['user_id'];?>">
                                    <input type="hidden" name="to_id" value="<?php echo $row2['id'];?>">
                                    <input type="hidden" name="family_id" value="<?php echo $row1['family_id'];?>">
                                    <input type="submit" title="送信されるコメントを設定します。" value="設定する">
                                    </form>
                                    
                                        <?php if(!empty($value['comment']) && $row2['id'] != $_SESSION['user_id']){ ?>
                                            <form action="alert_set.php" method="post">
                                                <input type="hidden" name="from_id" value="<?php echo $_SESSION['user_id'];?>">
                                                <input type="hidden" name="to_id" value="<?php echo $row2['id'];?>">
                                                <input type="hidden" name="family_id" value="<?php echo $row1['family_id'];?>">
                                                <?php if($value['alert'] == 1){ ?>
                                                <input type="hidden" name="alert" value="0">
                                                <input type="submit" title="このメンバーからの通知をオフにします。" value="通知をオフ">
                                                <?php }else if($value['alert'] == 0){ ?>
                                                <input type="hidden" name="alert" value="1">
                                                <input type="submit" title="このメンバーからの通知をオンにします。" value="通知をオン">
                                                <?php }?>
                                            </form>

                                        <?php } ?>

                                    <?php
                                    echo '<form action="family_confrim.php?id=',$row2['id'],'" method="post"><input type="hidden" name="f_id" value="',$row2['id'],'"><input type="submit" value="',$row2['name'],'さんの持ち物を登録"></form>';           
                                    echo "</td></tr>";
                                }
                            }
                            echo "</table>";
                            ?>
                            <form action="family_exit.php" method="post">
                                <input type="hidden" name="family_id" value="<?php echo $row1['family_id'];?>">
                                <input type="submit" title="グループから退会する" value="グループから退会する">
                                </form>

                            <?php
                            echo "<hr>";
                            
                        }
                    }
                }
            
            echo '<a href="family_make.php">グループを作成する</a><br>';
            echo '<a href="family_entry.php">グループに参加する</a><br>';
            echo '<a href="confrim.php?id=',$_SESSION['user_id'],'">登録物一覧に戻る</a><br>';
            
            }catch(Exception $e){
                echo "エラーが発生しました。";
            }
            
            ?>

 </div>
	<div class="col span-4">
		<a href="confrim.php?id=<?php echo $_SESSION['user_id'];?>"><img src="img/15.png" alt="バナー画像"></a>
	    <a href="clock.php"><img src="img/14.png" alt="バナー画像"></a>
	    <a href="family_top.php"><img src="img/16.png" alt="バナー画像"></a>
	</div>
    </div>    
        </div>
	</article>
    </main>
    <footer>
 <div class="container">
 <div class="row">
    <div class="col span-4">
        <h5>登録物一覧</h5>
        <p>登録物の確認、登録、変更、削除ができます</p>
    </div>
    <div class="col span-4">
        <h5>時間登録</h5>
        <p>時間を登録することで、設定された曜日の指定された時間に通知が来るようになります</p>
    </div>
    <div class="col span-4">
        <h5>グループトップ</h5>
        <p>同グループのメンバーの忘れたくないもの登録、変更、削除、メッセージの送信ができます</p>
    </div>
        </div>
</div>
    </footer>
    <div class="copyright">
<div class="container">
<div class="row">
    <div class="col">
    Fukuiohr2021 © <a href="https://fukuiohr2.sakura.ne.jp/2021/wasurenai/index.php" target="_blank">Wasurenai </a>
    
    </div>    
        </div>
    </div>
        </div>
<p id="pagetop"><a href="#">TOP</a></p>    
</body>
</html>
