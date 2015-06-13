<?php
/* Назначаем переменные */
if (G::location() === "constr")
{
	$_ip_access_mode = CONSTR_IP_ACCESS_MODE;
	$_ip_access = CONSTR_IP_ACCESS;
}
elseif (G::location() === "admin")
{
	$_ip_access_mode = ADMIN_IP_ACCESS_MODE;
	$_ip_access = ADMIN_IP_ACCESS;
}

/* Проверка указанных значений */
if (!in_array($_ip_access_mode, ["allow_all", "allow_all_except", "deny_all", "deny_all_except"]))
{
	throw new Exception("Режим доступа по IP задан неверно.");
}

if (!empty($_ip_access) and !is_array($_ip_access))
{
	$_ip_access = [(string)$_ip_access];
}
elseif (empty($_ip_access))
{
	$_ip_access = [];
}

/* Проверка доступа */
switch ($_ip_access_mode)
{
	/* Всём разрешено */
	case "allow_all":
	{

	}
	break;

	/* Всем разрешно кроме */
	case "allow_all_except":
	{
		if (in_array($_SERVER['REMOTE_ADDR'], $_ip_access))
		{
			header("HTTP/1.0 403 Forbidden");
			throw new Exception("403. Доступ по указанному IP запрещён.");
		}
	}
	break;

	/* Запрещено всем */
	case "deny_all":
	{
		if ($_SERVER['REMOTE_ADDR'] !== "127.0.0.1")
		{
			header("HTTP/1.0 403 Forbidden");
			throw new Exception("403. Доступ по указанному IP запрещён.");
		}
	}
	break;

	/* Запрещено всем кроме */
	case "deny_all_except":
	{
		if (!in_array($_SERVER['REMOTE_ADDR'], $_ip_access))
		{
			header("HTTP/1.0 403 Forbidden");
			throw new Exception("403. Доступ по указанному IP запрещён.");
		}
	}
	break;
}
?>