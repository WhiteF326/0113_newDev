<!DOCTYPE html>
<?php
ini_set("display_errors", 1);
?>
<html>

<head>
    <title>test file</title>
</head>

<body>
    <pre style="font-size: 1.2em;">
<?php


require "DBController.php";

$dbController = new DBController();

// var_dump($dbController->alexaCooperationCheck(2));
// $dbController->setAlertFlag(false, 1, 2, 8);
// $dbController->saveLog(1, "test message", strtotime("now"));
// echo $dbController->getAlexaPassword(1);
// $dbController->addAlexaCooperation(2, 234345);
// echo $dbController->getUserNameFromId(1);
// $dbController->getNotificationData(8);
// var_dump($dbController->getTimeDesignedUser(strtotime(date("23:00:00")), DBController::GET_USER_CHECK_TIME));
// var_dump($dbController->getWeekdayDesignedItem(8, "fri"));
// var_dump($dbController->getWeekdayDesignedItem(8, DBController::WEEKDAY_FRIDAY));
// var_dump($dbController->weekdayDesignedItemCheck(7, "fri"));
// var_dump($dbController->getReceivingMessage(8));
// $dbController->markAsUnread(8);
// var_dump($dbController->countUnreadChecks(1, strtotime("2021-11-01 08:00:00"), DBController::RANGE_MONTH_AGO));
// var_dump($dbController->getUnreadUser(strtotime("2021-11-03 08:05:00"), DBController::GET_USER_NOTIFY_TIME));
// var_dump($dbController->getItemFromTime(strtotime("2021-11-03 13:01:00")));
// var_dump($dbController->registerUser("testestestest"));
// var_dump($dbController->withdrawal("testestestest"));
// echo strtolower(date("D"));
?>
    </pre>
</body>

</html>