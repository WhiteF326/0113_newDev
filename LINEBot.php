<?php
 
$accessToken = 'xNI28KLon+2C6/22M/f7EDOITUVAWdtVt+eKuhyYd21nO3Ie1FuCLVExVJd/7poTgU2Rjfo+NXoKkGI6944McsS+m6hw5kdj3i5kzDlUt0IxVAK3mWafdDP+R+lfpUMHDGmkbmp0xwLwRjWR1emeNgdB04t89/1O/w1cDnyilFU=';
date_default_timezone_set('Asia/Tokyo');

//ユーザーからのメッセージ取得
$json_string = file_get_contents('php://input');
$json_object = json_decode($json_string);
 
//取得データ
$replyToken = $json_object->{"events"}[0]->{"replyToken"};        //返信用トークン
$user_id = $json_object->{"events"}[0]->{"source"}->{"userId"};     //ユーザーID取得
$message_type = $json_object->{"events"}[0]->{"message"}->{"type"};    //メッセージタイプ
$message_text = $json_object->{"events"}[0]->{"message"}->{"text"};    //メッセージ内容
$type = $json_object->{"events"}[0]->{"type"};//イベントタイプ　ポストバックを受け取るときに使用
$postdata = $json_object->{"events"}[0]->{"postback"}->{"data"};//ポストバックしたデータ名
$week_name = ["sun", "mon", "tue", "wed", "thu", "fri", "sat"];//date("w")でとったときの0-6の対応配列

