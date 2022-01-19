<?php
/**
 * Database Access Controller Class.
 * 
 * データベースに対する操作を全てメソッド化し内包するクラスです。
 * 
 * @author Mitta Kazuki
 * @since 1.0
 */
class DBController
{
    private ?PDO $pdo = null;
    function DBController()
    {
        // dsn, user name, password
        $this->pdo = new PDO(
            "mysql:host=mysql640.db.sakura.ne.jp;dbname=fukuiohr2_wasurenai;charset=utf8",
            "fukuiohr2",
            "Fukui2021d"
        );
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * データベース上の
     */
    function setAlertFlag(
        bool $alert_value, int $family_id,
        int $from_id, int $to_id
    ){
        //
    }

    /**
     * データベースに LINE メッセージ送信のログを書き込みます。
     * 
     * $datetime には文字列化する前の時刻情報を与えてください。
     * 
     * @param int $to_id 受信者 ID を与えます。
     * @param string $message ログ本文を与えます。
     * @param int $datetime 日時情報を UNIX time で与えます。
     */
    function saveLog(int $to_id, string $message, int $datetime){
        $sql = "INSERT INTO send_log(to_id, message, datetime)
            VALUES (:to_id, :message, :datetime)";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":to_id", $to_id);
        $stm->bindValue(":message", $message);
        $stm->bindValue(":datetime", date("Y/m/d H:i:s", $datetime));
        $stm->execute();

        return;
    }
};
