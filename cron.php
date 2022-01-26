<script>
    window.onload = () => {
        if (window.location.href.endsWith("cron.php")) {
            window.location.href = "LINE_registration.php";
        }
    };
</script>

<?php
$week_name = ["%sun%", "%mon%", "%tue%", "%wed%", "%thu%", "%fri%", "%sat%"];//date("w")でとったときの0-6の対応配列
date_default_timezone_set('Asia/Tokyo');
$time = strtotime("now");

//MySQLデータベースに接続する
require 'dbconnect.php';
try{
    //持ち物確認の指定時間と一致したユーザーに持ち物を通知する
    $sql = "SELECT id, LINE_id FROM user WHERE notice_time >= :min_time AND notice_time < :max_time";
    //プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    //プレースホルダに値をバインドする
    $stm->bindValue(':min_time', date("H:i:s", $time - 90), PDO::PARAM_STR);
    $stm->bindValue(':max_time', date("H:i:s", $time + 30), PDO::PARAM_STR);
    //SQL文を実行する
    $stm->execute();
    //結果を連想配列で取得
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $row){
        //Alexaのみの登録であればスキップする
        if(is_null($row["LINE_id"])){
            continue;
        }
        //一致ユーザーに登録されているアイテムを検索
        $sql = "SELECT a.name FROM item a, user_item b WHERE b.user_id = :id AND a.id = b.item_id AND (b.days LIKE :day OR b.days LIKE 'ALL')";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        $stm->bindValue(':id', $row["id"], PDO::PARAM_INT);
        //プレースホルダに値をバインドする
        $stm->bindValue(':day', $week_name[date("w")], PDO::PARAM_STR);
        $stm->execute();
        //結果を連想配列で取得
        $result2 = $stm->fetchAll(PDO::FETCH_ASSOC);

        //登録されているアイテムを列挙する
        $item = "";
        foreach($result2 as $row2){
            $item .= $row2["name"]. "\n";
        }

        //アイテムが登録されている場合
        if($item != ""){
            confirm_button($row["LINE_id"], $item);
            //持ち物確認のログをデータベースに登録する
            $sql = "INSERT INTO send_log(to_id, message, datetime) VALUES (:id, :item, :datetime)";
            //プリペアドステートメントを作る
            $stm = $pdo->prepare($sql);
            //プレースホルダに値をバインドする
            $stm->bindValue(':id', $row["id"], PDO::PARAM_INT);
            $stm->bindValue(':item', $item."持ち物確認", PDO::PARAM_STR);
            $stm->bindValue(':datetime', date("Y-m-d H:i:s", $time), PDO::PARAM_STR);
            //SQL文を実行する
            $stm->execute();
            
            $fp = fopen("sample.txt", "a");
            fwrite($fp, date("Y/m/d H:i:s", $time). " に、id = " .$row['id']. "へ持ち物確認1回目を送信しました。\n");
            fclose($fp);

            //メッセージがあれば送信する
            $sql = "SELECT user.name, comment.comment FROM user, comment WHERE comment.to_id = :id AND comment.from_id = user.id AND comment.comment != null";
            //プリペアドステートメントを作る
            $stm = $pdo->prepare($sql);
            //プレースホルダに値をバインドする
            $stm->bindValue(':id', $row["id"], PDO::PARAM_INT);
            //SQL文を実行する
            $stm->execute();
            //結果を連想配列で取得
            $result2 = $stm->fetchAll(PDO::FETCH_ASSOC);
            if(!empty($result2)){
                $comment = "";
                foreach($result2 as $row2){
                    $comment = $row2["name"]. "さんからのメッセージ\n" . $row2["comment"];
                    sending_pushmessages($row["LINE_id"], $comment);
                }
                //メッセージが送られた人のDBを更新する
                $sql = "UPDATE comment SET LINE_check = false WHERE to_id = :id";
                $stm = $pdo->prepare($sql);
                $stm->bindValue(':id', $row["id"], PDO::PARAM_INT);
                $stm->execute();
            } 
        }

        //月曜日なら一週間の記録を送信する
        if(date('w', $time) == '1'){
            $sql = "SELECT COUNT(*) AS num FROM send_log WHERE datetime >= :min_time AND datetime < :max_time AND confirm_check = false AND to_id = :id";
            //プリペアドステートメントを作る
            $stm = $pdo->prepare($sql);
            //プレースホルダに値をバインドする
            $stm->bindValue(':min_time', date("Y-m-d 00:00:00",strtotime("-1 week", strtotime("+1 min",$time))), PDO::PARAM_STR);
            $stm->bindValue(':max_time', date("Y-m-d 00:00:00",strtotime("+1 min",$time)), PDO::PARAM_STR);
            $stm->bindValue(':id', $row["id"], PDO::PARAM_INT);
            //SQL文を実行する
            $stm->execute();
            //結果を取得
            $result2 = $stm->fetch(PDO::FETCH_ASSOC);
            if($result2["num"] == 0){
                sending_pushmessages($row["LINE_id"], "先週は忘れたものはありません。\nこの調子で頑張りましょう。");
            }
            else{
                sending_pushmessages($row["LINE_id"], "先週は ". $result2["num"]. " 回確認を忘れています\n今週は気を付けましょう。");
            }
        }

        //毎月1日なら先月の記録を送信する
        if(date('j', $time) == '1'){
            $sql = "SELECT COUNT(*) AS num FROM send_log WHERE datetime >= :min_time AND datetime < :max_time AND confirm_check = false AND to_id = :id";
            //プリペアドステートメントを作る
            $stm = $pdo->prepare($sql);
            //プレースホルダに値をバインドする
            $stm->bindValue(':min_time', date("Y-m-d 00:00:00",strtotime("-1 month", strtotime("+1 min",$time))), PDO::PARAM_STR);
            $stm->bindValue(':max_time', date("Y-m-d 00:00:00",strtotime("+1 min",$time)), PDO::PARAM_STR);
            $stm->bindValue(':id', $row["id"], PDO::PARAM_INT);
            //SQL文を実行する
            $stm->execute();
            //結果を取得
            $result2 = $stm->fetch(PDO::FETCH_ASSOC);

            if($result2["num"] == 0){
                sending_pushmessages($row["LINE_id"], "先月は忘れたものはありません。\n素晴らしいですね。この調子です。");
            }
            else{
                sending_pushmessages($row["LINE_id"], "先月は ". $result2["num"]. " 回確認を忘れています\n今月は気を付けて。");
            }
        }
    }

    //持ち物を確認していない時にメッセージを送る
    $sql = "SELECT DISTINCT a.id, a.name, a.LINE_id FROM user a, send_log b WHERE a.notice_time >= :min_time AND a.notice_time < :max_time AND b.datetime >= :min_time2 AND b.datetime < :max_time2 AND b.confirm_check = false AND a.id = b.to_id";
    //プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    //プレースホルダに値をバインドする
    $stm->bindValue(':min_time', date("Y-m-d H:i:s", $time - 390), PDO::PARAM_STR);
    $stm->bindValue(':max_time', date("Y-m-d H:i:s", $time - 270), PDO::PARAM_STR);
    $stm->bindValue(':min_time2', date("Y-m-d H:i:s", $time - 390), PDO::PARAM_STR);
    $stm->bindValue(':max_time2', date("Y-m-d H:i:s", $time - 270), PDO::PARAM_STR);
    //SQL文を実行する
    $stm->execute();
    //結果を連想配列で取得
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $row){
        if(is_null($row["LINE_id"])){
            continue;
        }
        //確認していないことをメッセージ送信者に伝える
        $sql = "SELECT user.LINE_id FROM user, comment WHERE comment.to_id = :id AND comment.from_id = user.id AND comment.from_id != comment.to_id AND comment.alert = true";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':id', $row["id"], PDO::PARAM_INT);
        //SQL文を実行する
        $stm->execute();
        //結果を連想配列で取得
        $result2 = $stm->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result2)){
            foreach($result2 as $row2){
                sending_pushmessages($row2["LINE_id"], $row["name"]. "さんが忘れ物の確認を完了していません。");
            }
        }
        sending_pushmessages($row["LINE_id"], "確認出来たらボタンを押してください。");

        $fp = fopen("sample.txt", "a");
        fwrite($fp, date("Y/m/d H:i:s", $time). " に、id = " .$row['id']. "へ持ち物確認2回目を送信しました。\n");
        fclose($fp);
    }

    //帰りの確認の指定時間と一致したユーザーに持ち物を通知する
    $sql = "SELECT id, LINE_id FROM user WHERE return_time >= :min_time AND return_time < :max_time";
    //プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    //プレースホルダに値をバインドする
    $stm->bindValue(':min_time', date("H:i:s", $time - 90), PDO::PARAM_STR);
    $stm->bindValue(':max_time', date("H:i:s", $time + 30), PDO::PARAM_STR);
    //SQL文を実行する
    $stm->execute();
    //結果を連想配列で取得
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $row){
        //Alexaのみの登録であればスキップする
        if(is_null($row["LINE_id"])){
            continue;
        }
        //一致ユーザーに登録されているアイテムを検索
        $sql = "SELECT a.name FROM item a, user_item b WHERE b.user_id = :id AND a.id = b.item_id AND (b.days LIKE :day OR b.days LIKE 'ALL')";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        $stm->bindValue(':id', $row["id"], PDO::PARAM_INT);
        //プレースホルダに値をバインドする
        $stm->bindValue(':day', $week_name[date("w")], PDO::PARAM_STR);
        $stm->execute();
        //結果を連想配列で取得
        $result2 = $stm->fetchAll(PDO::FETCH_ASSOC);

        //登録されているアイテムを列挙する
        $item = "";
        foreach($result2 as $row2){
            $item .= $row2["name"]. "\n";
        }

        //アイテムが登録されている場合
        if($item != ""){
            confirm_button($row["LINE_id"], $item);
            //持ち物確認のログをデータベースに登録する
            $sql = "INSERT INTO send_log(to_id, message, datetime) VALUES (:id, :item, :datetime)";
            //プリペアドステートメントを作る
            $stm = $pdo->prepare($sql);
            //プレースホルダに値をバインドする
            $stm->bindValue(':id', $row["id"], PDO::PARAM_INT);
            $stm->bindValue(':item', $item."持ち物確認", PDO::PARAM_STR);
            $stm->bindValue(':datetime', date("Y-m-d H:i:s", $time), PDO::PARAM_STR);
            //SQL文を実行する
            $stm->execute();
            
            $fp = fopen("sample.txt", "a");
            fwrite($fp, date("Y/m/d H:i:s", $time). " に、id = " .$row['id']. "へ持ち物確認1回目を送信しました。\n");
            fclose($fp);

            //メッセージがあれば送信する
            $sql = "SELECT user.name, comment.comment FROM user, comment WHERE comment.to_id = :id AND comment.from_id = user.id AND comment.comment != null";
            //プリペアドステートメントを作る
            $stm = $pdo->prepare($sql);
            //プレースホルダに値をバインドする
            $stm->bindValue(':id', $row["id"], PDO::PARAM_INT);
            //SQL文を実行する
            $stm->execute();
            //結果を連想配列で取得
            $result2 = $stm->fetchAll(PDO::FETCH_ASSOC);
            if(!empty($result2)){
                $comment = "";
                foreach($result2 as $row2){
                    $comment = $row2["name"]. "さんからのメッセージ\n" . $row2["comment"];
                    sending_pushmessages($row["LINE_id"], $comment);
                }
                //メッセージが送られた人のDBを更新する
                $sql = "UPDATE comment SET LINE_check = false WHERE to_id = :id";
                $stm = $pdo->prepare($sql);
                $stm->bindValue(':id', $row["id"], PDO::PARAM_INT);
                $stm->execute();
            } 
        }
    }

    //持ち物更新の指定時間と一致したユーザーに持ち物を通知する
    $sql = "SELECT id, LINE_id FROM user WHERE check_time >= :min_time AND check_time < :max_time";
    //プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    //プレースホルダに値をバインドする
    $stm->bindValue(':min_time', date("H:i:s", $time - 90), PDO::PARAM_STR);
    $stm->bindValue(':max_time', date("H:i:s", $time + 30), PDO::PARAM_STR);
    //SQL文を実行する
    $stm->execute();
    //結果を連想配列で取得
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $row){
        if(is_null($row["LINE_id"])){
            continue;
        }
        //登録されているかを検索
        $sql = "SELECT user_id FROM user_item WHERE user_id = :id AND (days LIKE :day OR days LIKE 'ALL')";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':id', $row["id"], PDO::PARAM_INT);
        $stm->bindValue(':day', $week_name[date("w")], PDO::PARAM_STR);
        //SQL文を実行する
        $stm->execute();
        //結果を連想配列で取得
        $result2 = $stm->fetch(PDO::FETCH_ASSOC);
        if(!empty($result2)){
            check_button($row["LINE_id"], $row["id"]);
            //更新確認のログを送信
            $sql = "INSERT INTO send_log(to_id, message, datetime) VALUES (:id, :item, :datetime)";
            //プリペアドステートメントを作る
            $stm = $pdo->prepare($sql);
            //プレースホルダに値をバインドする
            $stm->bindValue(':id', $row["id"], PDO::PARAM_INT);
            $stm->bindValue(':item', "持ち物更新確認", PDO::PARAM_STR);
            $stm->bindValue(':datetime', date("Y/m/d H:i:s", $time), PDO::PARAM_STR);
            //SQL文を実行する
            $stm->execute();

            $fp = fopen("sample.txt", "a");
            fwrite($fp, date("Y/m/d H:i:s", $time). " に、id = " .$row['id']. "へ更新確認1回目を送信しました。\n");
            fclose($fp);
        }
    }

    //持ち物を更新していない時にメッセージを送る
    $sql = "SELECT DISTINCT a.id, a.name, a.LINE_id FROM user a, send_log b 
        WHERE a.check_time >= :min_time AND a.check_time < :max_time AND b.datetime >= :min_time2 AND b.datetime < :max_time2 AND b.confirm_check = false AND a.id = b.to_id";

    //プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    //プレースホルダに値をバインドする
    $stm->bindValue(':min_time', date("Y-m-d H:i:s", $time - 390), PDO::PARAM_STR);
    $stm->bindValue(':max_time', date("Y-m-d H:i:s", $time - 270), PDO::PARAM_STR);
    $stm->bindValue(':min_time2', date("Y-m-d H:i:s", $time - 390), PDO::PARAM_STR);
    $stm->bindValue(':max_time2', date("Y-m-d H:i:s", $time - 270), PDO::PARAM_STR);
    //SQL文を実行する
    $stm->execute();
    //結果を連想配列で取得
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $row){
        if(is_null($row["LINE_id"])){
            continue;
        }
        $sql = "SELECT user.LINE_id FROM user, comment WHERE comment.to_id = :id AND comment.from_id = user.id AND comment.from_id != comment.to_id AND comment.alert = true";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':id', $row["id"], PDO::PARAM_INT);
        //SQL文を実行する
        $stm->execute();
        //結果を連想配列で取得
        $result2 = $stm->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result2)){
            foreach($result2 as $row2){
                sending_pushmessages($row2["LINE_id"], $row["name"]. "さんが更新確認を完了していません。");
            }
        }
        sending_pushmessages($row["LINE_id"], "更新出来たらボタンを押してください。");

        $fp = fopen("sample.txt", "a");
        fwrite($fp, date("Y/m/d H:i:s", $time). " に、id = " .$row['id']. "へ更新確認2回目を送信しました。\n");
        fclose($fp);
    }


    //指定時間の持ち物が一致したユーザーにLINEを送る
    $sql = "SELECT a.id, a.LINE_id, c.name FROM user a, user_item b, item c WHERE a.id = b.user_id AND c.id = b.item_id AND b.notice_datetime >= :min_time AND b.notice_datetime < :max_time";
    //プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    //プレースホルダに値をバインドする
    $stm->bindValue(':min_time', date("Y-m-d H:i:s", $time - 90), PDO::PARAM_STR);
    $stm->bindValue(':max_time', date("Y-m-d H:i:s", $time + 30), PDO::PARAM_STR);
    //SQL文を実行する
    $stm->execute();
    //結果を連想配列で取得
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $row){
        if(is_null($row["LINE_id"])){
            continue;
        }
        confirm_button($row["LINE_id"], $row["name"]."\n");

        //持ち物確認のログを送信
        $sql = "INSERT INTO send_log(to_id, message, datetime) VALUES (:id, :item, :datetime)";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':id', $row["id"], PDO::PARAM_INT);
        $stm->bindValue(':item', $row["name"]."\n指定時間の持ち物確認", PDO::PARAM_STR);
        $stm->bindValue(':datetime', date("Y/m/d H:i:s", $time), PDO::PARAM_STR);
        //SQL文を実行する
        $stm->execute();

        $fp = fopen("sample.txt", "a");
        fwrite($fp, date("Y/m/d H:i:s", $time). " に、id = " .$row['id']. "へ忘れ物確認1回目を送信しました。\n");
        fclose($fp);

        //グループ間のコメント
        $sql = "SELECT user.id, user.name, comment.comment FROM user, comment WHERE comment.to_id = :id AND comment.from_id = user.id AND comment.alert = true";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':id', $row["id"], PDO::PARAM_INT);
        //SQL文を実行する
        $stm->execute();
        //結果を連想配列で取得
        $result2 = $stm->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result2)){
            $comment = "";
            foreach($result2 as $row2){
                $comment = $row2["name"]. "さんからのメッセージ\n" . $row2["comment"];
                sending_pushmessages($row["LINE_id"], $comment);
            }
            
            //メッセージが送られた人のDBを更新する
            $sql = "UPDATE comment SET LINE_check = false WHERE to_id = :id";
            //プリペアドステートメントを作る
            $stm = $pdo->prepare($sql);
            //プレースホルダに値をバインドする
            $stm->bindValue(':id', $row["id"], PDO::PARAM_INT);
            //SQL文を実行する
            $stm->execute();
        } 
    }
  
    //持ち物を確認していない時にメッセージを送る
    $sql = "SELECT DISTINCT a.id, a.name, a.LINE_id FROM user a, user_item b, send_log c 
    WHERE a.id = b.user_id AND b.user_id = c.to_id AND b.notice_datetime >= :min_time2 AND b.notice_datetime < :max_time2 AND c.datetime >= :min_time 
    AND c.datetime < :max_time AND c.confirm_check = false";

    //プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    //プレースホルダに値をバインドする
    $stm->bindValue(':min_time', date("Y-m-d H:i:s", $time - 390), PDO::PARAM_STR);
    $stm->bindValue(':max_time', date("Y-m-d H:i:s", $time - 270), PDO::PARAM_STR);
    $stm->bindValue(':min_time2', date("Y-m-d H:i:s", $time - 390), PDO::PARAM_STR);
    $stm->bindValue(':max_time2', date("Y-m-d H:i:s", $time - 270), PDO::PARAM_STR);
    //SQL文を実行する
    $stm->execute();
    //結果を連想配列で取得
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $row){
        if(is_null($row["LINE_id"])){
            continue;
        }
        $sql = "SELECT user.LINE_id FROM user, comment WHERE comment.to_id = :id AND comment.from_id = user.id AND comment.from_id != comment.to_id AND comment.alert = true";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':id', $row["id"], PDO::PARAM_INT);
        //SQL文を実行する
        $stm->execute();
        //結果を連想配列で取得
        $result2 = $stm->fetchAll(PDO::FETCH_ASSOC);
        foreach($result2 as $row2){
            sending_pushmessages($row2["LINE_id"], $row["name"]. "さんが忘れ物を確認していません。");
        }
        sending_pushmessages($row["LINE_id"], "確認したらボタンを押してください。");

        $fp = fopen("sample.txt", "a");
        fwrite($fp, date("Y/m/d H:i:s", $time). " に、id = " .$row['id']. "へ忘れ物確認2回目を送信しました。\n");
        fclose($fp);
    }

    //グループでコメントを送った人に対してメッセージを送る（確認が取れた人を取得）
    $sql = "SELECT a.id, a.name FROM user a, send_log b WHERE b.confirm_check = true AND b.datetime >= :min_time 
        AND b.datetime < :max_time AND a.id = b.to_id";
    //プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    //プレースホルダに値をバインドする
    $stm->bindValue(':min_time', date("Y-m-d H:i:s", $time - 1810), PDO::PARAM_STR);
    $stm->bindValue(':max_time', date("Y-m-d H:i:s", $time - 10), PDO::PARAM_STR);
    //SQL文を実行する
    $stm->execute();
    //結果を連想配列で取得
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $row){
        //グループでコメントを送った人に対して確認メッセージを送る（確認が取れた人にコメントを送信した人のLINE_idを取得）
        $sql = "SELECT a.LINE_id FROM user a, comment b WHERE b.to_id = :id AND a.id = b.from_id AND b.LINE_check = false AND b.to_id != b.from_id AND b.alert = true";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':id', $row["id"], PDO::PARAM_INT);
        //SQL文を実行する
        $stm->execute();
        //結果を連想配列で取得
        $result2 = $stm->fetchAll(PDO::FETCH_ASSOC);
        foreach($result2 as $row2){
            sending_pushmessages($row2["LINE_id"], $row["name"]. "さんの忘れ物の確認が取れました。");
        }

        //メッセージが送られた人のSQLを更新する
        $sql = "UPDATE comment SET LINE_check = true WHERE to_id = :id";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':id', $row["id"], PDO::PARAM_INT);
        //SQL文を実行する
        $stm->execute();
    }

    //昨日に通知が来た人を検索
    $sql = "SELECT DISTINCT a.to_id, b.LINE_id FROM send_log a, user b 
        WHERE a.datetime >= :min_datetime AND a.datetime < :max_datetime AND a.to_id = b.id AND b.notice_time >= :min_time AND b.notice_time < :max_time";
    //プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    //プレースホルダに値をバインドする
    $stm->bindValue(':min_datetime', date("Y-m-d 00:00:00",strtotime("-1day", strtotime("+1 min",$time))), PDO::PARAM_STR);
    $stm->bindValue(':max_datetime', date("Y-m-d 00:00:00",strtotime("+1 min",$time)), PDO::PARAM_STR);
    $stm->bindValue(':min_time', date("H:i:s", $time - 90), PDO::PARAM_STR);
    $stm->bindValue(':max_time', date("H:i:s", $time + 30), PDO::PARAM_STR);
    //SQL文を実行する
    $stm->execute();
    //結果を連想配列で取得
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $row){
        $sql = "SELECT COUNT(*) AS num FROM send_log 
            WHERE datetime >= :min_time AND datetime < :max_time AND confirm_check = false AND to_id = :id";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':min_time', date("Y-m-d 00:00:00",strtotime("-1 day", strtotime("+1 min",$time))), PDO::PARAM_STR);
        $stm->bindValue(':max_time', date("Y-m-d 00:00:00",strtotime("+1 min",$time)), PDO::PARAM_STR);
        $stm->bindValue(':id', $row["to_id"], PDO::PARAM_INT);
        //SQL文を実行する
        $stm->execute();
        //結果を連想配列で取得
        $result2 = $stm->fetch(PDO::FETCH_ASSOC);
        if($result2["num"] == 0){
            sending_pushmessages($row["LINE_id"], "昨日は忘れ物をしていません。\n次の日も頑張りましょう！！");
        }
        else{
            sending_pushmessages($row["LINE_id"], "昨日は ". $result2["num"]. "回確認を忘れています。\n気を付けてください。");
        }
    }

    $fp = fopen("sample.txt", "a");
    fwrite($fp, date("Y/m/d H:i:s", $time). " にPHPを実行しました。\n");
    fclose($fp);

}catch(Exception $e){
    $fp = fopen("sample.txt", "a");
    fwrite($fp, date("Y/m/d H:i:s"). " にエラーが発生しました。\n");
    fclose($fp);
    exit;
}
?>

