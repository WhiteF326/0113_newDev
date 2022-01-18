<?php
require "dbconnect.php";

function write($a){
    define("TESTFILE", "./TEST.TEXT");
    $fh = fopen(TESTFILE, "a");
    date_default_timezone_set('Asia/Tokyo');
    $timestamp=time();
    $day=date("y/m/d/H時i分 ",$timestamp);
    fwrite($fh,$day.$a."\n");
}

// POSTされたJSON文字列を取り出し
$json = file_get_contents("php://input");

// JSON文字列をobjectに変換
//   ⇒ 第2引数をtrueにしないとハマるので注意
$contents = json_decode($json, true);


$alexa_id=$contents["name"];
//$alexa_id="amzn1.ask.person.ALVQI5F6NHYHCSPHCGSGZIMK2WY2PRSR4G6NVYYEKF7DXKPJDBVWGSUGFN4IQLUJP3TJJ6ZZVBBTYYPFWRGBX7M7MJJJFIYJDPNJXS6A";


write($alexa_id);


// $weather = get_weather($alexa_id);
// if($weather != false){
//     $text = "本日の天気\n取得した地域 : ". $weather[4]."\n";
//     for($i = 1; $i < 5; $i++){
//         $text .= (String)($i * 3)."時間後の天気 : ". $weather[$i - 1]."\n";
//     }
//     //sending_messages($accessToken, $replyToken, "text", substr($text,0,-1));
// }
// else{
//     //sending_messages($accessToken, $replyToken, "text", "位置情報が登録されていません。\n天気を取得したい場所の位置情報を送信してください。");
// }




//天気が悪い場合、傘を持っていくことを提案する
$weather = get_weather($alexa_id);
if($weather != false){
    $text = "本日の天気\n取得した地域 : ". $weather[4]."\n";
    for($i = 1; $i < 5; $i++){
        $text .= (String)($i * 3)."時間後の天気 : ". $weather[$i - 1]."\n";
    }

    if(in_array("Rain",$weather) || in_array("Drizzle",$weather) || in_array("Thunderstorm",$weather) || in_array("Snow",$weather)){
        $bad_weather = 1;
    }
    else{
        $bad_weather = 0;
    }

    if($bad_weather){
        $text .= "天気が悪くなる可能性があります。傘を持っていってはどうでしょうか。";
        //sending_messages($accessToken, $replyToken, "text", $text);
    }
    else{
        //sending_messages($accessToken, $replyToken, "text", "確認できました。良い一日を。\n\n". substr($text,0,-1));
    }
    $Weather='{
        "bad_weather": "'.$bad_weather.'"
    }';
    echo $Weather;
}

?>





<?php
function get_weather($alexa_id){
    require 'dbconnect.php';
    try{
        //登録されている現在位置を取得
        $sql = "SELECT lat, lon FROM location WHERE id = (SELECT id FROM user WHERE Alexa_id = :id)";
        //プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        //プレースホルダに値をバインドする
        $stm->bindValue(':id', $alexa_id, PDO::PARAM_STR);
        //SQL文を実行する
        $stm->execute();
        //結果を連想配列で取得
        $result = $stm->fetch(PDO::FETCH_ASSOC);

        if(empty($result)){
            echo "エラー";
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
            $weather[$i] = weather_jp($json_decode->list[$i]->weather[0]->main);
        }
        
        $weather[4] = $json_decode->city->name;
    }
    catch(Exception $e){
        echo "エラー";
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
