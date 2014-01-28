<?php
$group = ZN_User_Group::select_list();
$user = ZN_User::select_list();

path
([
	"Пользователи [#zn_user/user]"
]);

title("Пользователи");
?>