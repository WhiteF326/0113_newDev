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


	?>
	<hr>"
	<a href="family_top.php">グループトップに戻る</a><br>
</body>

</html>