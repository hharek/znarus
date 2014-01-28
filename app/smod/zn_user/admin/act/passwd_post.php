<?php
if($_POST['Password_New'] !== $_POST['Password_New_Repeat'])
{
	Err::add("Пароли не совпадают", "Password_New");
	Err::add("Пароли не совпадают", "Password_New_Repeat");
	Err::exception();
}

ZN_User_Action::passwd($_POST['Password_Old'], $_POST['Password_New']);
mess_ok("Пароль успешно изменён");
reload();
?>