<?php
// メッセージの送信（ユーザーID使用）
function sending_pushmessages($user_id, $return_message_text){
    $message_data = [
        "type" => "text",
        "text" => $return_message_text
    ];
    
    // ポストデータ
    $post_data = [
        "to"       => $user_id,
        "messages" => [$message_data]
    ];
    
    // curl実行
    $ch = curl_init("https://api.line.me/v2/bot/message/push");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $post_data, JSON_UNESCAPED_UNICODE ));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charser=UTF-8',
        'Authorization: Bearer ' . 'xNI28KLon+2C6/22M/f7EDOITUVAWdtVt+eKuhyYd21nO3Ie1FuCLVExVJd/7poTgU2Rjfo+NXoKkGI6944McsS+m6hw5kdj3i5kzDlUt0IxVAK3mWafdDP+R+lfpUMHDGmkbmp0xwLwRjWR1emeNgdB04t89/1O/w1cDnyilFU='
    ));
    $result = curl_exec($ch);
    $result = json_decode($result);
    curl_close($ch);
    
    if(isset($result->message) ){
        // エラー処理： $result->messageにエラーメッセージが入っている。
    }
}
?>

<?php
// 確認ボタンの送信
function confirm_button($user_id, $item){
    $message_data = [
        "type" => "template",
        "altText" => "持ち物の確認です。忘れずにボタンを押してください。",
        "template" => array(
            "type" => "buttons",
            "text" => $item. "忘れ物はありませんか？",
            "actions" => [
                array(
                    "type" => "postback",
                    "label" => "はい",
                    "data" => "confirm_ok"
                )
            ]
        )
    ];
    
    // ポストデータ
    $post_data = [
        "to"       => $user_id,
        "messages" => [$message_data]
    ];
    
    // curl実行
    $ch = curl_init("https://api.line.me/v2/bot/message/push");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $post_data, JSON_UNESCAPED_UNICODE ));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charser=UTF-8',
        'Authorization: Bearer ' . 'xNI28KLon+2C6/22M/f7EDOITUVAWdtVt+eKuhyYd21nO3Ie1FuCLVExVJd/7poTgU2Rjfo+NXoKkGI6944McsS+m6hw5kdj3i5kzDlUt0IxVAK3mWafdDP+R+lfpUMHDGmkbmp0xwLwRjWR1emeNgdB04t89/1O/w1cDnyilFU='
    ));
    $result = curl_exec($ch);
    $result = json_decode($result);
    curl_close($ch);
    
    if(isset($result->message)){
        // エラー処理： $result->messageにエラーメッセージが入っている。
    }
}
?>

