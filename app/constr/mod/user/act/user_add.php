<?php
$group = ZN_User_Group::select_list();

title("Добавить пользователя");
path
([
	"Пользователи [#user/list]",
	"Добавить [#user/user_add]"
]);
?>