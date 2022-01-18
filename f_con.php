<?php session_start();?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>メンバーのページ</title>
    <link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="table.css">
	<link rel="icon" type="image/jpg" href="img/abcd2.png">    
</head>
<header>
<h1><a href="index.php">None Leave<img src="img/abcd2.png" alt="バナー画像"></a></h1>
</header>
<body>
	
<?php
	if(isset($_POST["f_id"])){
		$_SESSION['f_id'] = $_POST['f_id'];
	}
	//MySQLデータベースに接続する
	require 'dbconect.php';
	try{
		//SQL文を作る（プレースホルダを使った式）
		$sql = "SELECT name FROM user WHERE id = :id";
		//プリペアードステートメントを作る
		$stm = $pdo->prepare($sql);
		//プリペアードステートメントに値をバインドする
		$stm->bindValue(':id',$_SESSION['f_id'],PDO::PARAM_INT);
		//SQL文を実行する
		$stm->execute();
		//結果の取得（連想配列で受け取る）
		$result = $stm->fetch(PDO::FETCH_COLUMN);
		if(empty($result)){
			echo "<h2><span>あなたの登録物一覧です。</span></h2>";
		}else{
			echo "<h2><span>",$result,"さんの登録物一覧です。</span></h2>";
		}
	}catch(Exception $e){
		echo "エラーが発生しました。";
	}
	
?>
<ol class = "sample">

<?php

	try{
		//SQL文を作る（プレースホルダを使った式）
		$sql = "SELECT a.name, b.item_id, b.days, b.notice_datetime FROM item a, user_item b
		WHERE b.user_id = :id 
		AND a.id = b.item_id";
		//プリペアードステートメントを作る
		$stm = $pdo->prepare($sql);
		//プリペアードステートメントに値をバインドする

		$stm->bindValue(':id',$_SESSION['f_id'],PDO::PARAM_INT);
		//SQL文を実行する
		$stm->execute();
		//結果の取得（連想配列で受け取る）
		$result = $stm->fetchAll(PDO::FETCH_ASSOC);
		//リストで表示する
		$week = ["sun", "mon", "tue", "wed", "thu", "fri", "sat"];
		$week_jp = ["日","月","火","水","木","金","土"];
		date_default_timezone_set('Asia/Tokyo');
		
		if (isset($result)) {
			echo "<table align='center'>";
			echo "<th>","持ち物","</th>";
			echo "<th>","","</th>";
			echo "<th>","","</th>";
			echo "<th>","曜日","</th>";
			echo "<th>","日時","</th>";
			foreach($result as $row){
				echo "<tr>";
				echo "<td>",$row['name'],"</td>";
				echo '<td>';
				echo '<form action="f_" method="post"><input type="hidden" name="item_id" value="',$row['item_id'],'"><input type="submit" value="変更" class="button2"></form>';
				echo '</td><td>';
				echo '<form action="f_delete.php" method="post"><input type="hidden" name="item_id" value="',$row['item_id'],'"><input type="submit" value="削除" class="button2"></form>';
				echo '</td>';

				if(preg_match('/ALL/u', $row["days"])){
					$days = "毎日";
				}else{
					$days = "";
					for($i = 0 ; $i < count($week); $i++){
						if(preg_match('/'.$week[$i].'/u', $row["days"])){//文字列が含まれるなら
							$days .= $week_jp[$i];
						}
					}
				}
				
				echo "<td>",$days,"</td>";
				if(isset($row['notice_datetime'])){
					echo "<td>", date("Y年m月d日 H時i分",strtotime($row['notice_datetime'])),"</td>";
				}
				else{
					echo "<td>","</td>";
				}
				
				echo "</tr>";
			}
			echo "</table>";
		} else {
			echo "持ち物は登録されていません。";
		}
	}catch(Exception $e){
		echo "エラーが発生しました。";
	}
    
?>

</ol>
<h3><a href="f_">持ち物登録</a></h3>
<hr>
<?php
try{
	//SQL文を作る（プレースホルダを使った式）
	$sql ="SELECT notice_time, return_time, check_time FROM user WHERE id = :user_id";
	//プリペアードステートメントを作る
	$stm = $pdo->prepare($sql);
	//プリペアードステートメントに値をバインドする
	$stm->bindValue(':user_id',$_SESSION['user_id'],PDO::PARAM_INT);
	//SQL文を実行する
	$stm->execute();
	//結果の取得（連想配列で受け取る）
	$time = $stm->fetch(PDO::FETCH_ASSOC);
	if(!empty($time["notice_time"] && !empty($time["check_time"]))){
		echo "現在設定されている時間<br>";
		echo "その日の持ち物を通知する時間 : ", $time["notice_time"], "<br>";
		echo "帰りだす時間 　　　　　　　　: ", $time["return_time"], "<br>";
		echo "次の日の持ち物を確認する時間 : ", $time["check_time"], "<br>";
	}else{
		echo "時間を登録してください。", "<br>";
	}

}catch(Exception $e){
    echo "エラーが発生しました
    。";
}
echo "<hr>";

?>

<a href="f_top.php">グループトップに戻る</a><br>
</body>
</html>