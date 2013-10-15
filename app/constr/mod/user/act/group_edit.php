<?php
$group = ZN_User_Group::select_line_by_id($_GET['id']);

title("Редактировать группу «{$group['Name']}»");
path
([
	"Пользователи [#user/list]",
	"Редактировать группу «{$group['Name']}» [#user/group_edit?id={$_GET['id']}]"
]);
?>