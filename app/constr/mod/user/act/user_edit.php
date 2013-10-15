<?php
$group = ZN_User_Group::select_list();
$user = ZN_User::select_line_by_id($_GET['id']);

title("Редактировать пользователя «{$user['Email']}»");
path
([
	"Пользователи [#user/list]",
	"{$user['Email']} [#user/user_edit?id={$_GET['id']}]"
]);
?>