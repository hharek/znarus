<?php
/* Задержка */
if(Reg::sleep_time_admin() !== 0)
{
	sleep(Reg::sleep_time_admin());
}

$status = false; $error = "";
try
{
	/* Проверка на POST */
	if($_SERVER['REQUEST_METHOD'] != "POST")
	{
		throw new Exception("Принимаются только данные POST.", 1);
	}
	
	/* CSRF */
	if(empty($_POST['token']) or empty($_SESSION['admin_auth_token']))
	{
		throw new Exception("Ошибка CSRF.", 2);
	}
	
	if($_POST['token'] != $_SESSION['admin_auth_token'])
	{
		throw new Exception("Ошибка CSRF.", 3);
	}
	
	/* Расшифровка POST данных */
	$private_key = file_get_contents(__DIR__ . "/../conf/private.pem");
	$email = ""; $password = "";
	openssl_private_decrypt(base64_decode($_POST['email']), $email, $private_key);
	openssl_private_decrypt(base64_decode($_POST['password']), $password, $private_key);
	
	/* Проверка имени и пароля */
	ZN_User_Action::auth($email, $password);
	
	/* Удаление вспомогательных данных */
	unset($_SESSION['admin_auth_token']);
	
	$status = true;
}
catch (Exception $e)
{	
//	$error = $e->getMessage() . " Код: " . $e->getCode();
	$error = $e->getMessage();
}

/* Вывод */
header("Content-type: application/json");
echo json_encode(array
(
	"status" => $status,
	"error" => $error
));
?>