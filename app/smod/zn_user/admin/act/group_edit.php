<?php
$group = ZN_User_Group::select_line_by_id($_GET['id']);

title("Редактировать группу «{$group['Name']}»");
path
([
	"Пользователи [#zn_user/user]",
	"Редактировать группу «{$group['Name']}» [#zn_user/group_edit?id={$_GET['id']}]"
]);
?>