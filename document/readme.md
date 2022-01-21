# DB アクセス一覧

|処理内容|ファイル名|場所(行)|SQL本文|補足|
| -- | -- | -- | -- | -- |
|コメントテーブルの<br>通知フラグを変更する|alert_set.php|$7$|`UPDATE comment SET alert = :alert`<br>`WHERE family_id = :family_id`<br>`AND from_id = :from_id AND to_id = :to_id"`|`alert` フィールドは<br> `notification_check` にリネームする|
|ユーザ情報が Alexa と<br>連携しているかを確認する|Alexa_cooperation_db.php|$4$|`SELECT Alexa_id FROM user WHERE id = :id`||
|既にユーザ情報と紐づいている<br>パスワードを検索する|Alexa_cooperation_db.php|$21$|`SELECT pass_id FROM Alexa_coop WHERE user_id = :user_id`|`Alexa_coop.pass_id` は `user` に統合|
|Alexa認証コードの<br>他ユーザとの重複を確認|Alexa_password_set.php|$7$|`SELECT pass_id FROM Alexa_coop WHERE pass_id = :pass_id`|`Alexa_coop.pass_id` は `user` に統合|
|Alexa認証情報を追加|Alexa_password_set.php|$21$|`INSERT INTO Alexa_coop(user_id,pass_id)`<br>`VALUES(:user_id,:pass_id)`|`Alexa_coop.pass_id` は `user` に統合|
|つかってない|comment_check.php|$31$|`SELECT count(comment) FROM comment a, user b`<br>`WHERE a.to_id = b.id AND b.Alexa_id = :Alexa_id'`|つかってない|
|グループ、および<br>送信者と受信者を指定して<br>コメントを取得|comment_send.php|$12$|`SELECT * FROM comment`<br>`WHERE family_id = :family_id`<br>`AND from_id = :from_id AND to_id = :to_id"`||
|グループ、および<br>送信者と受信者を指定して<br>コメントと既読フラグを更新|comment_send.php|$23$|`UPDATE comment SET comment = :comment , alert = :alert`<br>`WHERE family_id = :family_id`<br>`AND from_id = :from_id AND to_id = :to_id"`||
|新規にコメントを登録|comment_send.php|$39$|`INSERT INTO comment(family_id, from_id, to_id, comment)`<br>`VALUES (:family_id, :from_id, :to_id, :comment)`||
|ユーザの LINE ID から名前を取得|confirm.php|$76$|`SELECT name FROM user WHERE id = :id`||
|ユーザ LINE ID ~~と持ち物 ID~~ から<br>持ち物の通知時刻を取得|confirm.php|$101$|`SELECT a.name, b.item_id, b.days, b.notice_datetime`<br>`FROM item a, user_item b`<br>`WHERE b.user_id = :id AND a.id = b.item_id`|`item` テーブルは `user_item` に統合|
|通知時刻が現在時刻(前後 $2$ 分)<br>であるユーザをすべて取得|cron.php|$10$|`SELECT id, LINE_id FROM user`<br>`WHERE notice_time >= :min_time AND notice_time < :max_time`|`BETWEEN` 構文の方がよさそう<br>半開区間なので注意|
|帰りの時間が現在時刻(前後 $2$ 分)<br>であるユーザをすべて取得|cron.php|$168$|`SELECT id, LINE_id FROM user`<br>`WHERE return_time >= :min_time AND return_time < :max_time`||
|持ち物更新の時間が現在時刻(前後 $2$ 分)<br>であるユーザをすべて取得|cron.php|$244$|`SELECT id, LINE_id FROM user`<br>`WHERE check_time >= :min_time AND check_time < :max_time`||
|現在の曜日で登録されている<br>特定ユーザの持ち物をすべて取得|cron.php|$26, 184$|`SELECT a.name FROM item a, user_item b`<br>`WHERE b.user_id = :id AND a.id = b.item_id`<br>`AND (b.days LIKE :day OR b.days LIKE 'ALL')`|`item` テーブルは `user_item` に統合|
|現在の曜日で登録されている<br>特定ユーザの持ち物があるか確認|cron.php|$259$|`SELECT user_id FROM user_item WHERE user_id = :id AND (days LIKE :day OR days LIKE 'ALL')`||
|送信者と受信者を指定して<br>登録されたメッセージを取得|cron.php|$61, 219$|`SELECT user.name, comment.comment FROM user, comment`<br>`WHERE comment.to_id = :id AND comment.from_id = user.id`<br>`AND comment.comment != null`||
|メッセージ受信者の既読フラグを<br>`false` にする|cron.php|$77, 235$|`UPDATE comment SET LINE_check = false WHERE to_id = :id`||
|ユーザごとに期間内の<br>確認されなかった持ち物確認回数を取得|cron.php|$86, 107$|`SELECT COUNT(*) AS num FROM send_log`<br>`WHERE datetime >= :min_time AND datetime < :max_time`<br>`AND confrim_check = false AND to_id = :id`|期間は `:min_time`, `:max_time`|
|通知時刻が現在時刻の $5$ 分前(前後 $2$ 分)かつ<br>通知を確認していないユーザをすべて取得|cron.php|$129$|`SELECT DISTINCT a.id, a.name, a.LINE_id`<br>`FROM user a, send_log b`<br>`WHERE a.notice_time >= :min_time AND a.notice_time < :max_time`<br>`AND b.datetime >= :min_time2 AND b.datetime < :max_time2`<br>`AND b.confrim_check = false AND a.id = b.to_id"`||
|持ち物更新の時間が現在時刻の $5$ 分前(前後 $2$ 分)かつ<br>通知を確認していないユーザをすべて取得|cron.php|$290$|`SELECT DISTINCT a.id, a.name, a.LINE_id`<br>`FROM user a, send_log b`<br>`WHERE a.check_time >= :min_time AND a.check_time < :max_time`<br>`AND b.datetime >= :min_time2 AND b.datetime < :max_time2`<br>`AND b.confirm_check = false AND a.id = b.to_id"`||
|受信者が確認していないメッセージについて、<br>メッセージ送信者を取得|cron.php|$146, 409$|`SELECT user.LINE_id FROM user, comment`<br>`WHERE comment.to_id = :id AND comment.from_id = user.id`<br>`AND comment.from_id != comment.to_id AND comment.alert = true`|自身に向けてのリマインドは無視される|
|ユーザ LINE ID と持ち物 ID を指定して<br>通知時刻が現在時刻(前後 $2$ 分)<br>であるユーザをすべて取得|cron.php|$330$|`SELECT a.id, a.LINE_id, c.name FROM user a, user_item b, item c`<br>`WHERE a.id = b.user_id AND c.id = b.item_id`<br>`AND b.notice_datetime >= :min_time AND b.notice_datetime < :max_time`|`item` テーブルは `user_item` に統合|
|送信者、受信者を指定して<br>メッセージを取得|cron.php|$362$|`SELECT user.id, user.name, comment.comment FROM user, comment`<br>`WHERE comment.to_id = :id AND comment.from_id = user.id`<br>`AND comment.alert = true`||
|通知時刻が現在時刻(前後 $2$ 分)かつ<br>通知を確認していないユーザをすべて取得|cron.php|$390$|`SELECT DISTINCT a.id, a.name, a.LINE_id FROM user a, user_item b, send_log c`<br>`WHERE a.id = b.user_id AND b.user_id = c.to_id AND`<br>`b.notice_datetime >= :min_time2 AND b.notice_datetime < :max_time2`<br>`AND c.datetime >= :min_time AND c.datetime < :max_time`<br>`AND c.confirm_check = false`||
|受信者がメッセージを確認済みであるような<br>メッセージの送信者をすべて取得|cron.php|$429$|`SELECT a.id, a.name FROM user a, send_log b`<br>`WHERE b.confirm_check = true AND b.datetime >= :min_time`<br>`AND b.datetime < :max_time AND a.id = b.to_id`||
||cron.php|$442$|`SELECT a.LINE_id FROM user a, comment b`<br>`WHERE b.to_id = :id AND a.id = b.from_id AND b.LINE_check = false`<br>`AND b.to_id != b.from_id AND b.alert = true`||
||cron.php|$456$|`UPDATE comment SET LINE_check = true WHERE to_id = :id`||
||cron.php|$466$|`SELECT DISTINCT a.to_id, b.LINE_id FROM send_log a, user b`<br>`WHERE a.datetime >= :min_datetime AND a.datetime < :max_datetime`<br>`AND a.to_id = b.id`<br>`AND b.notice_time >= :min_time AND b.notice_time < :max_time`||
|持ち物確認のログ保存|cron.php|$46, 204$|`INSERT INTO send_log(to_id, message, datetime)`<br>`VALUES (:id, :item, :datetime)`||
|持ち物更新のログ保存|cron.php|$272, 347$|`INSERT INTO send_log(to_id, message, datetime)`<br>`VALUES (:id, :item, :datetime)`||


# その他備考

- SQL に、あって然るべき空白がない場合が多く、本ファイル上に限って修正した状態で記載しています。
- confirm.php について
  - $138$ 行目の preg_match は曜日の判定であり、ロジックそのものを書き換える予定
- cron.php について
  - `$time - 90` などのマジックナンバーについて説明が必要
    - 場合によっては説明をコメントに書いたうえで変数としておくべき

# その他問題点

- echo で HTML タグを出力している部分が多い
- コロン構文を使っておらず、インデントが間違っており、可読性が損なわれている
- コメントが邪魔
