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
    $sql = "UPDATE family SET pass = :hashed_pass WHERE id = :id";
    $stm = $pdo->prepare($sql);
    $hashed_pass = hash(
        "SHA256", $row["pass"]
    );
    $stm->bindValue(":hashed_pass", $hashed_pass, PDO::PARAM_STR);
    $stm->bindValue(":id", $row["id"], PDO::PARAM_INT);
    $stm->execute();
}
?>
    </pre>
</body>

</html>