if($type == "follow"){
    require 'dbconnect.php';
    //現在登録されている名前を検索
    $sql = "SELECT id FROM user WHERE LINE_id = :id";
    //プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    //プレースホルダに値をバインドする
    $stm->bindValue(':id', $user_id, PDO::PARAM_STR);
    //SQL文を実行する
    $stm->execute();
    //結果を連想配列で取得
    $id = $stm->fetch(PDO::FETCH_COLUMN);
    if(empty($id)){
        //未登録の場合
        $sql = "SELECT MAX(id) FROM user";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //SQL文を実行する
        $stm->execute();
        //結果を連想配列で取得
        $id = $stm->fetch(PDO::FETCH_COLUMN);
        //新規id番号
        if(!empty($id)){
           $id += 1; 
        }else{
            $id = 1;
        }
        //SQL文を作る
        $sql = "INSERT INTO user(id, LINE_id) VALUES (:id, :user_id)";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':id', $id, PDO::PARAM_INT);
        $stm->bindValue(':user_id', $user_id, PDO::PARAM_STR);
        //SQL文を実行する
        if($stm->execute()){
            sending_messages($accessToken, $replyToken, "text", "ユーザーを登録しました。以下のURLから忘れ物を登録できます。\nhttps://fukuiohr2.sakura.ne.jp/2021/wasurenai/confirm.php?id=$id");
            exit;
        }
        else{
            sending_messages($accessToken, $replyToken, "text", "エラーが発生しました。もう一度お試しください。");
            exit;
        }
    }
    else{
        //登録済みの場合
        sending_messages($accessToken, $replyToken, "text", "以下のURLから忘れ物を登録できます。\nhttps://fukuiohr2.sakura.ne.jp/2021/wasurenai/confirm.php?id=$id");
        exit;
    }
}
else if($type == "unfollow"){ 
    require 'dbconnect.php';
    try{
        $sql = "DELETE FROM user_item WHERE user_id = (SELECT id FROM user WHERE LINE_id = :id)";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':id', $user_id, PDO::PARAM_STR);
        //SQL文を実行する
        $stm->execute();

        $sql = "DELETE FROM location WHERE id = (SELECT id FROM user WHERE LINE_id = :id)";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':id', $user_id, PDO::PARAM_STR);
        //SQL文を実行する
        $stm->execute();

        $sql = "DELETE FROM send_log WHERE to_id = (SELECT id FROM user WHERE LINE_id = :id)";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':id', $user_id, PDO::PARAM_STR);
        //SQL文を実行する
        $stm->execute(); 

        $sql = "DELETE FROM comment WHERE (from_id = (SELECT id FROM user WHERE LINE_id = :id) OR to_id = (SELECT id FROM user WHERE LINE_id = :id2))";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':id', $user_id, PDO::PARAM_STR);
        $stm->bindValue(':id2', $user_id, PDO::PARAM_STR);
        //SQL文を実行する
        $stm->execute(); 

        $sql = "DELETE FROM family_user WHERE user_id = (SELECT id FROM user WHERE LINE_id = :id)";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':id', $user_id, PDO::PARAM_STR);
        //SQL文を実行する
        $stm->execute(); 

        $sql = "DELETE FROM user WHERE LINE_id = :id";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':id', $user_id, PDO::PARAM_STR);
        //SQL文を実行する
        $stm->execute(); 

    }catch(Exception $e){
        $fp = fopen("sample.txt", "a");
        fwrite($fp, date("Y/m/d H:i:s"). " にunfollowエラーが発生しました。\n");
        fclose($fp);
        exit;
    }
}
else if($message_text == "登録"|| $message_text == "とうろく"){
    //MySQLデータベースに接続する
    require 'dbconnect.php';
    try{
        //現在登録されている名前を検索
        $sql = "SELECT id FROM user WHERE LINE_id = :id";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':id', $user_id, PDO::PARAM_STR);
        //SQL文を実行する
        $stm->execute();
        //結果を連想配列で取得
        $id = $stm->fetch(PDO::FETCH_COLUMN);
        if(!empty($id)){
            sending_messages($accessToken, $replyToken, "text", "以下のURLから忘れ物を確認できます。\nhttps://fukuiohr2.sakura.ne.jp/2021/wasurenai/confirm.php?id=$id");
            exit;
        }
        else{
            sending_messages($accessToken, $replyToken, "text", "エラーが発生しました。");
            exit;
        }
    }
    catch(Exception $e){
        sending_messages($accessToken, $replyToken, "text", "エラーが発生しました。もう一度お試しください。");
        exit;
    }
}
else if($message_text == "確認" || $message_text == "かくにん"){
    //MySQLデータベースに接続する
    require 'dbconnect.php';
    try{
        //現在登録されている名前を検索
        $sql = "SELECT a.name, b.days, b.notice_datetime FROM item a, user_item b, user c WHERE c.LINE_id = :id AND b.user_id = c.id AND a.id = b.item_id";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':id', $user_id, PDO::PARAM_STR);
        //SQL文を実行する
        $stm->execute();
        //結果を連想配列で取得
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        if(empty($result)){
            //未登録の場合
            sending_messages($accessToken, $replyToken, "text", "登録されていません。必要な持ち物を登録してください。");
            exit;
         }
        else{
            //登録済みの場合
    
            $item = "";
            foreach($result as $value){
                if(preg_match("/". $week_name[(date('w') + 1) % 7]. "/u", $value["days"]) || preg_match("/ALL/u", $value["days"]) ||
                    (strtotime("+1day",strtotime(date("Y-m-d 00:00:00"))) <= strtotime($value["notice_datetime"]) &&
                    (strtotime("+2day",strtotime(date("Y-m-d 00:00:00"))) > strtotime($value["notice_datetime"])))){
                    $item .= $value['name'] . "\n";
                }
            }
            if($item == ""){
                //登録物がない場合
                sending_messages($accessToken, $replyToken, "text", "明日の持ち物は登録されていません。");
                exit;
            }
            else{
                sending_messages($accessToken, $replyToken, "text", $item. "以上が明日の持ち物として登録されています。");
                exit;
            }
        }
     }
    catch(Exception $e){
    sending_messages($accessToken, $replyToken, "text", "エラーが発生しました。もう一度お試しください。");
    exit;
    }
}
else if($message_text == "使い方"|| $message_text == "つかいかた"){
    sending_messages($accessToken, $replyToken, "text", 
"使用方法
    
以下のメッセージを送ることで持ち物の確認や登録が行えます。
    
「登録」
持ち物の登録を行うURLが送られてきます。
    
「確認」
明日、必要なものがメッセージで表示されます。

登録が完了すると、指定時間に持ち物が通知されます。
付属しているボタンを押すことで確認が取れるので、必ず押してください。
");
}
//天気を表示する
else if($message_text == "天気" || $message_text == "てんき"){
    $weather = get_weather($user_id);
    if($weather != false){
        $text = "本日の天気\n取得した地域 : ". $weather[0]."\n";
        for($i = 0; $i < 4; $i++){
            $text .= $weather["time"][$i]."時の天気 : ". $weather["weather"][$i]. "\n　　 気温 : ". $weather["temp"][$i]."℃\n";
        }
        sending_messages($accessToken, $replyToken, "text", substr($text,0,-1));
    }
    else{
        sending_messages($accessToken, $replyToken, "text", "位置情報が登録されていません。\n天気を取得したい場所の位置情報を送信してください。");
    }
}
//位置情報を解除する
else if($message_text == "天気解除" || $message_text == "てんきかいじょ"){
    require 'dbconnect.php';
    try{
        $sql = "DELETE FROM location WHERE id = (SELECT id FROM user WHERE LINE_id = :id)";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':id', $user_id, PDO::PARAM_STR);
        //SQL文を実行する
        $stm->execute();
    }catch(Exception $e){
        $fp = fopen("sample.txt", "a");
        fwrite($fp, date("Y/m/d H:i:s"). " にunfollowエラーが発生しました。\n");
        fclose($fp);
        exit;
    }
}
//ボタンのポストバックデータが送られてきたとき
else if($type == "postback"){
    if($postdata == "confirm_ok"){
        //MySQLデータベースに接続する
        require 'dbconnect.php';
        try{
            $sql = "SELECT DISTINCT a.id, a.name FROM user a, send_log b 
                WHERE a.LINE_id = :id AND a.id = b.to_id AND b.confirm_check = false AND b.datetime >= :min_datetime AND b.datetime <= :now_datetime";
            //プリペアドステートメントを作る
            $stm = $pdo->prepare($sql);
            //プレースホルダに値をバインドする
            $stm->bindValue(':id', $user_id, PDO::PARAM_STR);
            $stm->bindValue(':min_datetime', date("Y-m-d H:i:s", strtotime('-30min')), PDO::PARAM_STR);
            $stm->bindValue(':now_datetime', date("Y-m-d H:i:s", strtotime('now')), PDO::PARAM_STR);
            //SQL文を実行する
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if(empty($result)){
                sending_messages($accessToken, $replyToken, "text", "通知から30分以上過ぎているか、連続で押しています。");
                exit;
            }

            //ログを更新する
            $sql = "UPDATE send_log SET confirm_check = true WHERE to_id = :id AND datetime >= :min_datetime AND datetime <= :now_datetime";
            //プリペアドステートメントを作る
            $stm = $pdo->prepare($sql);
            //プレースホルダに値をバインドする
            $stm->bindValue(':id', $result["id"], PDO::PARAM_INT);
            $stm->bindValue(':min_datetime', date("Y-m-d H:i:s", strtotime('-30min')), PDO::PARAM_STR);
            $stm->bindValue(':now_datetime', date("Y-m-d H:i:s", strtotime('now')), PDO::PARAM_STR);
            //SQL文を実行する
            $stm->execute();

            //天気が悪い場合、傘を持っていくことを提案する
            $weather = get_weather($user_id);
            if($weather != false){
                $text = "本日の天気\n取得した地域 : ". $weather[0]."\n";
                for($i = 0; $i < 4; $i++){
                    $text .= $weather["time"][$i]."時の天気 : ". $weather["weather"][$i]. "\n　　 気温 : ". $weather["temp"][$i]."℃\n";
                }

                if(in_array("雨",$weather["weather"]) || in_array("霧雨",$weather["weather"]) || in_array("雷雨",$weather["weather"]) || in_array("雪",$weather["weather"]) || in_array("台風",$weather["weather"])){
                    $text .= "天気が悪くなる可能性があります。傘を持っていってはどうでしょうか。";
                    sending_messages($accessToken, $replyToken, "text", $text);
                }
                else{
                    sending_messages($accessToken, $replyToken, "text", "確認できました。良い一日を。\n\n". substr($text,0,-1));
                }
            }
        }
        catch(Exception $e){
            sending_messages($accessToken, $replyToken, "text", "エラーが発生しました。もう一度お試しください。");
            exit;
        }
    }
    else if($postdata == "check_ok"){
        //MySQLデータベースに接続する
        require 'dbconnect.php';
        try{
            $sql = "SELECT DISTINCT a.id, a.name FROM user a, send_log b 
                WHERE LINE_id = :id AND a.id = b.to_id AND b.confirm_check = false AND b.datetime >= :min_datetime AND b.datetime <= :now_datetime 
                AND a.check_time >= :min_time AND a.check_time <= :now_time";
            //プリペアドステートメントを作る
            $stm = $pdo->prepare($sql);
            //プレースホルダに値をバインドする
            $stm->bindValue(':id', $user_id, PDO::PARAM_STR);
            $stm->bindValue(':min_datetime', date("Y-m-d H:i:s", strtotime('-30min')), PDO::PARAM_STR);
            $stm->bindValue(':now_datetime', date("Y-m-d H:i:s", strtotime('now')), PDO::PARAM_STR);
            $stm->bindValue(':min_time', date("Y-m-d H:i:s", strtotime('-30min')), PDO::PARAM_STR);
            $stm->bindValue(':now_time', date("Y-m-d H:i:s", strtotime('now')), PDO::PARAM_STR);
            //SQL文を実行する
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if(empty($result)){
                sending_messages($accessToken, $replyToken, "text", "通知から30分以上過ぎているか、違うボタンを押しています。");
                exit;
            }

            // ログを更新する
            $sql = "UPDATE send_log SET confirm_check = true WHERE to_id = :id AND datetime >= :min_datetime AND datetime <= :now_datetime";
            //プリペアドステートメントを作る
            $stm = $pdo->prepare($sql);
            //プレースホルダに値をバインドする
            $stm->bindValue(':id', $result["id"], PDO::PARAM_INT);
            $stm->bindValue(':min_datetime', date("Y-m-d H:i:s", strtotime('-30min')), PDO::PARAM_STR);
            $stm->bindValue(':now_datetime', date("Y-m-d H:i:s", strtotime('now')), PDO::PARAM_STR);
            //SQL文を実行する
            $stm->execute();

            //登録されているアイテムを検索
            for($i = 1 ; $i <= 7 ; $i++){
                $sql = "SELECT a.name FROM item a, user_item b WHERE b.user_id = :id AND a.id = b.item_id AND ((b.days LIKE :day OR b.days LIKE 'ALL') OR (b.notice_datetime >= :min_datetime AND b.notice_datetime < :max_datetime))";
                //プリペアドステートメントを作る
                $stm = $pdo->prepare($sql);
                //プレースホルダに値をバインドする
                $stm->bindValue(':id', $result["id"], PDO::PARAM_INT);
                $stm->bindValue(':day', "%".$week_name[(date("w") + $i) % 7]."%", PDO::PARAM_STR);
                $stm->bindValue(':min_datetime', date("Y-m-d 00:00:00", strtotime('+'.$i.'day')), PDO::PARAM_STR);
                $stm->bindValue(':max_datetime', date("Y-m-d 00:00:00", strtotime('+'.($i + 1).'day')), PDO::PARAM_STR);
                //SQL文を実行する
                $stm->execute();
                //結果を連想配列で取得
                $result2 = $stm->fetchAll(PDO::FETCH_ASSOC);
                $item = "";
                foreach($result2 as $row2){
                    $item .= $row2["name"]. "\n";
                }
                if($item != ""){
                    sending_messages($accessToken, $replyToken, "text", "次は". $i ."日後に、\n". $item. "が登録されています。");
                    break;
                }
            }
            if($item == ""){
                sending_messages($accessToken, $replyToken, "text", "登録されている持ち物はありません。");
            }

        }
        catch(Exception $e){
            sending_messages($accessToken, $replyToken, "text", "エラーが発生しました。もう一度お試しください。");
            exit;
        }
    }
}
//位置情報が送られてきたとき
else if($message_type == "location"){
    require 'dbconnect.php';
    $latitude = $json_object->{"events"}[0]->{"message"}->{"latitude"};
    $longitude = $json_object->{"events"}[0]->{"message"}->{"longitude"};
    $latitude = (string)$latitude;
    $longitude = (string)$longitude;

    try{
        //カラムを追加する、存在すればカラムを更新する
        $sql = "INSERT INTO location(id, lat, lon) VALUES ((SELECT id FROM user WHERE LINE_id = :id), :latitude, :longitude)
                ON DUPLICATE KEY UPDATE lat = :latitude2, lon = :longitude2";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':id', $user_id, PDO::PARAM_STR);
        $stm->bindValue(':latitude', $latitude, PDO::PARAM_STR);
        $stm->bindValue(':longitude', $longitude, PDO::PARAM_STR);
        $stm->bindValue(':latitude2', $latitude, PDO::PARAM_STR);
        $stm->bindValue(':longitude2', $longitude, PDO::PARAM_STR);
        //SQL文を実行する
        if($stm->execute()){
            sending_messages($accessToken, $replyToken, "text", "位置情報を登録しました。");
        }
        else{
            sending_messages($accessToken, $replyToken, "text", "位置情報登録に失敗しました。\nもう一度お試し下さい。");
        }
    }
    catch(Exception $e){
        sending_messages($accessToken, $replyToken, "text", "エラーが発生しました。もう一度お試しください。");
        exit;
    }  
}
?>

