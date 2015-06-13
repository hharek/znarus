<?php
$user = _User::get($_GET['id']);

title("Сменить пароль у пользователя «{$user['Email']}»");
path
([
	"Пользователи [#_user/user]",
	"Сменить пароль у «{$user['Email']}» [#_user/user_passwd?id={$_GET['id']}]"
]);
?>