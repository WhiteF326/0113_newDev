<?php session_start(); ?>

<?php
if (isset($_POST["f_id"])) {
	$_SESSION['f_id'] = $_POST['f_id'];
}
?>

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
	//MySQLデータベースに接続する
	require 'dbconnect.php';
	//search_user_name.phpとほぼ一緒
	require 'search_family_user_name.php';
	?>
	<h2><span><?= $list; ?></span></h2>
	<ol class="sample">

		<?php
		require 'family_items_list.php'
		?>

	</ol>
	<h3><a href="family_registration_items.php">持ち物登録</a></h3>
	<hr>
	<?php
	require 'family_time_display.php';
	/*
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
    echo "エラーが発生しました。";
}
*/

	?>
	<hr>"
	<a href="family_top.php">グループトップに戻る</a><br>
</body>

</html>