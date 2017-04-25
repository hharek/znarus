<?php
/**
 * Заголовок
 * 
 * @param string $title
 */
function title($title = null)
{
	$title = trim((string)$title);
	if ($title !== "")
	{
		_Front_Ajax::$title = $title;
	}
}

/**
 * Путь
 * 
 * @param array $path
 */
function path($path = null)
{	
	_Front_Ajax::$path = [];
	$match = [];
	
	foreach ($path as $val)
	{
		if (preg_match("#([^\[]*)\[(.*)\]#isu", $val, $match))
		{
			_Front_Ajax::$path[] = 
			[
				"name" => trim($match[1]),
				"url" => trim($match[2])
			];
		}
		else
		{
			_Front_Ajax::$path[] = 
			[
				"name" => $val,
				"url" => null
			];
		}
	}
	
}

/**
 * Тэги
 * 
 * @param string $tags
 */
function tags($tags = null)
{
	$tags = trim((string)$tags);
	if ($tags !== "")
	{
		_Front_Ajax::$tags = $tags;
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
	
	_Front_Ajax::$last_modified = gmdate(LAST_MODIFIED_FORMAT_DATE, $date);
}

/**
 * Сообщение об успешном выполнении
 * 
 * @param string $mess_ok
 */
function mess_ok($mess_ok)
{
	$mess_ok = trim((string)$mess_ok);
	if ($mess_ok !== "")
	{
		_Front_Ajax::$mess_ok = $mess_ok;
	}
}

/**
 * Переход на другую страницу
 * 
 * @param string $url
 */
function redirect($url)
{
	$url = trim((string)$url);
	if ($url !== "")
	{
		_Front_Ajax::$redirect = $url;
	}
}

/**
 * Javascript-код
 * 
 * @param string $js
 */
function js($js)
{
	$js = trim((string)$js);
	if ($js !== "")
	{
		_Front_Ajax::$js = $js;
	}
}

/**
 * Назначить структуру http запроса или переменные, участвующие в отображении аякса
 * 
 * @param array $get
 * @param array $post
 * @param array $cookie
 * @param array $session
 */
function ajax_in_filter($get, $post = null, $cookie = null, $session = null)
{
	/* Проверка */
	if ($get !== null and !is_array($get))
	{
		throw new Exception("Cache Ajax In. Переменные GET заданы неверно.");
	}
	
	if ($post !== null and !is_array($post) and $post !== "isset")
	{
		throw new Exception("Cache Ajax In. Переменные POST заданы неверно.");
	}
	
	if ($cookie !== null and !is_array($cookie))
	{
		throw new Exception("Cache Ajax In. Переменные COOKIE заданы неверно.");
	}
	
	if ($session !== null and !is_array($session))
	{
		throw new Exception("Cache Ajax In. Переменные SESSION заданы неверно.");
	}
	
	/* Назначить */
	_Front_Ajax::$_in_filter = 
	[
		"get" => $get,
		"post" => $post,
		"cookie" => $cookie,
		"session" => $session
	];
}
?>