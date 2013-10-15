<?php
/* Задержка */
if(Reg::sleep_time_constr() !== 0)
{
	sleep(Reg::sleep_time_constr());
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
	if(empty($_POST['token']) or empty($_SESSION['constr_auth_token']))
	{
		throw new Exception("Ошибка CSRF.", 2);
	}
	
	if($_POST['token'] != $_SESSION['constr_auth_token'])
	{
		throw new Exception("Ошибка CSRF.", 3);
	}
	
	/* Проверка имени и пароля */
	$private_key = file_get_contents(__DIR__ . "/../conf/private.pem");
	$name = ""; $password = "";
	openssl_private_decrypt(base64_decode($_POST['name']), $name, $private_key);
	openssl_private_decrypt(base64_decode($_POST['password']), $password, $private_key);
	
	if(Reg::root_name() !== $name or Reg::root_password() !== $password)
	{
		throw new Exception("Имя и пароль заданы неверно.", 4);
	}
	
	/* Удаление вспомогательных данных */
	unset($_SESSION['constr_auth_token']);
	
	/* Создание сессии */
	$sid = md5(Reg::salt_constr() . Reg::root_name() . Reg::root_password() . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
	setcookie("sid", $sid, time() + Reg::session_time_constr(), "/" . urlencode(Reg::url_constr()), null, false, true);
	
	/* Создание токена */
	$token = md5(microtime(true) + mt_rand(1, 100000000));
	setcookie("token", $token, time() + Reg::session_time_constr(), "/" . urlencode(Reg::url_constr()));
	
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