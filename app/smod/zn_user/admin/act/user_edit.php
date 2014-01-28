<?php
$group = ZN_User_Group::select_list();
$user = ZN_User::select_line_by_id($_GET['id']);

title("Редактировать пользователя «{$user['Email']}»");
path
([
	"Пользователи [#zn_user/user]",
	"{$user['Email']} [#zn_user/user_edit?id={$_GET['id']}]"
]);
?>