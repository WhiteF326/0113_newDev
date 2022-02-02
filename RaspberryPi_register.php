<?php

session_start();

require "RaspberryPi_db.php";

// var_dump($_POST);

try {
    registerRaspberryPi(
        intval($_SESSION["user_id"]),
        $_POST["RaspberryPi_MAC_address"],
        $_POST["smartphone_MAC_address"]
    );
    echo "<meta http-equiv=\"refresh\" content=\"0;RaspberryPi_register_succeed.php\">";
    header("Location: RaspberryPi_register_succeed.php");
}catch(Exception $e){
    echo "<meta http-equiv=\"refresh\" content=\"0;RaspberryPi_register_failed.php\">";
    header("Location: RaspberryPi_register_failed.php");
}