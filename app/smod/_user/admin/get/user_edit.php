<?php
$group = _User_Group::get_all();
$user = _User::get($_GET['id']);

title("Редактировать пользователя «{$user['Email']}»");
path
([
	"Пользователи [#_user/user]",
	"{$user['Email']} [#_user/user_edit?id={$_GET['id']}]"
]);
?>