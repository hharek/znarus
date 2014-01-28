<?php
$user = ZN_User_Action::data();
if($user['Name'] === "root")
{
	throw new Exception_Admin("Чтобы сменить пароль у root-а, воспользуйтесь конфигурационным файлом.");
}
?>