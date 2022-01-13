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

// �f�o�b�O�p�Ƀ_���v
$alexa_id=$contents["name"];
//��������
//$alexa_id="amzn1.ask.person.ALVQI5F6NHYHCSPHCGSGZIMK2WY2PRSR4G6NVYYEKF7DXKPJDBVWGSUGFN4IQLUJP3TJJ6ZZVBBTYYPFWRGBX7M7MJJJFIYJDPNJXS6A";
$str="alexa=".$alexa_id;
write($str);


try{
    //$alexa_id=$_POST;
    //SQL�������i�v���[�X�z���_���g�������j
    $sql='SELECT count(comment) FROM comment a,user b WHERE a.to_id=b.id AND b.Alexa_id=:Alexa_id';
    //�v���y�A�[�h�X�e�[�g�����g�����
    $stm = $pdo->prepare($sql);
    //�v���y�A�[�h�X�e�[�g�����g�ɒl���o�C���h����
    $stm->bindValue(':Alexa_id',$alexa_id,PDO::PARAM_STR);
    //SQL�������s����
    $stm->execute();
    //���ʂ̎擾�i�A�z�z��Ŏ󂯎��j
    $result = $stm->fetch(PDO::FETCH_COLUMN);
    // if($result){
    //     foreach($result as $row){
    //         $a=$row["name"];
    //         $b=$row["comment"];
    //     }
            
    // }
    // if($stm->execute()){
    //     write("ok");
    // }
   //require "write.php";
}catch(Exception $e){
     write( "�G���[���������܂���
    �B");
}



$flag='{
    "comment_flag": "'.$result.'"
}';

echo $flag;
?>