<?php
/* Проверка сессии */
if (G::session_check() === true)
{
	throw new Exception("Вы уже авторизованы.");
}

/* Задержка перед выполнением */
if (G::location() === "constr")
{
	sleep(CONSTR_AUTH_SLEEP);
}
elseif (G::location() === "admin")
{
	sleep(ADMIN_AUTH_SLEEP);
}

$data = [];
try
{
	/* Проверка токена */
	if ($_POST['token'] !== md5(SALT . $_SESSION['_auth_token']))
	{
		throw new Exception("Токен задан неверно.");
	}
	
	/* Расшифровка POST данных зашифрованных открытым ключом */
	if (JSENCRYPT_AUTH === true)
	{
		$private_key = file_get_contents(JSENCRYPT_PRIVATE_KEY);
		$email = ""; $password = "";
		openssl_private_decrypt(base64_decode($_POST['Email']), $email, $private_key);
		openssl_private_decrypt(base64_decode($_POST['Password']), $password, $private_key);
	}
	/* Расшифровывать не нужно */
	else
	{
		$email = $_POST['Email'];
		$password = $_POST['Password'];
	}
	
	/* Авторизация */
	if (G::location() === "constr")
	{
		_User_Action::login_constr($email, $password);
	}
	elseif (G::location() === "admin")
	{
		_User_Action::login_admin($email, $password);
	}
	
	/* Последнее посещение */
	$data['result'] = true;
	$data['visit_last'] = _User_Action::visit_last_get(G::location());
}
catch (Exception $e)
{
	$data['error'] = $e->getMessage();
}

/* Вывод в json */
header("Content-type: application/json");
echo json_encode($data);
?>