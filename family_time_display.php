<?php

require 'dbconnect.php';
require 'DBController.php';
$DBControl = new DBController();

try{
	//選択したグループメンバーの登録している時間を取得する
	$time = $DBControl->getAllTimeByUserId($_POST["f_id"]);
	if(!empty($time["notice_time"]) && !empty($time["check_time"])) : ?>
		<p>現在設定されている時間</p>
		<p>その日の持ち物を通知する時間 : <?= $time["notice_time"]; ?></p>
        <?php
        if(!empty($time["return_time"])) : ?>
            <p>帰りだす時間 　　　　　　　　: <?= $time["return_time"]; ?></p>
        <?php
        else : ?>
            <p>帰りだす時間は登録されていません。</p>
        <?php
        endif ; ?>
		<p>次の日の持ち物を確認する時間 : <?=  $time["check_time"]; ?></p>
	<?php
    else : ?>
		<p>時間が登録されていません。</p>
	<?php
    endif ;

}catch(Exception $e){
    echo "エラーが発生しました。";
}