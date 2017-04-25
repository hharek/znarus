<?php
/**
 * Разные функции
 */

/* ------------- Основные функции используемые в шаблоне  ------------*/

/**
 * Заголовок
 * 
 * @param string $title
 */
function title($title = null)
{
	if ($title === null)
	{
		return _Front::$title;
	}
	else
	{
		$title = trim((string)$title);
		if ($title !== "")
		{
			_Front::$title = $title;
		}
	}
}

/**
 * Путь
 * 
 * @param array $path
 */
function path($path = null)
{
	if ($path === null)
	{
		return _Front::$path;
	}
	else
	{
		_Front::$path = []; $match = [];
		foreach ($path as $val)
		{
			if (preg_match("#([^\[]*)\[(.*)\]#isu", $val, $match))
			{
				_Front::$path[] = 
				[
					"name" => trim($match[1]),
					"url" => trim($match[2])
				];
			}
			else
			{
				_Front::$path[] = 
				[
					"name" => $val,
					"url" => null
				];
			}
		}
	}
}

/**
 * Тэги
 * 
 * @param string $tags
 * @return string
 */
function tags($tags = null)
{
	if ($tags === null)
	{
		return _Front::$tags;
	}
	else
	{
		$tags = trim((string)$tags);
		if ($tags !== "")
		{
			_Front::$tags = $tags;
		}
	}
}

/**
 * Назначить дату последнего изменения страницы
 * 
 * @param string $date
 */
function last_modified($date)
{
	$date = trim((string)$date);
	
	$date = strtotime($date);
	if ($date === false)
	{
		throw new Exception("Дата последнего изменения страницы указана неверно.");
	}
	
	_Front::$last_modified = gmdate(LAST_MODIFIED_FORMAT_DATE, $date);
}

/* ------------------------------ Исполнитель и Инк ---------------------------- */
/**
 * Показать содержимое
 */
function content()
{
	return _Front::$content;
}

/**
 * Загрузить инк
 * 
 * @param string $_module_identified
 * @param string $_identified
 * @return string
 */
function inc($_module_identified, $_identified)
{
	/* Данные по инку */
	$_inc = _Inc::get_by_identified($_module_identified, $_identified);
	if ((bool)$_inc['Active'] === false)
	{
		return "";
	}
	
	/* Выполнить */
	ob_start();
	require DIR_APP . "/" . _Module::get_type_by_identified($_module_identified) . "/" . $_module_identified . "/inc/act/" . $_identified . ".php";
	require DIR_APP . "/" . _Module::get_type_by_identified($_module_identified) . "/" . $_module_identified . "/inc/html/" . $_identified . ".html";
	$content = ob_get_contents();
	ob_end_clean();
	
	/* Записать в инк */
	_Front::$_inc[] = $_inc;
	
	return $content;
}

/**
 * Загрузить кусок html-а
 * 
 * @param string $_identified
 * @return string
 */
function html_part($_identified)
{
	ob_start();
	require DIR_APP . "/html/part/" . $_identified . ".html";
	$content = ob_get_contents();
	ob_end_clean();
	
	_Front::$_html_part[] = $_identified;
	
	return $content;
}

/*----------------------------------- Кэш ----------------------------------------*/
/**
 * Назначить структуру http запроса или переменные, участвующие в определении маршрута при одинаковом урле
 * 
 * @param array $get
 * @param array $post
 * @param array $cookie
 * @param array $session
 */
function route_in_filter($get, $post = null, $cookie = null, $session = null)
{
	/* Проверка */
	if ($get !== null and !is_array($get))
	{
		throw new Exception("Cache Route In. Переменные GET заданы неверно.");
	}
	
	if ($post !== null and !is_array($post) and $post !== "isset")
	{
		throw new Exception("Cache Route In. Переменные POST заданы неверно.");
	}
	
	if ($cookie !== null and !is_array($cookie))
	{
		throw new Exception("Cache Route In. Переменные COOKIE заданы неверно.");
	}
	
	if ($session !== null and !is_array($session))
	{
		throw new Exception("Cache Route In. Переменные SESSION заданы неверно.");
	}
	
	/* Назначить */
	_Front::$_route_in_filter = 
	[
		"get" => $get,
		"post" => $post,
		"cookie" => $cookie,
		"session" => $session
	];
}

/**
 * Назначить структуру http запроса или переменные, участвующие в отображении страницы при одинаковом урле
 * 
 * @param array $get
 * @param array $post
 * @param array $cookie
 * @param array $session
 */
function exe_in_filter($get, $post = null, $cookie = null, $session = null)
{
	/* Проверка */
	if ($get !== null and !is_array($get))
	{
		throw new Exception("Cache Exe In. Переменные GET заданы неверно.");
	}
	
	if ($post !== null and !is_array($post) and $post !== "isset")
	{
		throw new Exception("Cache Exe In. Переменные POST заданы неверно.");
	}
	
	if ($cookie !== null and !is_array($cookie))
	{
		throw new Exception("Cache Exe In. Переменные COOKIE заданы неверно.");
	}
	
	if ($session !== null and !is_array($session))
	{
		throw new Exception("Cache Exe In. Переменные SESSION заданы неверно.");
	}
	
	/* Назначить */
	_Front::$_exe_in_filter = 
	[
		"get" => $get,
		"post" => $post,
		"cookie" => $cookie,
		"session" => $session
	];
}

/* ------------------- Глобальные переменные для seo и функции работы с ними ------------------ */

/**
 * Тэг title
 * 
 * @param string $str
 * @return string
 */
function meta_title($str = null)
{
	if ($str === null)
	{
		if (G::_isset("meta_title"))
		{
			return G::meta_title();
		}
		
		if (_Front::$meta_title === "")
		{
			return _Front::$title;
		}
		else
		{
			return _Front::$meta_title;
		}
	}
	else
	{
		_Front::$meta_title = _delete_bad_symbols($str);
	}
}

/**
 * Тэг meta name="keywords"
 * 
 * @param string $str
 * @return string
 */
function meta_keywords($str = null)
{
	if ($str === null)
	{
		if (G::_isset("meta_keywords"))
		{
			return G::meta_keywords();
		}
		
		if (_Front::$meta_keywords === "")
		{
			return _Front::$tags;
		}
		else
		{
			return _Front::$meta_keywords;
		}
	}
	else
	{
		$str = mb_strtolower($str);
		_Front::$meta_keywords = _delete_bad_symbols($str);
	}
}

/**
 * Тэг meta name="description"
 * 
 * @param string $str
 * @return string
 */
function meta_description($str = null)
{
	if ($str === null)
	{
		if (G::_isset("meta_description"))
		{
			return G::meta_description();
		}
		
		if (_Front::$meta_description === "")
		{
			return _delete_bad_symbols(_Front::$content);
		}
		else
		{
			return _Front::$meta_description;
		}
	}
	else
	{
		_Front::$meta_description = _delete_bad_symbols($str);
	}
}

/**
 * Удалить ненужные символы
 * 
 * @param string $str
 * @return string
 */
function _delete_bad_symbols($str)
{
	$str = strip_tags($str);
	$str = str_replace(["\r", "\n", "\t"], " ", $str);
	while (strpos($str, "  ") !== false)
	{
		$str = str_replace("  ", " ", $str);
	}
	$str = trim($str);
	$str = html_entity_decode($str, ENT_QUOTES, "UTF-8");
	$str = mb_substr($str, 0, 250);
	
	return $str;
}
?>