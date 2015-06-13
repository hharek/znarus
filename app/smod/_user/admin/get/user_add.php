<?php
$group = _User_Group::get_all();

title("Добавить пользователя");
path
([
	"Пользователи [#_user/user]",
	"Добавить [#_user/user_add]"
]);
?>