<?php
// 更新ボタンの送信
function check_button($user_id, $id){
    $message_data = [
        "type" => "template",
        "altText" => "持ち物の更新が完了したら必ずボタンを押してください。",
        "template" => array(
            "type" => "buttons",
            "text" => "持ち物の更新は終わりましたか？",
            "actions" => [
                array(
                    "type" => "postback",
                    "label" => "はい",
                    "data" => "check_ok"
                ),
                array(
                    "type" => "uri",
                    "label" => "更新する",
                    "uri" => "https://fukuiohr2.sakura.ne.jp/2021/wasurenai/confirm.php?id=$id"
                )
            ]
        )
    ];
    
    // ポストデータ
    $post_data = [
        "to"       => $user_id,
        "messages" => [$message_data]
    ];
    
    // curl実行
    $ch = curl_init("https://api.line.me/v2/bot/message/push");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $post_data, JSON_UNESCAPED_UNICODE ));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charser=UTF-8',
        'Authorization: Bearer ' . 'xNI28KLon+2C6/22M/f7EDOITUVAWdtVt+eKuhyYd21nO3Ie1FuCLVExVJd/7poTgU2Rjfo+NXoKkGI6944McsS+m6hw5kdj3i5kzDlUt0IxVAK3mWafdDP+R+lfpUMHDGmkbmp0xwLwRjWR1emeNgdB04t89/1O/w1cDnyilFU='
    ));
    $result = curl_exec($ch);
    $result = json_decode($result);
    curl_close($ch);
    
    if(isset($result->message)){
        // エラー処理： $result->messageにエラーメッセージが入っている。
    }
}
?>