<?php
$user = ZN_User::select_line_by_id($_GET['id']);

title("Сменить пароль у пользователя «{$user['Email']}»");
path
([
	"Пользователи [#zn_user/user]",
	"Сменить пароль у «{$user['Email']}» [#zn_user/user_passwd?id={$_GET['id']}]"
]);
?>