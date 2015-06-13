<?php
/* ----------------------- Функции для exe ------------------------ */

/**
 * Заголовок
 * 
 * @param string $title
 */
function title($title)
{
	global $_data;
	
	$title = trim((string)$title);
	if ($title !== "")
	{
		$_data['title'] = $title;
	}
}

/**
 * Путь
 * 
 * @param array $path
 */
function path($path)
{
	global $_data;
	
	if (!empty($path))
	{
		$_data['path'] = [];
		foreach ($path as $val)
		{
			if (preg_match("#([^\[]*)\[(.*)\]#isu", $val, $match))
			{
				$_data['path'][] = 
				[
					"name" => trim($match[1]),
					"url" => trim($match[2])
				];
			}
			else
			{
				$_data['path'][] = 
				[
					"name" => $val,
					"url" => null
				];
			}
		}
	}
}

/**
 * Сообщение об успешном выполнении
 * 
 * @param string $message
 */
function mess_ok($message)
{
	global $_data;
	
	$message = trim((string)$message);
	if ($message !== "")
	{
		$_data['mess_ok'] = $message;
	}
}

/**
 * Переход на другую страницу
 * 
 * @param string $url
 */
function redirect($url)
{
	global $_data;
	
	$url = trim((string)$url);
	if ($url !== "")
	{
		$_data['redirect'] = $url;
	}
}

/**
 * Перезагрузить страницу
 */
function reload()
{
	global $_data;
	
	$_data['reload'] = true;
}

/**
 * Javascript
 * 
 * @param string $js
 */
function js($js)
{
	global $_data;
	
	$js = trim((string)$js);
	if ($js !== "")
	{
		$_data['js'] = $js;
	}
}

/**
 * Загрузить пакет JavaScript
 * 
 * @param string $identified
 * @param mixed $param
 */
function packjs($identified, $param = null)
{
	global $_data;
	
	/* Создать массивы */
	if (!isset($_data['packjs_init']))	{ $_data['packjs_init'] = []; }
	if (!isset($_data['packjs']))		{ $_data['packjs'] = [];	  }
	
	/* Сведения по пакету */
	$query = 
<<<SQL
SELECT
	"ID",
	"Identified"
FROM
	"packjs"
WHERE
	"Identified" = $1 OR
	"Category" = $2
LIMIT 1
SQL;
	$packjs = G::db_core()->query($query, [$identified, $identified])->row();
	if (empty($packjs))
	{
		throw new Exception("Пакет Javascript «{$identified}» не установлен.");
	}
	
	/* Зависемости */
	$query = 
<<<SQL
SELECT
	"p"."Identified"
FROM
	"packjs_depend" as "pd",
	"packjs" as "p"
WHERE
	"pd"."Packjs_ID" = $1 AND
	"pd"."Depend_ID" = "p"."ID"
SQL;
	$depend = G::db_core()->query($query, $packjs['ID'])->column();
	if ($depend !== null)
	{
		foreach ($depend as $depend_identified)
		{
			if (!in_array($depend_identified, $_data['packjs_init']))
			{
				$_data['packjs_init'][] = $depend_identified;
			}
		}
	}
	
	/* Добавить к инициированным */
	if (!in_array($packjs['Identified'], $_data['packjs_init']))
	{
		$_data['packjs_init'][] = $packjs['Identified'];
	}

	/* Добавить к объектам */
	$_data['packjs'][] = 
	[
		"identified" => $packjs['Identified'],
		"param" => $param
	];
}

/**
 * Версионность
 * 
 * @param string $identified
 */
function version($identified)
{
	global $_data;
	
	$identified = trim((string)$identified);
	if ($identified !== "")
	{
		$_data['version'] = $identified;
	}
}

/**
 * Черновик
 * 
 * @param string $identified
 */
function draft($identified)
{
	global $_data;
	
	$identified = trim((string)$identified);
	if ($identified !== "")
	{
		$_data['draft'] = $identified;
	}
}

/**
 * Включить автосохранение
 */
function autosave()
{
	global $_data;
	
	$_data['autosave'] = true;
}
?>