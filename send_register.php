<form action="register_item.php" method="POST">
    <div class="cp_iptxt">
        <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
        <input type="text" name="item_name" value="" placeholder="登録内容"><br>
    </div>
    <div class="cp_iptxt">
        <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
        <label><input type="checkbox" name="days[]" value="sun"><span>日</span></label>
        <label><input type="checkbox" name="days[]" value="mon"><span>月</span></label>
        <label><input type="checkbox" name="days[]" value="tue"><span>火</span></label>
        <label><input type="checkbox" name="days[]" value="wed"><span>水</span></label>
        <label><input type="checkbox" name="days[]" value="thu"><span>木</span></label>
        <label><input type="checkbox" name="days[]" value="fri"><span>金</span></label>
        <label><input type="checkbox" name="days[]" value="sat"><span>土</span></label>
    </div>
    <div class="cp_iptxt">
        <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
        <date><input type="datetime-local" name="datetime"></date>
        <i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i>
    </div>
    <div>
        <input type="submit" name="send" class="button1">
    </div>
    <input type="text" name="user_id" value="20">
</form>
