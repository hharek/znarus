<?php
$group = _User_Group::get($_GET['id']);

title("Редактировать группу «{$group['Name']}»");
path
([
	"Пользователи [#_user/user]",
	"Редактировать группу «{$group['Name']}» [#_user/group_edit?id={$_GET['id']}]"
]);
?>