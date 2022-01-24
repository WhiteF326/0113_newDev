<?php
try{
	//選択したグループメンバーの登録している時間を取得する
	$sql ="SELECT notice_time, return_time, check_time
    FROM user WHERE id = :family_id";
	
	$stm = $pdo->prepare($sql);
	$stm->bindValue(':family_id',$_SESSION['f_id'],PDO::PARAM_INT);
	$stm->execute();
	$time = $stm->fetch(PDO::FETCH_ASSOC);
	if(!empty($time["notice_time"]) && !empty($time["check_time"])) : ?>
		<p>現在設定されている時間</p>
		<p>その日の持ち物を通知する時間 : <?= $time["notice_time"] ?></p>
        <?php
        if(!empty($time["return_time"])) : ?>
            <p>帰りだす時間 　　　　　　　　: <?= $time["return_time"] ?></p>
        <?php
        else : ?>
            <p>帰りだす時間は登録されていません。</p>
        <?php
        endif ; ?>
		<p>次の日の持ち物を確認する時間 : <?=  $time["check_time"] ?></p>
	<?php
    else : ?>
		<p>時間が登録されていません。</p>
	<?php
    endif ;

}catch(Exception $e){
    echo "エラーが発生しました。";
}