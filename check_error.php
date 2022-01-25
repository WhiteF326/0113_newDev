<?php
//HTMLエスケープ、文字エンコードが異なるか調べるメソッド
function check_error($data, $charset = "UTF-8")
{
    if ($data != htmlspecialchars($data, ENT_QUOTES, $charset)) {
        //特殊文字を使っている場合
        return false;
    }

    if (!mb_check_encoding($data)) {
        //文字エンコードが一致しないとき
        return false;
    }

    return true;
}
