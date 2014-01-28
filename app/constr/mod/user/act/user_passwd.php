<?php
$group = ZN_User_Group::select_list();
$user = ZN_User::select_line_by_id($_GET['id']);

title("Сменить пароль у «{$user['Email']}»");
path
([
	"Пользователи [#user/list]",
	"Сменить пароль у {$user['Email']} [#user/user_passwd?id={$_GET['id']}]"
]);
?>