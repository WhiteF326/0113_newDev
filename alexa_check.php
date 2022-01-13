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


$alexa_id=$contents["name"];
//$alexa_id="amzn1.ask.person.AKJSQKKTKSR2ACJPQM4YQ7IT7GIH2VANC5HXSYKUAZ6ACLSW37WT7E34DDGD3GKJD6VNQBJHWBY4QBMKASVJN2OMIB2UG2EMQ3FY64JC";


write($alexa_id);



try{
    //SQL�������i�v���[�X�z���_���g�������j
    $sql = "SELECT Alexa_check FROM user WHERE Alexa_id = :alexa_id";
    //LIKE 'amzn1.ask.person.AKJSQKKTKSR2ACJPQM4YQ7IT7GIH2VANC5HXSYKUAZ6ACLSW37WT7E34DDGD3GKJD6VNQBJHWBY4QBMKASVJN2OMIB2UG2EMQ3FY64JC%'";


    //�v���y�A�[�h�X�e�[�g�����g�����
    $stm = $pdo->prepare($sql);
    //�v���y�A�[�h�X�e�[�g�����g�ɒl���o�C���h����
    $stm->bindValue(':alexa_id',$alexa_id,PDO::PARAM_STR);
    //SQL�������s����
    $stm->execute();
    //���ʂ̎擾�i�A�z�z��Ŏ󂯎��j
    $result = $stm->fetch(PDO::FETCH_COLUMN);
    if(isset($result)){
        write($result);
    }

}catch(Exception $e){
    //write("error");
}

$Data='{
    "flag": "'.$result.'"
}';

echo $Data;
?>