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
     * @var int FOR_LINEBOT
     */
    public const FOR_LINEBOT = 3;

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
     * @var int RANGE_MONTH_AGO 遡る期間の指定で 1 カ月を指定するための値です。
     */
    public const RANGE_DAY_AGO = 0;
    /**
     * @var int RANGE_WEEK_AGO 遡る期間の指定で 1 週間を指定するための値です。
     */
    public const RANGE_WEEK_AGO = 1;
    /**
     * @var int RANGE_MONTH_AGO 遡る期間の指定で 1 カ月を指定するための値です。
     */
    public const RANGE_MONTH_AGO = 2;

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
     * - Field "alert" will be renamed to "notification_check".
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
            return false;
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
     * 指定したユーザに対するメッセージを変更します。
     * 
     * ### 追加先テーブル
     * 
     * - comment
     * 
     * ### 引数により値を指定するフィールド
     * 
     * - family_id
     * - from_id
     * - to_id
     * - comment
     * - alert
     * 
     * ### デフォルト値を用いるフィールド
     * 
     * (なし)
     * 
     * ---
     * 
     * @param int $family_id グループ ID を指定します。
     * @param int $from_id 送信者 ID を指定します。
     * @param int $to_id 受信者 ID を指定します。
     * @param string $comment コメントを指定します。
     * @param bool $alert_value 変更後の値を指定します。
     * @return void
     */

    function setComment(
    int $family_id,
    int $from_id,
    int $to_id,
    string $comment,
    bool $alert_value
    )
    {
        $sql = "INSERT INTO comment(family_id,from_id,to_id,comment)
        VALUES (:family_id,:from_id,:to_id,:comment)
        ON DUPLICATE KEY UPDATE comment = :comment2, alert = :alert";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':family_id', $family_id, PDO::PARAM_INT);
        $stm->bindValue(':from_id', $from_id, PDO::PARAM_INT);
        $stm->bindValue(':to_id', $to_id, PDO::PARAM_INT);

        if (empty($comment)) {
            //コメントが登録されていなければ通知をオフにする
            $stm->bindValue(':comment', null, PDO::PARAM_NULL);
            $stm->bindValue(':comment2', null, PDO::PARAM_NULL);
            $stm->bindValue(':alert', $alert_value, PDO::PARAM_INT);
        } else {
            //コメントが設定されていれば通知をオンにする
            $stm->bindValue(':comment', $comment, PDO::PARAM_STR);
            $stm->bindValue(':comment2', $comment, PDO::PARAM_STR);
            $stm->bindValue(':alert', $alert_value, PDO::PARAM_INT);
        }
    }

    /**
     * 指定したユーザ ID からユーザ名を取得します。
     * 
     * 名前が登録されていない場合(Alexa のみの登録者)は null が返されます。
     * 該当するユーザが存在しない場合は空文字列を返します。
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
            return "";
        }
    }

    /**
     * 指定した LINE ID からユーザ名を取得します。
     * 
     * ### 注意
     * 
     * `getUserIdFromLINEId` および `getUserNameFromId` により実装されています。
     * 
     * ---
     * 
     * @param string $LINE_id LINE ID を指定します。
     * @return string|null
     */
    function getUserNameFromLINEId(string $LINE_id)
    {
        $user_id = $this->getUserIdFromLINEId($LINE_id);
        return $this->getUserNameFromId($user_id);
    }

    /**
     * 次に追加するユーザの ID を取得します。
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
     * @return int
     */
    private function getNextUserId()
    {
        $sql = "SELECT max(user_id) + 1 as next_user_id FROM user";
        return $this->pdo->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC)[0]["next_user_id"];
    }

    /**
     * 指定した LINE ID でユーザを登録します。
     * 
     * ### 返り値
     * 
     * ```
     * {
     *   "succeed" => (bool),
     *   "ascribed_user_id" => (int),
     * }
     * ```
     * 
     * - "succeed" はユーザ登録が成功したか否かを示す bool 値です。
     * - "ascribed_user_id" はユーザ ID として割り当てられた値です。
     * ユーザ登録に失敗した場合は、"ascribed_user_id" をもつユーザは実際には存在しないことに注意してください。
     * 
     * ### 追加先テーブル
     * 
     * - user
     * 
     * ### 引数により値を指定するフィールド
     * 
     * - LINE_id
     * 
     * ### 内部で計算するフィールド
     * 
     * - user_id
     * 
     * ### デフォルト値を用いるフィールド
     * 
     * - name
     * - Alexa_id
     * - notification_time
     * - homecoming_time
     * - update_time
     * - latitude
     * - longitude
     * - password
     * 
     * ---
     * 
     * @param string $user_id LINE ID を指定します。
     * 
     * @return array
     */
    function registerUser(string $user_id)
    {
        $next_user_id = $this->getNextUserId();
        $sql = "INSERT INTO user(user_id, LINE_id) VALUES (:user_id, :LINE_id)";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":user_id", $next_user_id, PDO::PARAM_INT);
        $stm->bindValue(":LINE_id", $user_id, PDO::PARAM_STR);

        try {
            $succeed = $stm->execute();
            return array(
                "succeed" => $succeed,
                "ascribed_user_id" => $next_user_id,
            );
        } catch (Exception $e) {
            return array(
                "succeed" => false,
                "ascribed_user_id" => null,
            );
        }
    }

    /**
     * ユーザ ID を指定して、当該ユーザの一連の退会処理を行います。
     * 
     * **ユーザ情報が完全に削除されます。**
     * 
     * 返り値として退会処理が成功したかどうかを返します。
     * 
     * ---
     * @param string $user_id 退会するユーザの LINE ID を指定します。
     * @return bool
     */
    function withdrawal(string $LINE_id)
    {
        try {
            // start transaction for protecting the integrity during a withdrawal processes.
            $this->pdo->beginTransaction();

            // 1. get user_id from user table
            try {
                $user_id = $this->getUserIdFromLINEId($LINE_id);
            } catch (WrappedDBError $e) {
                // no matches. throw Exception and send failed signal.
                throw new Exception();
            }

            // matched. continue

            // 2. remove from user_item
            $this->pdo->query(
                "DELETE FROM user_item WHERE user_id = $user_id"
            );
            // 3. remove from location
            $this->pdo->query(
                "DELETE FROM location WHERE id = $user_id"
            );
            // 4. remove from send_log
            $this->pdo->query(
                "DELETE FROM send_log WHERE to_id = $user_id"
            );
            // 5. remove from comment
            $this->pdo->query(
                "DELETE FROM comment
                    WHERE from_id = $user_id OR to_id = $user_id"
            );
            // 6. remove from family_user
            $this->pdo->query(
                "DELETE FROM family_user WHERE user_id = $user_id"
            );
            // 7. remove from user
            $this->pdo->query(
                "DELETE FROM user WHERE user_id = $user_id"
            );

            // end transaction.
            $this->pdo->commit();
            // transaction succeed. return succeed signal (boolean true)
            return true;
        } catch (Exception $e) {
            // transaction failed. rollback and return failed signal (boolean false)
            $this->pdo->rollBack();
            return false;
        }
    }

    /**
     * ユーザ ID を指定して、登録されている時間情報を取得します。
     * 
     * ### 参照先テーブル
     * 
     * - user
     * 
     * ### 参照先フィールド
     * 
     * - notice_time
     * - return_time
     * - check_time
     * - id
     * 
     * ---
     * 
     * @param int user_id ユーザ ID を指定します。
     * @param array
     */
    function getAllTimeByUserId(int $user_id)
    {
        $sql = "SELECT notice_time, return_time, check_time
        FROM user WHERE id = :user_id";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        $stm->execute();

        return $stm->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * LINE ID を指定して、登録されている位置情報を取得します。
     * 
     * ### 参照先テーブル
     * 
     * - location
     * 
     * ### 参照先フィールド
     * 
     * - lat
     * - lon
     * - id
     * 
     * ---
     * 
     * @param $string LINE_id LINE ID を指定します。
     * @param array
     */
    function getLocationData(string $LINE_id)
    {
        $user_id = $this->getUserIdFromLINEId($LINE_id);
        $sql = "SELECT lat, lon FROM location
            WHERE id = :user_id";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        $stm->execute();

        return $stm->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 位置情報を追加します。
     * 
     * ### 追加先テーブル
     * 
     * - location
     * 
     * ---
     * 
     * @param string $LINE_id LINE ID を指定します。
     * @param string $latitude
     * @param string $longitude
     * @return bool 追加処理が成功したかどうかを返します。
     */
    function addLocationData(
        string $LINE_id,
        string $latitude,
        string $longitude
    ) {
        $sql = "INSERT INTO location(id, lat, lon)
            VALUES (:user_id, :latitude, :longitude)
            ON DUPLICATE KEY UPDATE lat = :latitude, lon = :longitude";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":user_id", $this->getUserIdFromLINEId($LINE_id));
        $stm->bindValue(":latitude", $latitude);
        $stm->bindValue(":longitude", $longitude);

        return $stm->execute();
    }

    /**
     * 指定したユーザ ID の位置情報を削除します。
     * 
     * ---
     * 
     * ### 削除先テーブル
     * 
     * - location
     * 
     * ---
     * 
     * @param string LINE_id LINE ID を指定します。
     */
    function eraseLocationData(string $LINE_id)
    {
        try {
            $user_id = $this->getUserIdFromLINEId($LINE_id);
        } catch (WrappedDBError $e) {
            // no matches. throw Exception and send failed signal.
            throw new Exception();
        }

        $this->pdo->query(
            "DELETE FROM location WHERE id = $user_id"
        );
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
     * ### might change in the further development
     * 
     * - Field "alert" will be renamed to "notification_check".
     * 
     * ---
     * 
     * @param int $to_id 受信者ユーザ ID を指定します。
     * @return array
     */
    function getReceivingMessage(int $to_id, bool $alerted)
    {
        if ($alerted) {
            $sql = "SELECT user.user_id, user.name, comment.comment
                FROM user, comment
                WHERE comment.to_id = :to_id AND comment.from_id = user.user_id
                AND comment.alert = true";
        } else {
            $sql = "SELECT a.name, b.comment FROM user a, comment b
                WHERE b.to_id = :to_id AND b.from_id = a.user_id
                AND b.comment IS NOT NULL";
        }
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":to_id", $to_id, PDO::PARAM_INT);

        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 指定した受信者 ID のメッセージ確認状態を `false` に変更します。
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
     * 
     * @deprecated 1.0.0 use setReadStatus() instead.
     */
    function markAsUnread(int $to_id)
    {
        $sql = "UPDATE comment SET LINE_check = false WHERE to_id = :to_id";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":to_id", $to_id, PDO::PARAM_INT);

        $stm->execute();
    }

    /**
     * 指定した受信者 ID のメッセージ確認状態を変更します。
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
     * @param bool $status 変更先の確認状態を指定します。
     * @return void
     */
    function setReadStatus(int $to_id, bool $status)
    {
        $sql = "UPDATE comment SET LINE_check = :status WHERE to_id = :to_id";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":to_id", $to_id, PDO::PARAM_INT);
        $stm->bindValue(":status", $status, PDO::PARAM_BOOL);

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
            // a day ago
            $min_time = date("Y-m-d 00:01:00", strtotime("-1 day", $time));
        } else if ($range == 1) {
            // a week ago
            $min_time = date("Y-m-d 00:01:00", strtotime("-1 week", $time));
        } else if ($range == 2) {
            // a month ago
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
     * ### $mode = GET_USER_(NOTICE, RETURN, CHECK)_TIME
     * 
     * 厳密には、5 分前 ±2 分 に送信されており、未確認であるような通知の送信ログを取得します。
     * また、±2 分 の判定は通知時刻が実行時刻の 1 分 30 秒前から 30 秒後までに含まれるかを判定しています。
     * 
     * $mode は DBController::GET_USER_******_TIME を用いて指定してください。
     * 
     * ### $mode = FOR_LINEBOT
     * 
     * 指定時刻から前 30 分以内のみに対して実行されます。
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
     * @param int? $time 対象時刻を指定します。省略すると現在時刻になります。
     * @param int $mode 判定対象の通知時間を DBController::GET_USER_******_TIME を用いて指定します。
     * @return array
     */
    function getUnreadUser(?int $time = null, int $mode)
    {
        if (is_null($time)) {
            $time = time();
        }
        switch ($mode) {
            case DBController::GET_USER_NOTIFY_TIME:
                $key_name = "notice_time";

            case DBController::GET_USER_RETURN_TIME:
                $key_name = "return_time";

            case DBController::GET_USER_CHECK_TIME:
                $key_name = "check_time";

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

                break;

            case DBController::FOR_LINEBOT:
                // before 1m30s from specified time, and more before 5m
                $min_time = date("Y-m-d H:i:s", $time - 1800);
                // after 30s from specified time, and before 5m from it
                $max_time = date("Y-m-d H:i:s", $time);

                $sql = "SELECT DISTINCT a.user_id, a.name, a.LINE_id
                    FROM user a, send_log b
                    WHERE a.notice_time BETWEEN '$min_time' AND '$max_time'
                    AND b.datetime BETWEEN '$min_time' AND '$max_time'
                    AND b.confirm_check = false AND a.user_id = b.to_id";
                break;
        }
    }

    /**
     * 受信者を指定して、送信済みのメッセージについて送信者を取得します。
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
     * - comment.alert
     * 
     * ---
     * 
     * ### might change in the further development
     * 
     * - Field "alert" will be renamed to "notification_check".
     * 
     * ---
     * 
     * @param int $to_id 受信者ユーザ ID を指定します。
     * @return array
     */
    function getMessageSender(int $to_id)
    {
        $sql = "SELECT user.LINE_id FROM user, comment
            WHERE comment.to_id = :to_id AND comment.from_id = user.user_id
            AND comment.from_id != comment.to_id AND comment.alert = true";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":to_id", $to_id, PDO::PARAM_INT);
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 時刻を指定して、通知時刻が現在時刻であるユーザの情報を取得します。
     * 
     * 1 回限りの時刻指定通知に対して使用することを想定しています。
     * 
     * ### 参照先テーブル
     * 
     * - user
     * - user_item
     * - item
     * 
     * ### 参照先フィールド
     * 
     * - user.user_id
     * 
     * - user_item.user_id
     * - user_item.item_id
     * - user_item.notice_datetime
     * 
     * - item.id
     * 
     * ### might change in the further development
     * 
     * - Table "item" will be integrated into "user_item".
     * 
     * ---
     * 
     * @param int $time 対象時刻を設定します。
     * @return array
     */
    function getItemFromTime(int $time)
    {
        // before 1m30s from specified time
        $min_time = date("Y-m-d H:i:s", $time - 90);
        // after 30s from specified time
        $max_time = date("Y-m-d H:i:s", $time + 30);

        $sql = "SELECT a.user_id, a.LINE_ID, c.name
            FROM user a, user_item b, item c
            WHERE a.user_id = b.user_id AND c.id = b.item_id
            AND b.notice_datetime BETWEEN '$min_time' AND '$max_time'";

        $stm = $this->pdo->prepare($sql);
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 指定時刻から前 30 分の間に送信された確認済みのメッセージについて、送信者を取得します。
     * 
     * 厳密には、実行時刻の 30 分 10 秒前から 10 秒前までに含まれるかを判定しています。
     * 
     * ### 参照先テーブル
     * 
     * - user
     * - send_log
     * 
     * ### 参照先フィールド
     * 
     * - user.user_id
     * - user.name
     * 
     * - send_log.date_time
     * - send_log.to_id
     * - send_log.confirm_check
     * 
     * ---
     * 
     * @param int $time 対象時刻を指定します。
     * @return array
     */
    function getCheckedMessageSender(int $time)
    {
        // before 1m30s from specified time
        $min_time = date("Y-m-d H:i:s", $time - 1810);
        // after 30s from specified time
        $max_time = date("Y-m-d H:i:s", $time - 10);

        $sql = "SELECT a.user_id, a.name FROM user a, send_log b
            WHERE b.comfirm_check = true
            AND b.date_time BETWEEN '$min_time' AND '$max_time'
            AND a.user_id = b.to_id";

        $stm = $this->pdo->prepare($sql);
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 送信者 ID を指定し、通知済みかつ未確認のメッセージについて、送信元の LINE ID を取得します。
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
     * - comment.LINE_check
     * - comment.alert
     * 
     * ---
     * 
     * ### might change in the further development
     * 
     * - Field "alert" will be renamed to "notification_check".
     * 
     * ---
     * 
     * @param int $to_id 送信者 ID を指定します。
     * @return array
     */
    function getMessageUnreadUser(int $to_id)
    {
        $sql = "SELECT a.LINE_id from user a, comment b
            WHERE b.to_id = :to_id AND a.user_id = b.from_id
            AND b.to_id != b.from_id 
            AND b.LINE_check = false AND b.alert = true";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":to_id", $to_id, PDO::PARAM_INT);
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 日時を指定して、その 1 日前に通知されたユーザをすべて取得します。
     * 
     * ### 参照先テーブル
     * 
     * - user
     * - send_log
     * 
     * ### 参照先フィールド
     * 
     * - user.LINE_id
     * - user.user_id
     * 
     * - send_log.to_id
     * - send_log.datetime
     * - send_log.notice_time
     * 
     * ---
     * 
     * @param int $time 対象時刻を指定します。
     * @return array|false
     */
    function getYesterdayNotified(int $time)
    {
        // before 1m30s from specified time
        $min_log_time = date("Y-m-d 00:00:00", $time - 86460);
        // after 30s from specified time
        $max_log_time = date("Y-m-d 00:00:00", $time + 60);

        // before 1m30s from specified time
        $min_time = date("Y-m-d H:i:s", $time - 1810);
        // after 30s from specified time
        $max_time = date("Y-m-d H:i:s", $time - 10);

        $sql = "SELECT DISTINCT a.to_id, b.LINE_id FROM send_log a, user b
            WHERE a.datetime BETWEEN '$min_log_time' AND '$max_log_time'
            AND a.to_id = b.user_id
            AND b.notice_time BETWEEN '$min_time' AND '$max_time'";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 指定した日数後に通知される持ち物を取得します。
     * 
     * ### 参照先テーブル
     * 
     * - item
     * - user_item
     * 
     * ### might change in the further development
     * 
     * - Table "item" will be integrated into "user_item".
     * - Query will be remade as using the union phrase.
     * 
     * ---
     * 
     * @param int $user_id ユーザ ID を指定します。
     * @param int $move_day 参照する日数を指定します。
     */

    function getFurtherItem(int $user_id, int $move_day)
    {
        $sql = "SELECT a.name FROM item a, user_item b
            WHERE b.user_id = :user_id AND a.id = b.item_id
            AND (
                (b.days LIKE :weekday OR b.days LIKE 'ALL')
                OR(
                    b.notice_datetime >= :min_time
                    AND b.notice_datetime < :max_time
                )
            )";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $stm->bindValue(":weekday", strtolower(date("D")), PDO::PARAM_STR);
        // 86400s = 1 day
        $stm->bindValue(
            ":min_time",
            date("Y-m-d 00:00:00", time() + 86400 * $move_day)
        );
        $stm->bindValue(
            ":max_time",
            date("Y-m-d 23:59:59", time() + 86400 * $move_day)
        );
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 持ち物の登録を解除します。
     * 
     * ### 削除先テーブル
     * 
     * - user_item
     * 
     * ---
     * 
     * @param int $user_id 削除対象の持ち物を登録しているユーザ ID を指定します。
     * @param int $item_id 削除対象の持ち物 ID を指定します。
     * @return void
     */
    function deleteItem(int $user_id, int $item_id)
    {
        $sql = "DELETE FROM user_item
            WHERE user_id = :user_id AND item_id = :item_id";

        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $stm->bindValue(":item_id", $item_id, PDO::PARAM_INT);

        $stm->execute();

        return;
    }

    /**
     * まだ読まれていない、実行時刻の 30 分以内に送信されたメッセージを取得します。
     * 
     * ### 参照先テーブル
     * 
     * - user
     * - send_log
     * 
     * ### 参照先フィールド
     * 
     * - user.LINE_id
     * - user.user_id
     * 
     * - send_log.to_id
     * - send_log.confirm_check
     * - send_log.datetime
     * 
     * ---
     * 
     * @param string $LINE_id LINE ID を指定します。
     * @return array
     */
    function getUnreadValidMessage(string $LINE_id)
    {
        $sql = "SELECT DISTINCT a.id, a.name FROM user a, send_log b 
            WHERE a.LINE_id = :LINE_id AND a.id = b.to_id
            AND b.confirm_check = false
            AND b.datetime BETWEEN :min_time AND :current_time";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":LINE_id", $LINE_id);
        // 30m ago
        $stm->bindValue(":min_time", date("Y-m-d H:i:s", time() - 30 * 60));
        $stm->bindValue(":current_time", date("Y-m-d H:i:s", time()));

        $stm->execute();
        return $stm->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 次に登録する持ち物の ID を取得します。
     * 
     * ### 参照先テーブル
     * 
     * - item
     * 
     * ### 参照先フィールド
     * 
     * - id
     * 
     * ### might change in the further development
     * 
     * - Table "item" will be integrated into the table "user_item".
     * 
     * @return int
     */
    private function getNextItemId()
    {
        $sql = "SELECT max(id) FROM item";
        $result = $this->pdo->query($sql)->fetch(PDO::FETCH_COLUMN);
        if (!isset($result)) {
            return 1;
        } else {
            return $result + 1;
        }
    }

    /**
     * 持ち物を新規に登録します。
     * 
     * ### 追加先テーブル
     * 
     * - item
     * - user_item
     * 
     * ### might change in the further development
     * 
     * - Table "item" will be integrated into the table "user_item".
     * 
     * ---
     * 
     * @param int $user_id ユーザ ID を指定します。
     * @param string $name 持ち物の名前を指定します。
     * @param string $weekday 通知する曜日を指定します。
     * @param string $notify_time 通知する日時を指定します。
     * @return bool 追加が成功したかを返します。
     */
    function registerItem(
        int $user_id,
        string $name,
        string $weekday = "",
        string $notify_time = ""
    ) {
        // adding into item table
        $sql = "INSERT INTO item(id, name) VALUES (:id, :name)";
        $stm = $this->pdo->prepare($sql);
        $item_id = $this->getNextItemId();
        $stm->bindValue(":id", $item_id, PDO::PARAM_INT);
        $stm->bindValue(":name", $name, PDO::PARAM_STR);

        $result = $stm->execute();

        // adding into user_item table
        $sql = "INSERT INTO user_item(user_id, item_id, days, notice_time)
            VALUES (:user_id, :item_id, :weekday, :notice_datetime)
            ON DUPLICATE KEY
                UPDATE days = :weekday, notice_datetime = :notice_time2";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":user_id", $user_id);
        $stm->bindValue(":item_id", $item_id);
        $stm->bindValue(":weekday", $weekday);
        $stm->bindValue(":notice_time", $notify_time);
        $stm->bindValue(":notice_time2", $notify_time);

        $result = $result & $stm->execute();

        return $result;
    }

    /**
     * 持ち物を登録解除します。
     * 
     * ### 削除先テーブル
     * 
     * - user_item
     * 
     * ---
     * 
     * @param int $user_id ユーザ ID を指定します。
     * @param int $item_id 持ち物 ID を指定します。
     * @return bool 処理が成功したかを返します。
     */
    function removeItem(int $user_id, int $item_id)
    {
        $sql = "DELETE FROM user_item
            WHERE user_id = :user_id AND item_id = :item_id";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $stm->bindValue(":item_id", $item_id, PDO::PARAM_INT);
        return $stm->execute();
    }

    /**
     * ユーザ ID を指定して、登録されている時刻を取得します。
     * 
     * ### 参照先テーブル
     * 
     * - user
     * 
     * ### 参照先フィールド
     * 
     * - notice_time
     * - return_time
     * - check_time
     * 
     * ---
     * 
     * @param int $user_id ユーザ ID を指定します。
     * @return array
     */
    function getRegisteredTime(int $user_id)
    {
        $sql = "SELECT notice_time, return_time, check_time
            FROM user where user_id = :user_id";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $stm->execute();

        return $stm->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 登録されている時刻を更新します。
     * 
     * ### 更新先テーブル
     * 
     * - user
     * 
     * ### 更新先フィールド
     * 
     * - notice_time
     * - return_time
     * - check_time
     * 
     * ---
     * 
     * @param int $user_id ユーザ ID を指定します。
     * @param int $notice_time
     * @param int $return_time
     * @param int $check_time
     * @return void
     */
    function setTime(
        int $user_id,
        int $notice_time,
        int $return_time,
        int $check_time
    ) {
        $sql = "UPDATE user SET
            notice_time = :notice_time,
            return_time = :return_time,
            check_time = :check_time
            WHERE user_id = :user_id";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $stm->bindValue(":notice_time", $notice_time, PDO::PARAM_INT);
        if ($return_time == 000000) {
            $stm->bindValue(":return_time", null, PDO::PARAM_NULL);
        } else {
            $stm->bindValue(":return_time", $return_time, PDO::PARAM_INT);
        }
        $stm->bindValue(":check_time", $check_time, PDO::PARAM_INT);
        $stm->execute();
    }

    /**
     * 次に登録するグループの ID を取得します。
     * 
     * ### 参照先テーブル
     * 
     * - family
     * 
     * ### 参照先フィールド
     * 
     * - id
     * 
     * ---
     * 
     * @return int
     */
    private function getNextFamilyId()
    {
        return $this->pdo->query(
            "SELECT MAX(id) + 1 FROM family"
        )->fetch(PDO::FETCH_COLUMN) ?? 1;
    }

    /**
     * グループを新規登録します。
     * 
     * ### 追加先テーブル
     * 
     * - family
     * 
     * ---
     * 
     * @param string $name グループ名を指定します。
     * @param string $pass パスワードを指定します。
     * @return bool 追加処理が成功したかを返します。
     */
    function registerFamily(string $name, string $pass)
    {
        $family_id = $this->getNextFamilyId();
        $sql = "INSERT INTO family(id, name, pass)
            VALUES (:id, :name, :pass)";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":id", $family_id, PDO::PARAM_INT);
        $stm->bindValue(":name", $name, PDO::PARAM_STR);
        $stm->bindValue(":pass", $pass, PDO::PARAM_STR);

        return $stm->execute();
    }

    /**
     * ユーザをグループに追加します。
     * 
     * ### 追加先テーブル
     * 
     * - family_user
     * - user
     * 
     * ---
     * @param int $family_id グループ ID を指定します。
     * @param int $user_id ユーザ ID を指定します。
     * @param string $name_in_family グループ内で使う名前を指定します。
     * @return void
     */
    function registerUserIntoFamily(
        int $family_id,
        int $user_id,
        string $name_in_family
    ) {
        $sql = "INSERT INTO family_user(family_id, user_id)
            VALUES (:family_id, :user_id)";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":family_id", $family_id, PDO::PARAM_INT);
        $stm->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        $stm->execute();

        $sql = "UPDATE user SET name = :name WHERE user_id = :user_id";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":name", $name_in_family, PDO::PARAM_STR);
        $stm->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        $stm->execute();
    }

    /**
     * グループを名前とパスワードで検索します。
     * 
     * ### 参照先テーブル
     * 
     * - family_id
     * 
     * ### 参照先フィールド
     * 
     * - name
     * - pass
     * 
     * ---
     * 
     * @param string $name グループ名を指定します。
     * @param string $pass パスワードを指定します。
     * @return int
     */
    function searchFamily(string $name, string $pass)
    {
        $sql = "SELECT DISTINCT id from family
            WHERE name = :name AND pass = :pass";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":name", $name, PDO::PARAM_INT);
        $stm->bindValue(":pass", $pass, PDO::PARAM_STR);
        $stm->execute();

        return $stm->fetch(PDO::FETCH_COLUMN);
    }

    /**
     * ユーザ ID を指定して、当該ユーザが所属するグループをすべて取得します。
     * 
     * ### 参照先テーブル
     * 
     * - family
     * - family_user
     * 
     * ### 参照先フィールド
     * 
     * - family.id
     * - family_user.user_id
     * - family_user.family_id
     * 
     * ---
     * 
     * @param int $user_id ユーザ ID を指定します。
     * @return array
     */
    function getAllFamilyFromUserId(int $user_id)
    {
        $sql = "SELECT DISTINCT a.family_id, b.name FROM family_user a, family b
            WHERE a.user_id = :user_id AND a.family_id = b.id";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * グループ ID を指定して、所属するユーザ情報をすべて取得します。
     * 
     * ### 参照先テーブル
     * 
     * - user
     * - family_user
     * 
     * ### 参照先フィールド
     * 
     * - user.user_id
     * - family_user.user_id
     * - family_user.family_id
     * 
     * ---
     * 
     * @param int $family_id グループ ID を指定します。
     * @return array
     */
    function getAllUserFromFamilyId(int $family_id)
    {
        $sql = "SELECT a.user_id, a.user_name from user a, family_user b
            WHERE b.family_id = :family_id AND a.user_id = b.user_id";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":family_id", $family_id, PDO::PARAM_INT);
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * グループ ID、送信者、受信者を指定してメッセージを取得します。
     * 
     * ### 参照先テーブル
     * 
     * - comment
     * 
     * ### 参照先テーブル
     * 
     * - family_id
     * - from_id
     * - to_id
     * 
     * ---
     * 
     * @param int $family_id グループ ID を指定します。
     * @param int $from_id 送信者のユーザ ID を指定します。
     * @param int $to_id 受信者のユーザ ID を指定します。
     * @return array
     */
    function getMessageOnFamily(int $family_id, int $from_id, int $to_id)
    {
        $sql = "SELECT comment, alert FROM comment
            WHERE family_id = :family_id
            AND from_id = :from_id AND to_id = :to_id";
        //プリペアードステートメントを作る
        $stm = $this->pdo->prepare($sql);
        //プリペアードステートメントに値をバインドする
        $stm->bindValue(':family_id', $family_id, PDO::PARAM_INT);
        $stm->bindValue(':from_id', $from_id, PDO::PARAM_INT);
        $stm->bindValue(':to_id', $to_id, PDO::PARAM_INT);
        //SQL文を実行する
        $stm->execute();
        //結果の取得（連想配列で受け取る）
        return $stm->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * グループからユーザを退会させます。
     * 
     * ### 削除先テーブル
     * 
     * - comment
     * - family_user
     * 
     * ---
     * 
     * @param int $family_id 退会元のグループ ID を指定します。
     * @param int $user_id 退会するユーザ ID を指定します。
     */
    function familyWithdrawal(int $family_id, int $user_id)
    {
        $sql = "DELETE FROM comment
            WHERE family_id = :family_id
            AND (to_id = :user_id OR from_id = :user_id2)";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":family_id", $family_id, PDO::PARAM_INT);
        $stm->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $stm->bindValue(":user_id2", $user_id, PDO::PARAM_INT);

        $stm->execute();

        $sql = "DELETE FROM family_user
            WHERE family_id = :family_id AND user_id = :user_id";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":family_id", $family_id, PDO::PARAM_INT);
        $stm->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        $stm->execute();
    }

    /**
     * 保存されているログに対して既読状態を更新します。
     * 
     * ### 更新先テーブル
     * 
     * - send_log
     * 
     * ### 更新先フィールド
     * 
     * - confirm_check
     * 
     * ---
     * 
     * @param int $user_id ユーザ ID を指定します。
     * @return void
     */
    function markAsRead(int $user_id)
    {
        $sql = "UPDATE send_log SET confirm_check = true
            WHERE to_id = :user_id
            AND datetime BETWEEN :min_time AND :current_time";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        // 30m ago
        $stm->bindValue(":min_time", date("Y-m-d H:i:s", time() - 30 * 60));
        $stm->bindValue(":current_time", date("Y-m-d H:i:s", time()));

        $stm->execute();
        return;
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

    /**
     * LINE ID からユーザ ID を取得します。
     * 
     * ### 参照先テーブル
     * 
     * - user
     * 
     * ### 参照先フィールド
     * 
     * - user_id
     * - LINE_id
     * 
     * ---
     * 
     * @param string $LINE_id LINE ID を指定します。
     * @throws WrappedDBError 指定された LINE ID をもつユーザが存在しない場合
     */
    private function getUserIdFromLINEId(string $LINE_id)
    {
        $user_id = $this->pdo->query(
            "SELECT user_id from user WHERE LINE_id = '$LINE_id'"
        )->fetchAll(PDO::FETCH_ASSOC);

        if (count($user_id)) {
            // matched. continue
            $user_id = $user_id[0]["user_id"];
        } else {
            // no matches. throw Exception and send failed signal.
            throw new WrappedDBError($this->error_text[0]);
        }

        return $user_id;
    }
};
