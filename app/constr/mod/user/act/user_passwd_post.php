<?php
ZN_User::passwd($_GET['id'], $_POST['Password']);

$user = ZN_User::select_line_by_id($_GET['id']);
mess_ok("Пароль у пользователя «{$user['Email']}» изменён.");
require "user_edit.php";
?>