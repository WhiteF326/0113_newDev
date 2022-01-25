<?php
//check_errorメソッドに検査したいデータを挿入する
function check_error($data, $charset = "UTF-8")
{
    if ($data != htmlspecialchars($data, ENT_QUOTES, $charset)) {
        return false;
    }

    if (!mb_check_encoding($data)) {
        //文字エンコードが一致しないとき
        return false;
    }

    return true;
}
