<?php
/* Проверка сессии */
if (G::session_check() === true)
{
	throw new Exception("Вы уже авторизованы.");
}

/* Небольшая задержка */
sleep(1);

$data = [];
try
{
	/* Проверка токена */
	if ($_POST['token'] !== md5(SALT . $_SESSION['_restore_token']))
	{
		throw new Exception("Токен задан неверно.");
	}
	
	/* Отправить код для восстановления пароля на почту */
	if ($_POST['Type'] === "send")
	{
		_User::password_change_code_send($_POST['Email']);
		$data['email'] = $_POST['Email'];
	}
	elseif ($_POST['Type'] === "passwd")
	{
		if ($_POST['Password'] !== $_POST['Password_Repeat'])
		{
			throw new Exception("Пароли не совпадают.");
		}
		
		_User::password_change_code($_POST['Code'], $_POST['Password']);
	}
	
}
catch (Exception $e)
{
	$data['error'] = $e->getMessage();
}

/* Вывод в json */
header("Content-type: application/json");
echo json_encode($data);
?>