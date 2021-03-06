<?php
/**
 * Адреса для переадресации
 */
class _Seo_Redirect
{
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function is($id)
	{
		if (!Type::check("uint", $id))
		{
			throw new Exception("Номер у переадресации задан неверно. " . Type::get_last_error());
		}
		
		$query = 
<<<SQL
SELECT 
	true
FROM 
	"seo_redirect"
WHERE 
	"ID" = $1
SQL;
		$rec = G::db_core()->query($query, $id)->single();
		if ($rec === null)
		{
			throw new Exception("Переадресации с номером «{$id}» не существует.");
		}
	}
	
	/**
	 * Добавить
	 * 
	 * @param string $from
	 * @param string $to
	 * @param bool $location
	 * @param string $tags
	 * @return array
	 */
	public static function add($from, $to, $location, $tags = "")
	{
		/* Проверка */
		self::_check($from, $to, $location, $tags);

		/* Уникальность */
		self::_unqiue($from);

		/* Добавить */
		$data = 
		[
			"From" => $from,
			"To" => $to,
			"Location" => $location,
			"Tags" => $tags
		];
		$id = G::db_core()->insert("seo_redirect", $data, "ID");
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("seo_redirect");
		_Cache_Front::delete(["url_path" => $from]);
		_Cache_Front::delete(["url_path" => $to]);
		
		/* Данные добавленного */
		return self::get($id);
	}

	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $from
	 * @param string $to
	 * @param bool $location
	 * @param string $tags
	 * @return array
	 */
	public static function edit($id, $from, $to, $location, $tags = "")
	{
		/* Проверка */
		self::is($id);
		self::_check($from, $to, $location, $tags);

		/* Уникальность */
		self::_unqiue($from, $id);

		/* Редактировать */
		$data = 
		[
			"From" => $from,
			"To" => $to,
			"Location" => $location,
			"Tags" => $tags
		];
		G::db_core()->update("seo_redirect", $data, ["ID" => $id]);
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("seo_redirect");
		_Cache_Front::delete(["url_path" => $from]);
		_Cache_Front::delete(["url_path" => $to]);
		
		/* Данные редактируемого */
		return self::get($id);
	}

	/**
	 * Удалить
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function delete($id)
	{
		$old = self::get($id);

		G::db_core()->delete("seo_redirect", ["ID" => $id]);
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("seo_redirect");
		_Cache_Front::delete(["url_path" => $old['From']]);
		_Cache_Front::delete(["url_path" => $old['To']]);
		
		return $old;
	}

	/**
	 * Выборка строки по ID
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function get($id)
	{
		self::is($id);

		$query = 
<<<SQL
SELECT
	"ID",
	"From",
	"To",
	"Location"::int,
	array_to_string ("Tags", ',', '*') as "Tags"
FROM 
	"seo_redirect"
WHERE 
	"ID" = $1
SQL;
		return G::db_core()->query($query, $id)->row();
	}
	
	/**
	 * Получить редирект по источнику
	 * 
	 * @param string $from
	 * @return array
	 */
	public static function get_by_from($from)
	{
		$redirect = G::cache_db_core()->get("seo_redirect_" . md5($from));
		if ($redirect === null)
		{
			$redirect = G::db_core()->seo_redirect_get_by_from($from)->row();
			G::cache_db_core()->set("seo_redirect_" . md5($from), $redirect, "seo_redirect");
		}
		
		return $redirect;
	}

	/**
	 * Выборка всех по источнику
	 * 
	 * @param string $from
	 * @return array
	 */
	public static function search($from = "", $page = 1)
	{
		/* Проверка */
		$from = trim((string)$from);
		if ($from !== "" and !Type::check("text", $from))
		{
			throw new Exception("Источник указан неверно.");
		}
		$from = mb_strtolower($from);
		
		/* Страница */
		$page = (int)$page;
		if ($page < 1)
		{
			$page = 1;
		}
		$limit = 20;
		$offset = ($page - 1) * $limit;
		
		/* Всего */
		$query = 
<<<SQL
SELECT
	COUNT(*) as "count"
FROM 
	"seo_redirect"
WHERE 
	"From" LIKE $1
SQL;
		$count = G::db_core()->query($query, "%" . $from . "%")->single();
		
		/* Выборка */
		$query = 
<<<SQL
SELECT
	"ID",
	"From",
	"To",
	"Location"::int
FROM 
	"seo_redirect"
WHERE 
	"From" LIKE $1
ORDER BY
	"From" ASC
LIMIT {$limit}
OFFSET {$offset}
SQL;
		$redirect = G::db_core()->query($query, "%" . $from . "%")->assoc();
		
		return 
		[
			"count" => $count,
			"redirect" => $redirect
		];
	}
	
	/**
	 * Удалить редиректы по тэгу
	 * 
	 * @param string $tag
	 * @return bool
	 */
	public static function delete_by_tag (string $tag) : bool
	{
		if (!Type::check("url_part", $tag))
		{
			throw new Exception("Тэг задан неверно.");
		}
		
		$query = 
<<<SQL
DELETE
FROM
	"seo_redirect"
WHERE
	'{$tag}' = ANY("Tags")
SQL;
		G::db_core()->query($query);
		
		return true;
	}
	
	/**
	 * Проверка полей
	 * 
	 * @param string $from
	 * @param string $to
	 * @param bool $location
	 * @param string $tags
	 */
	private static function _check(&$from, &$to, $location, &$tags)
	{
		Err::check_field($from, "text", false, "From", "Источник");
		$from = mb_strtolower($from);
		if ($from === "/")
		{
			Err::add("Нельзя сделать переадресацию с главной страницы.", "From");
		}
		
		Err::check_field($to, "text", false, "To", "Назначение");
		$to = mb_strtolower($to);
		
		if ($from === $to)
		{
			Err::add("Назначение и Источник совпадают.", "To");
		}
		
		Err::check_field($location, "bool", false, "Location", "Делать переход на другой урл");
		
		Err::check_field($tags, "tags", true, "Tags", "Тэги");
		if (!empty($tags))
		{
			$tags = explode(",", $tags);
			foreach ($tags as &$t) { $t = trim($t); }
			$tags = "{" . implode(",", $tags) . "}";
		}
		else
		{
			$tags = null;
		}
		
		Err::exception();
	}

	/**
	 * Уникальность
	 * 
	 * @param string $from
	 * @param int $id
	 */
	private static function _unqiue($from, $id = null)
	{
		$query = 
<<<SQL
SELECT 
	true
FROM 
	"seo_redirect"
WHERE 
	"From" = $1 AND
	"ID" != $2
SQL;
		$rec = G::db_core()->query($query, [$from, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Источник «{$from}» уже существует.", "From");
		}
		
		Err::exception();
	}
}
?>