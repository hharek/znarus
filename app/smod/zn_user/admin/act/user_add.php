<?php
$group = ZN_User_Group::select_list();

title("Добавить пользователя");
path
([
	"Пользователи [#zn_user/user]",
	"Добавить [#zn_user/user_add]"
]);
?>