<?php
//メッセージの送信
function sending_messages($accessToken, $replyToken, $message_type, $return_message_text){
    //レスポンスフォーマット
    $response_format_text = [
        "type" => $message_type,
        "text" => $return_message_text
    ];
 
    //ポストデータ
    $post_data = [
        "replyToken" => $replyToken,
        "messages" => [$response_format_text]//←これに2つ以上のテキストを入れると連続して返信することができる
    ];
 
    //curl実行
    $ch = curl_init("https://api.line.me/v2/bot/message/reply");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charser=UTF-8',
        'Authorization: Bearer ' . $accessToken
    ));
    curl_exec($ch);
    curl_close($ch);
}
?>

<?php
// メッセージの送信（ユーザーID使用）
function sending_pushmessages($accessToken, $user_id, $message_type, $return_message_text){
    $message_data = [
        "type" => $message_type,
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
        'Authorization: Bearer ' . $accessToken
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
//OpenWeatherAPIから天気情報を取得
function get_weather($user_id){
    require 'dbconnect.php';
    try{
        //登録されている現在位置を取得
        $sql = "SELECT lat, lon FROM location WHERE id = (SELECT id FROM user WHERE LINE_id = :id)";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':id', $user_id, PDO::PARAM_STR);
        //SQL文を実行する
        $stm->execute();
        //結果を連想配列で取得
        $result = $stm->fetch(PDO::FETCH_ASSOC);

        if(empty($result)){
            return false;
        }

        $lat = $result["lat"];
        $lon = $result["lon"];

        $appid = "1980f952dce1984ef63584809a770204";
        $url = "http://api.openweathermap.org/data/2.5/forecast?lat=".$lat."&lon=".$lon."&units=metric&lang=ja&APPID=".$appid;

        $json = file_get_contents( $url );
        $json = mb_convert_encoding( $json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN' );
        $json_decode = json_decode( $json );

        for($i = 0; $i < 4; $i++){
            $weather["weather"][$i] = weather_jp($json_decode->list[$i]->weather[0]->main);
            $weather["temp"][$i] = $json_decode->list[$i]->main->temp;
            $weather["time"][$i] = date("H",$json_decode->list[$i]->dt);
        }
        $weather[0] = $json_decode->city->name;
    }
    catch(Exception $e){
        return false;
    }
    
    return $weather;
}
?>

<?php
//天気を日本語に翻訳
function weather_jp($weather){
    switch($weather){
        case "Rain":
            return "雨";
        case "Thunderstorm":
            return "雷雨";
        case "Dizzle":
            return "霧雨";
        case "Snow":
            return "雪";
        case "Mist":
            return "靄";
        case "Smoke":
            return "煙";
        case "Haze":
            return "霧";
        case "Dust":
            return "ほこり";
        case "Fog":
            return "霧";
        case "Sand":
            return "砂";
        case "Squall":
            return "スコール";
        case "Tornado":
            return "竜巻";
        case "Clear":
            return "快晴";
        case "Clouds":
            return "曇り";
    }
}
?>