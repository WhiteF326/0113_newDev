<?php

require "WrappedDBError.php";

date_default_timezone_set("Asia/Tokyo");

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
    /**
     * @var PDO $pdo 内部で使用される PDO オブジェクトです。
     */
    private ?PDO $pdo = null;

    /**
     * @var string[] $error_text エラーメッセージ一覧です。
     * 
     * 0. 指定されたユーザは存在しません。
     * 1. 指定されたユーザは Alexa と連携していません。
     */
    private array $error_text = array(
        0 => "Specified user does not exist.",
        1 => "Specified user data does not cooperate with Alexa.",
        2 => "Please specify \$weekday in string or int.",
        -1 => "We are sorry, but this function is not implemented yet :("
    );

    /**
     * @var int GET_USER_NOTIFY_TIME 通知時刻を取得する為の mode 指定値です。
     */
    public const GET_USER_NOTIFY_TIME = 0;
    /**
     * @var int GET_USER_RETURN_TIME 帰りの時刻を取得する為の mode 指定値です。
     */
    public const GET_USER_RETURN_TIME = 1;
    /**
     * @var int GET_USER_CHECK_TIME 持ち物更新時刻を取得する為の mode 指定値です。
     */
    public const GET_USER_CHECK_TIME = 2;

    /**
     * @var int WEEKDAY_SUNDAY 曜日指定で日曜日を指定する為の値です。
     */
    public const WEEKDAY_SUNDAY = 1;
    /**
     * @var int WEEKDAY_SUNDAY 曜日指定で月曜日を指定する為の値です。
     */
    public const WEEKDAY_MONDAY = 2;
    /**
     * @var int WEEKDAY_SUNDAY 曜日指定で火曜日を指定する為の値です。
     */
    public const WEEKDAY_TUESDAY = 4;
    /**
     * @var int WEEKDAY_SUNDAY 曜日指定で水曜日を指定する為の値です。
     */
    public const WEEKDAY_WEDNESDAY = 8;
    /**
     * @var int WEEKDAY_SUNDAY 曜日指定で木曜日を指定する為の値です。
     */
    public const WEEKDAY_THURSDAY = 16;
    /**
     * @var int WEEKDAY_SUNDAY 曜日指定で金曜日を指定する為の値です。
     */
    public const WEEKDAY_FRIDAY = 32;
    /**
     * @var int WEEKDAY_SUNDAY 曜日指定で土曜日を指定する為の値です。
     */
    public const WEEKDAY_SATURDAY = 64;
    /**
     * @var int WEEKDAY_ALL 曜日指定で全ての曜日を指定するための値です。
     */
    public const WEEKDAY_ALL = 127;

    /**
     * @var int RANGE_WEEK_AGO 遡る期間の指定で 1 週間を指定するための値です。
     */
    public const RANGE_WEEK_AGO = 0;
    /**
     * @var int RANGE_MONTH_AGO 遡る期間の指定で 1 カ月を指定するための値です。
     */
    public const RANGE_MONTH_AGO = 1;

    /**
     * コンストラクタです。
     */
    function __construct()
    {
        // dsn, user name, password
        $this->pdo = new PDO(
            "mysql:host=127.0.0.1;dbname=noneleave;charset=utf8",
            "fukuiohr2",
            "Fukui2021d"
        );
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * コメントテーブル上の通知フラグを変更します。
     * 
     * ### 変更先テーブル
     * 
     * - comment
     * 
     * ### 変更されるフィールド
     * 
     * - alert
     * 
     * ---
     * 
     * ### might change in the further development
     * 
     * - Rename field "alert" to "notification_check".
     * 
     * ---
     * 
     * @param bool $alert_value 変更後の値を指定します。
     * @param int $family_id グループ ID を指定します。
     * @param int $from_id 送信者 ID を指定します。
     * @param int $to_id 受信者 ID を指定します。
     * @return void
     */
    function setAlertFlag(
        bool $alert_value,
        int $family_id,
        int $from_id,
        int $to_id
    ) {
        $sql = "UPDATE comment SET alert = :alert
            WHERE family_id = :family_id and from_id = :from_id
            AND to_id = :to_id";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":alert", $alert_value, PDO::PARAM_BOOL);
        $stm->bindValue(":family_id", $family_id, PDO::PARAM_INT);
        $stm->bindValue(":from_id", $from_id, PDO::PARAM_INT);
        $stm->bindValue(":to_id", $to_id, PDO::PARAM_INT);

        $stm->execute();

        return;
    }

    /**
     * 指定ユーザがアカウントを Alexa と連携しているかを確認します。
     * 
     * ### 参照先テーブル
     * 
     * - user
     * 
     * ### 参照先フィールド
     * 
     * - alexa_id
     * 
     * ---
     * 
     * @param int $user_id ユーザ ID を指定します。
     * @return bool
     * @throws WrappedDBError 指定したユーザ ID に対応するデータがない場合
     */

    function alexaCooperationCheck(int $user_id)
    {
        $sql = "SELECT alexa_id FROM user WHERE user_id = :user_id";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        $stm->execute();

        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        if (count($result)) {
            return $result[0]["alexa_id"] ? true : false;
        } else {
            throw new WrappedDBError($this->error_text[0]);
        }
    }

    /**
     * 指定したユーザ ID と紐づいているパスワードを返します。
     * 
     * ### 参照先テーブル
     * 
     * - Alexa_coop
     * 
     * ### 参照先フィールド
     * 
     * - user_id
     * 
     * ---
     * 
     * ### might change in the further development
     * 
     * - Table "Alexa_coop" will be integrated into the table "user".
     * 
     * ---
     * 
     * @param int $user_id ユーザ ID を指定します。
     * @return int
     * @throws WrappedDBError 指定したユーザ ID に対応するデータがない場合
     * 
     */
    function getAlexaPassword(int $user_id)
    {
        $sql = "SELECT pass_id FROM Alexa_coop WHERE user_id = :user_id";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        $stm->execute();

        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        if (count($result)) {
            return $result[0]["pass_id"];
        } else {
            throw new WrappedDBError($this->error_text[1]);
        }
    }

    /**
     * 指定した Alexa パスワードを追加しようとしたとき、他ユーザと重複するかを返します。
     * 
     * ### 参照先テーブル
     * 
     * - Alexa_coop
     * 
     * ### 参照先フィールド
     * 
     * - pass_id
     * 
     * ---
     * 
     * ### might change in the further development
     * 
     * - Table "Alexa_coop" will be integrated into the table "user".
     * 
     * ---
     * 
     * @param int $pass_id パスワードを指定します。
     * @return bool
     */
    function alexaPasswordUniqueCheck(int $pass_id)
    {
        $sql = "SELECT count(*) as is_duplicated FROM Alexa_coop
            WHERE pass_id = :pass_id";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":pass_id", $pass_id, PDO::PARAM_INT);

        $stm->execute();

        $result = $stm->fetchAll(PDO::FETCH_ASSOC)[0]["is_duplicated"];

        return $result ? true : false;
    }

    /**
     * 指定したユーザ ID と Alexa パスワードを連係情報としてデータベースに追加します。
     * 
     * ### 追加先テーブル
     * 
     * - Alexa_coop
     * 
     * ### 引数により値を指定するフィールド
     * 
     * - user_id
     * - pass_id
     * 
     * ### デフォルト値を用いるフィールド
     * 
     * (なし)
     * 
     * ---
     * 
     * ### might change in the further development
     * 
     * - Table "Alexa_coop" will be integrated into the table "user".
     * - Integrity should be checked by foreign key constraint.
     * So then this method will become to throw `WrappedDBError`.
     * 
     * ---
     * 
     * @param int $user_id ユーザ ID を指定します。
     * @param int $pass_id パスワードを指定します。
     * @return void
     */

    function addAlexaCooperation(int $user_id, int $pass_id)
    {
        $sql = "INSERT INTO Alexa_coop VALUES(:user_id, :pass_id)";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $stm->bindValue(":pass_id", $pass_id, PDO::PARAM_INT);

        $stm->execute();

        return;
    }

    /**
     * 指定したユーザ ID からユーザ名を取得します。
     * 
     * 名前が登録されていない場合(Alexa のみの登録者)は null が返されます。
     * 
     * ### 参照先テーブル
     * 
     * - user
     * 
     * ### 参照先フィールド
     * 
     * - user_id
     * 
     * ---
     * 
     * @param int $user_id ユーザ ID を指定します。
     * @return string|null
     * @throws WrappedDBError 指定したユーザ ID に対応するデータがない場合
     */
    function getUserNameFromId(int $user_id)
    {
        $sql = "SELECT name FROM user WHERE user_id = :user_id";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);

        if (count($result)) {
            $name = $result[0]["name"];
            return $name;
        } else {
            throw new WrappedDBError($this->error_text[0]);
        }
    }

    /**
     * 指定したユーザ ID から、当該ユーザが登録した持ち物情報をすべて取得します。
     * 
     * ### 参照先テーブル
     * 
     * - item
     * - user_item
     * 
     * ### 参照先フィールド
     * 
     * - item.id
     * 
     * - user_item.user_id
     * - user_item.item_id
     * 
     * ---
     * 
     * ### might change in the further development
     * 
     * - Table "item" will be integrated into "user_item".
     * 
     * ---
     * 
     * @param int $user_id ユーザ ID を指定します。
     * @return array|null
     */
    function getNotificationData(int $user_id)
    {
        $sql = "SELECT a.name, b.item_id, b.days, b.notice_datetime
            FROM item a, user_item b
            WHERE b.user_id = :user_id AND a.id = b.item_id";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * 時刻を指定して、通知時刻が前後 2 分に指定されているユーザをすべて取得します。
     * 
     * 厳密には通知時刻が実行時刻の 1 分 30 秒前から 30 秒後までに含まれるかを判定しています。
     * 
     * $mode は DBController::GET_USER_******_TIME を用いて指定してください。
     * 
     * ### 参照先テーブル
     * 
     * - user
     * 
     * ### 参照先フィールド
     * 
     * - notice_time        ($mode = GET_USER_NOTICE_TIME)
     * - return_time        ($mode = GET_USER_RETURN_TIME)
     * - check_time         ($mode = GET_USER_CHECK_TIME)
     * 
     * ---
     * 
     * @param $int time 対象となる時刻を指定します。
     * @param $int mode 判定対象の通知時間を DBController::GET_USER_******_TIME を用いて指定します。
     * @return array
     */
    function getTimeDesignedUser(int $time, int $mode)
    {
        switch ($mode) {
            case DBController::GET_USER_NOTIFY_TIME: {
                    $key_name = "notice_time";
                    break;
                }

            case DBController::GET_USER_RETURN_TIME: {
                    $key_name = "return_time";
                    break;
                }

            case DBController::GET_USER_CHECK_TIME: {
                    $key_name = "check_time";
                    break;
                }
        }

        // before 1m30s from specified time
        $min_time = date("Y-m-d H:i:s", $time - 90);
        $max_time = date("Y-m-d H:i:s", $time + 30);

        $sql = "SELECT user_id, LINE_ID FROM user
            WHERE $key_name BETWEEN '$min_time' AND '$max_time'";

        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 指定したユーザ ID と曜日から、登録された持ち物情報を取得します。
     * 
     * ### 参照先テーブル
     * 
     * - item
     * - user_item
     * 
     * ### 参照先フィールド
     * 
     * - item.id
     * 
     * - user_item.user_id
     * - user_item.item_id
     * - user_item.days
     * 
     * ---
     * 
     * ### might change in the further development
     * 
     * - Table "item" will be integrated into "user_item".
     * 
     * ---
     * 
     * @param int $user_id ユーザ ID を指定します。
     * @param string|int $weekday 曜日を文字列または DBController::WEEKDAY_****** で指定します。
     * @return array
     * @throws WrappedDBError $weekday が string, int のいずれでもない場合
     */
    function getWeekdayDesignedItem(int $user_id, $weekday)
    {
        if (gettype($weekday) == "string") {
            $sql = "SELECT a.name FROM item a, user_item b
                WHERE b.user_id = :user_id AND a.id = b.item_id
                AND (b.days LIKE :weekday OR b.days LIKE 'ALL')";
            $stm = $this->pdo->prepare($sql);
            $stm->bindValue(":user_id", $user_id, PDO::PARAM_INT);
            $stm->bindValue(":weekday", "%" . $weekday . "%", PDO::PARAM_STR);

            $stm->execute();

            return $stm->fetchAll(PDO::FETCH_ASSOC);
        } else if (gettype($weekday) == "integer") {
            $sql = "SELECT a.name FROM user_item
                WHERE user_id = :user_id AND days & :weekday > 0";
            $stm = $this->pdo->prepare($sql);
            $stm->bindValue(":user_id", $user_id, PDO::PARAM_INT);
            $stm->bindValue(":weekday", $weekday, PDO::PARAM_INT);

            $stm->execute();

            return $stm->fetchAll(PDO::FETCH_ASSOC);
        } else {
            throw new WrappedDBError($this->error_text[2]);
        }
    }

    /**
     * 指定したユーザ ID と曜日から、登録された持ち物情報があるか確認します。
     * 
     * ### 参照先テーブル
     * 
     * - user_item
     * 
     * ### 参照先フィールド
     * 
     * - user_item.user_id
     * - user_item.item_id
     * - user_item.days
     * 
     * ---
     * 
     * @param int $user_id ユーザ ID を指定します。
     * @param string|int $weekday 曜日を文字列または DBController::WEEKDAY_****** で指定します。
     * @return bool
     * @throws WrappedDBError $weekday が string, int のいずれでもない場合
     */
    function weekdayDesignedItemCheck(int $user_id, $weekday)
    {
        if (gettype($weekday) == "string") {
            $sql = "SELECT count(*) > 0 as hitCheck FROM user_item
                WHERE user_id = :user_id
                AND (days LIKE :weekday OR days LIKE 'ALL')";
            $stm = $this->pdo->prepare($sql);
            $stm->bindValue(":user_id", $user_id, PDO::PARAM_INT);
            $stm->bindValue(":weekday", "%" . $weekday . "%", PDO::PARAM_STR);

            $stm->execute();

            return $stm->fetchAll(PDO::FETCH_ASSOC)[0]["hitCheck"];
        } else if (gettype($weekday) == "integer") {
            $sql = "SELECT count(*) > 0 as hitCheck FROM user_item
                WHERE user_id = :user_id AND days & :weekday > 0";
            $stm = $this->pdo->prepare($sql);
            $stm->bindValue(":user_id", $user_id, PDO::PARAM_INT);
            $stm->bindValue(":weekday", $weekday, PDO::PARAM_INT);

            $stm->execute();

            return $stm->fetchAll(PDO::FETCH_ASSOC)[0]["hitCheck"];
        } else {
            throw new WrappedDBError($this->error_text[2]);
        }
    }

    /**
     * 指定した受信者ユーザ ID から、当該ユーザが受信するメッセージを取得します。
     * 
     * ### 参照先テーブル
     * 
     * - user
     * - comment
     * 
     * ### 参照先フィールド
     * 
     * - user.user_id
     * 
     * - comment.to_id
     * - comment.from_id
     * - comment.comment
     * 
     * ---
     * 
     * @param int $to_id 受信者ユーザ ID を指定します。
     * @return array
     */
    function getReceivingMessage(int $to_id)
    {
        $sql = "SELECT a.name, b.comment FROM user a, comment b
            WHERE b.to_id = :to_id AND b.from_id = a.user_id
            AND b.comment IS NOT NULL";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":to_id", $to_id, PDO::PARAM_INT);

        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 指定した受信者 ID のメッセージ確認状態を `false` にします。
     * 
     * ### 変更先テーブル
     * 
     * - comment
     * 
     * ### 変更先フィールド
     * 
     * - LINE_check
     * 
     * ---
     * 
     * @param int $to_id 受信者ユーザ ID を指定します。
     * @return void
     */
    function markAsUnread(int $to_id)
    {
        $sql = "UPDATE comment SET LINE_check = false WHERE to_id = :to_id";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":to_id", $to_id, PDO::PARAM_INT);

        $stm->execute();
    }

    /**
     * 受信者 ID を指定して、指定日から 1 週間遡って確認されなかった通知をカウントします。
     * 
     * $range は DBController::RANGE_*****_AGO で指定してください。
     * 
     * ### 参照先テーブル
     * 
     * - send_log
     * 
     * ### 参照先フィールド
     * 
     * - datetime
     * - to_id
     * 
     * ---
     * 
     * @param int $to_id 受信者ユーザ ID を指定します。
     * @param int $time 対象となる時刻を設定します。
     * @param int $range 遡る期間を DBController::RANGE_*****_AGO で指定します。
     */
    function countUnreadChecks(int $to_id, int $time, int $range)
    {
        // 1 week ago = 604800 seconds ago, and shift 60 seconds after
        if ($range == 0) {
            // a week ago
            $min_time = date("Y-m-d 00:01:00", strtotime("-1 week", $time));
        } else {
            $min_time = date("Y-m-d 00:01:00", strtotime("-1 month", $time));
        }
        $max_time = date("Y-m-d H:i:s", $time + 60);

        $sql = "SELECT count(*) As unread_count FROM send_log
            WHERE datetime BETWEEN '$min_time' AND '$max_time'
            AND to_id = :to_id";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":to_id", $to_id, PDO::PARAM_INT);

        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC)[0]["unread_count"];
    }

    /**
     * 時刻を指定して、確認されていない通知を取得します。
     * 
     * 厳密には、5 分前 ±2 分 に送信されており、未確認であるような通知の送信ログを取得します。
     * また、±2 分 の判定は通知時刻が実行時刻の 1 分 30 秒前から 30 秒後までに含まれるかを判定しています。
     * 
     * $mode は DBController::GET_USER_******_TIME を用いて指定してください。
     * 
     * ### 参照先テーブル
     * 
     * - user
     * - send_log
     * 
     * ### 参照先フィールド
     * 
     * - user.notice_time        ($mode = GET_USER_NOTICE_TIME)
     * - user.return_time        ($mode = GET_USER_RETURN_TIME)
     * - user.check_time         ($mode = GET_USER_CHECK_TIME)
     * - user.user_id
     * 
     * - send_log.datetime
     * - send_log.confirm_check
     * 
     * ---
     * 
     * @param int $time 対象時刻を指定します。
     * @param int $mode 判定対象の通知時間を DBController::GET_USER_******_TIME を用いて指定します。
     * @return array
     */
    function getUnreadUser(int $time, int $mode)
    {
        switch ($mode) {
            case DBController::GET_USER_NOTIFY_TIME: {
                    $key_name = "notice_time";
                    break;
                }

            case DBController::GET_USER_RETURN_TIME: {
                    $key_name = "return_time";
                    break;
                }

            case DBController::GET_USER_CHECK_TIME: {
                    $key_name = "check_time";
                    break;
                }
        }

        // before 1m30s from specified time, and more before 5m
        $min_time = date("Y-m-d H:i:s", $time - 90 - 300);
        // after 30s from specified time, and before 5m from it
        $max_time = date("Y-m-d H:i:s", $time + 30 - 300);

        $sql = "SELECT DISTINCT a.user_id, a.name, a.LINE_id
            FROM user a, send_log b
            WHERE a.$key_name BETWEEN '$min_time' AND '$max_time'
            AND b.datetime BETWEEN '$min_time' AND '$max_time'
            AND b.confirm_check = false AND a.user_id = b.to_id";

        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * データベースに LINE メッセージ送信のログを書き込みます。
     * 
     * ### 注意
     * 
     * $datetime には文字列化する前の時刻情報を与えてください。
     * 
     * ### 追加先テーブル
     * 
     * - send_log
     * 
     * ---
     * 
     * @param int $to_id 受信者 ID を指定します。
     * @param string $message ログ本文を指定します。
     * @param int $datetime 日時情報を UNIX time で指定します。
     * @return void
     */
    function saveLog(int $to_id, string $message, int $datetime)
    {
        $sql = "INSERT INTO send_log(to_id, message, datetime)
            VALUES (:to_id, :message, :datetime)";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":to_id", $to_id, PDO::PARAM_INT);
        $stm->bindValue(":message", $message, PDO::PARAM_STR);
        $stm->bindValue(
            ":datetime",
            date("Y/m/d H:i:s", $datetime),
            PDO::PARAM_INT
        );
        $stm->execute();

        return;
    }
};
