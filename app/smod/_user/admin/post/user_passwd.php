<?php
_User::passwd($_GET['id'], $_POST['Password']);
$user = _User::get($_GET['id']);

mess_ok("Пароль у пользователя «{$user['Email']}» изменён.");
?>