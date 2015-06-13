<?php
if($_POST['Password_New'] !== $_POST['Password_New_Repeat'])
{
	throw new Exception("Пароли не совпадают.");
}

_User_Action::passwd($_POST['Password_Old'], $_POST['Password_New']);
mess_ok("Пароль успешно изменён");
?>