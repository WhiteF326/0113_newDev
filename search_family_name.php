<?php
require 'dbconnect.php';
require 'DBController.php';
$DBControl = new DBController();

try {
    //グループ名を検索する
    $family_name = "[" . $DBControl->searchFamilyName($_POST['family_id']) . "]";
    
} catch (Exception $e) {
    echo "エラーが発生思案した。";
}
