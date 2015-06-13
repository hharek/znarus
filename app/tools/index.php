<?php
try
{
	/* Нет слэша на конце */
	if (in_array(G::url(), ["/" . URL_CONSTR, "/" . URL_ADMIN]))
	{
		header("Location: " . G::url() . "/");
		exit();
	}

	/* Тип */
	if (G::url_path_ar()[0] === URL_CONSTR)
	{
		G::location("constr");
	}
	elseif (G::url_path_ar()[0] === URL_ADMIN)
	{
		G::location("admin");
	}

	/* Доступ по IP */
	require DIR_TOOLS . "/_shared/php/ip_access.php";

	/* Новый путь */
	G::url_path("/" . implode("/", array_slice(G::url_path_ar(), 1)));
	G::url_path_ar(explode("/", mb_substr(G::url_path(), 1)));

	/* Статические файлы */
	require DIR_TOOLS . "/_shared/php/static.php";
	
	/* Инициализация */
	require DIR_APP . "/init.php";
	
	/* Проверка сессии и получение данных по пользователю */
	G::session_check(_User_Session::check(G::location()));
	if (G::session_check() === true)
	{
		$_SESSION['_tools_session_check'] = true;
		G::user(_User_Action::data(G::location()));
	}
	elseif (G::session_check() === false)
	{
		unset($_SESSION['_tools_session_check']);
	}
	
	/* Основной урл */
	if (G::url_path() === "/")
	{
		if (G::session_check() === false)
		{
			/* Восстановить пароль */
			if (isset($_GET['restore']) and G::location() === "admin")
			{
				require DIR_TOOLS . "/_shared/html/restore.html";
			}
			/* Форма с авторизацией */
			else
			{
				require DIR_TOOLS . "/_shared/html/auth.html";
			}
		}
		/* Основной шаблон */
		elseif (G::session_check() === true)
		{
			require DIR_TOOLS . "/_shared/html/index.html";
		}
	}
	/* Восстановить пароль */
	elseif (G::url_path() === "/restore" and G::location() === "admin")
	{
		require DIR_TOOLS . "/_shared/php/restore.php";
	}
	/* Авторизация */
	elseif (G::url_path() === "/auth")
	{
		require DIR_TOOLS . "/_shared/php/auth.php";
	}
	/* Выход */
	elseif (G::url_path() === "/exit")
	{
		require DIR_TOOLS . "/_shared/php/exit.php";
	}
	/* Версии */
	elseif (G::url_path() === "/version")
	{
		require DIR_TOOLS . "/_shared/php/version.php";
	}
	/* Черновик. Получить */
	elseif (G::url_path() === "/draft_get")
	{
		require DIR_TOOLS . "/_shared/php/draft_get.php";
	}
	/* Черновик. Назначить */
	elseif (G::url_path() === "/draft_set")
	{
		require DIR_TOOLS . "/_shared/php/draft_set.php";
	}
	/* Exe */
	elseif (G::url_path_ar()[0] === "exe" and count(G::url_path_ar()) === 3)
	{
		require DIR_TOOLS . "/_shared/php/exe.php";
	}
	/* Установленные пакеты Javascript */
	elseif (G::url_path() === "/packjs")
	{
		require DIR_TOOLS . "/_shared/php/packjs.php";
	}
	/* 404 */
	else
	{
		header("HTTP/1.0 404 Not Found");
		throw new Exception("404. Страница не найдена.");
	}
}
catch (Exception $e)
{
	echo $e->getMessage();
}
?>