<?php
/* Проверка сессии */
if (G::session_check() === false)
{
	header("HTTP/1.0 401 Unauthorized");
	throw new Exception("Для доступа к странице требуется аутентификация.");
}

/* Задержка */
if (G::location() === "constr")
{
	usleep(CONSTR_EXE_USLEEP);
}
elseif (G::location() === "admin")
{
	usleep(ADMIN_EXE_USLEEP);
}

$_data = [];

try
{
	/* Проверка токена */
	if (empty($_GET["_token"]))
	{
		throw new Exception("В GET отсутствует токен.");
	}

	if (empty($_COOKIE["_sid"]))
	{
		throw new Exception("В куки отсутствует токен.");
	}

	if ($_GET["_token"] !== $_COOKIE["_sid"])
	{
		throw new Exception("Токен указан неверно.");
	}
	
	/* Проверка урла */
	if (!Type::check("identified", G::url_path_ar()[1]) or !Type::check("identified", G::url_path_ar()[2]))
	{
		throw new Exception("Урл задан неверно.");
	}
	
	/* Переменные */
	$_mod = G::url_path_ar()[1];
	$_act = G::url_path_ar()[2]; 
	
	/* Папка с исполняемыми файлами */
	if (G::location() === "constr")
	{
		$_mod_path = DIR_TOOLS . "/constr/mod/" . $_mod;
	}
	elseif (G::location() === "admin")
	{
		if (substr($_mod, 0, 1) === "_")
		{
			$_mod_path = DIR_APP . "/smod/" . $_mod .  "/admin";
		}
		else
		{
			$_mod_path = DIR_APP . "/mod/" . $_mod .  "/admin";
		}
	}
	
	/* Необходимые функции */
	require DIR_TOOLS . "/_shared/php/exe_function.php";
	
	/* В случае фатальной ошибки */
	register_shutdown_function(function()
	{
		$e = error_get_last();
		if (!empty($e) and in_array($e['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_RECOVERABLE_ERROR]))
		{
			ob_end_clean();
			header("Content-type: application/json");
			echo json_encode
			([
				"error_sys" => "Ошибка: «{$e['message']}» в файле «{$e['file']}» ({$e['line']})"
			]);
			exit();
		}
	});

	/* Операции перед загрузкой файлов */
	if (G::location() === "admin")
	{
		require DIR_TOOLS . "/admin/exe.php";
	}
	
	/* Загрузка файла */
	ob_start();
	try
	{
		call_user_func(function ($_mod, $_act, $_mod_path)
		{
			if ($_SERVER['REQUEST_METHOD'] === "GET")
			{
				if (!is_file($_mod_path . "/get/" . $_act . ".php"))
				{
					throw new Exception("Отсутствует файл «" . $_mod_path . "/get/" . $_act . ".php».");
				}

				require $_mod_path . "/get/" . $_act . ".php";
				if (is_file($_mod_path . "/html/" . $_act . ".html"))
				{
					require $_mod_path . "/html/" . $_act . ".html";
				}
			}
			elseif ($_SERVER['REQUEST_METHOD'] === "POST")
			{
				/* Делаем trim POST-данных */
				foreach($_POST as $_k => $_v)
				{
					if (is_string($_v))
					{
						$_POST[$_k] = trim($_v);
					}
				}
				
				if (!is_file($_mod_path . "/post/" . $_act . ".php"))
				{
					throw new Exception("Отсутствует файл «" . $_mod . "/post/" . $_act . ".php».");
				}

				require $_mod_path . "/post/" . $_act . ".php";
			}
		}, $_mod, $_act, $_mod_path);	
	} 
	/* Ошибка в форме */
	catch (Exception_Form $e)
	{
		$_data['error_form'] = Err::get();
	}
	/* Ошибка в форме. Новая версия */
	catch (Exception_Many $e)
	{
		$_data['error_form'] = $e->get_err();
	}
	/* Ошибка */
	catch (Exception $e)
	{
		$_data['error'] = $e->getMessage();
	}

	/* Буфер в содержимое */
	$_data['content'] = ob_get_contents();
	ob_end_clean();

	/* Вывод если админка и в новом окне (Window = 1) */
	if (G::location() === "admin" and $_admin['Window'] === "1")
	{
		echo $_data['content'];
		exit();
	}
	
	/* CSS */
	if ($_SERVER['REQUEST_METHOD'] === "GET" and is_file($_mod_path . "/css/" . $_act . ".css"))
	{
		$less = new lessc();
		$_data['css'] = $less->compile(file_get_contents($_mod_path . "/css/" . $_act . ".css"));
	}

	/* Javascript в файле */
	$js_file = $_mod_path . "/js/" . $_act . ".js";
	if ($_SERVER['REQUEST_METHOD'] === "POST")
	{
		$js_file = $_mod_path . "/js/" . $_act . "_post.js";
	}
	
	if (is_file($js_file))
	{
		if (!isset($_data['js']))
		{
			$_data['js'] = "";
		}
		$_data['js'] .= "\n" . file_get_contents($js_file);
	}
	
	/* Записать последнюю посещаемую страницу */
	$_hash = "#" . $_mod . "/" . $_act;
	$_get = $_GET;	unset($_get['_token']); unset($_get['_']);
	if (!empty($_get))
	{
		$_hash .= "?" . http_build_query($_get);
	}
	_User_Action::visit_last_set(G::location(), urldecode($_hash));
}
/* Системная ошибка */
catch (Exception $e)
{
	$_data["error_sys"] = $e->getMessage();
}

header("Content-type: application/json");
echo json_encode($_data);
exit();
?>