<?php
require "dbconect.php";

function write($a){
    define("TESTFILE", "./TEST.TEXT");
    $fh = fopen(TESTFILE, "a");
    date_default_timezone_set('Asia/Tokyo');
    $timestamp=time();
    $day=date("y/m/d/H��i�� ",$timestamp);
    fwrite($fh,$day.$a."\n");
}

// POST���ꂽJSON����������o��
$json = file_get_contents("php://input");

// JSON�������object�ɕϊ�
//   �� ��2������true�ɂ��Ȃ��ƃn�}��̂Œ���
$contents = json_decode($json, true);


$id=$contents["id"];

write($id);

// $id=123456;
//echo $id;

try{
    //SQL�������i�v���[�X�z���_���g�������j
    $sql = "SELECT count(*) FROM Alexa_coop WHERE pass_id = :id";
   

    //�v���y�A�[�h�X�e�[�g�����g�����
    $stm = $pdo->prepare($sql);
    //�v���y�A�[�h�X�e�[�g�����g�ɒl���o�C���h����
    $stm->bindValue(':id',(int)$id,PDO::PARAM_INT);
    //SQL�������s����
    $stm->execute();
    //���ʂ̎擾�i�A�z�z��Ŏ󂯎��j
    $result = $stm->fetch(PDO::FETCH_COLUMN);
    // if(isset($result)){
    //     $a = "";
    //     foreach($result as $row){
           
    //         $a.=$row["name"]."�A";
    //     }
    //     write($a);
    // }

}catch(Exception $e){
    //write("error");
}

$Number='{
    "number": "'.$result.'"
}';

echo $Number;
?>