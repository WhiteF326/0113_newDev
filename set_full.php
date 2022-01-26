<?php
// convert all days -> weekdays on user_item
ini_set("display_errors", 1);
require "dbconnect.php";

$week = ["sun", "mon", "tue", "wed", "thu", "fri", "sat"];

$result = $pdo->query("SELECT * from user_item")->fetchAll(PDO::FETCH_ASSOC);
// var_dump($result);
foreach ($result as $row) {
    $sql = "UPDATE user_item SET weekdays = :weekdays
        WHERE user_id = :user_id AND item_id = :item_id";
    $stm = $pdo->prepare($sql);
    $stm->bindValue(":user_id", $row["user_id"], PDO::PARAM_INT);
    $stm->bindValue(":item_id", $row["item_id"], PDO::PARAM_INT);

    var_dump($row);

    if ($row["days"] == null) {
        $stm->bindValue(":weekdays", null, PDO::PARAM_NULL);
    } else if($row["days"] == "ALL"){
        $stm->bindValue(":weekdays", 127, PDO::PARAM_INT);
    } else {
        $res = 0;
        $prm = 1;
        foreach($week as $weekday){
            if(gettype(stripos($row["days"], $weekday)) != "boolean"){
                $res += $prm;
            }
            $prm *= 2;
        }
        $stm->bindValue(":weekdays", $res, PDO::PARAM_INT);
        var_dump($row["days"], $res);
    }

    $stm->execute();
}
