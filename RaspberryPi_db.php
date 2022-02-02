<?php
ini_set("display_errors", 1);
function isUserCooperatedWithRaspberryPi(int $user_id)
{
    require "dbconnect.php";
    return $pdo->query(
        "SELECT count(*) as is_cooperated FROM raspberrypi_cooperation
            WHERE user_id = $user_id"
    )->fetch(PDO::FETCH_ASSOC)["is_cooperated"];
}

function registerRaspberryPi(
    int $user_id,
    string $RaspberryPi_MAC_address,
    string $smartphone_MAC_address
) {
    require "dbconnect.php";
    return $pdo->query(
        "INSERT INTO raspberrypi_cooperation
            VALUES(
                null,
                '$RaspberryPi_MAC_address',
                '$smartphone_MAC_address',
                $user_id
            )
        ON DUPLICATE KEY
            UPDATE
                RaspberryPi_MAC_address = '$RaspberryPi_MAC_address',
                smartphone_MAC_address = '$smartphone_MAC_address'"
    )->fetch(PDO::FETCH_ASSOC);
}
