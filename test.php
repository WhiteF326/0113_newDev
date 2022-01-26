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
require "dbconnect.php";

$result = $pdo->query("SELECT * FROM family")->fetchAll(PDO::FETCH_ASSOC);
// var_dump($pdo->query("SELECT * FROM family")->fetchAll(PDO::FETCH_ASSOC));
foreach($result as $row){
    $salt = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    for($i = 1; $i <= 16; $i <<= 1) $salt = $salt. $salt;
    $salt = str_shuffle($salt);
    substr($salt, 0, 13);

    $sql = "UPDATE family SET pass = :hashed_pass, salt = :salt WHERE id = :id";
    $stm = $pdo->prepare($sql);
    $hashed_pass = hash(
        "SHA256", $row["pass"]. $salt
    );
    $stm->bindValue(":hashed_pass", $hashed_pass, PDO::PARAM_STR);
    $stm->bindValue(":salt", $salt, PDO::PARAM_STR);
    $stm->bindValue(":id", $row["id"], PDO::PARAM_INT);
    $stm->execute();
}
?>
    </pre>
</body>

</html>