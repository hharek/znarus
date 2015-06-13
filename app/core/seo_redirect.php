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
		if (!Chf::uint($id))
		{
			throw new Exception("Номер у переадресации задан неверно. " . Chf::error());
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
	 * @return array
	 */
	public static function add($from, $to, $location)
	{
		/* Проверка */
		self::_check($from, $to, $location);

		/* Уникальность */
		self::_unqiue($from);

		/* Добавить */
		$data = 
		[
			"From" => $from,
			"To" => $to,
			"Location" => $location
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
	 * @return array
	 */
	public static function edit($id, $from, $to, $location)
	{
		/* Проверка */
		self::is($id);
		self::_check($from, $to, $location);

		/* Уникальность */
		self::_unqiue($from, $id);

		/* Редактировать */
		$data = 
		[
			"From" => $from,
			"To" => $to,
			"Location" => $location
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
	"Location"::int
FROM 
	"seo_redirect"
WHERE 
	"ID" = $1
SQL;
		return G::db_core()->query($query, $id)->row();
	}

	/**
	 * Выборка всех по источнику
	 * 
	 * @param string $from
	 * @return array
	 */
	public static function get_by_from($from = "")
	{
		/* Проверка */
		$from = trim((string)$from);
		if ($from !== "" and !Chf::url($from))
		{
			throw new Exception("Источник указан неверно.");
		}
		$from = mb_strtolower($from);
		
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
SQL;
		return G::db_core()->query($query, "%" . $from . "%")->assoc();
	}
	
	/**
	 * Все редиректы
	 * 
	 * @return array
	 */
	public static function get_all()
	{
		$redirect = G::cache_db_core()->get("seo_redirect_all");
		if ($redirect === null)
		{
			$redirect = G::db_core()->seo_redirect_all()->assoc();
			G::cache_db_core()->set("seo_redirect_all", $redirect, "seo_redirect");
		}
		
		return $redirect;
	}

	/**
	 * Проверка полей
	 * 
	 * @param string $from
	 * @param string $to
	 * @param bool $location
	 */
	private static function _check(&$from, &$to, $location)
	{
		Err::check_field($from, "url", false, "From", "Источник");
		$from = mb_strtolower($from);
		if ($from === "/")
		{
			Err::add("Нельзя сделать переадресацию с главной страницы.", "From");
		}
		
		Err::check_field($to, "url", false, "To", "Назначение");
		$to = mb_strtolower($to);
		
		if ($from === $to)
		{
			Err::add("Назначение и Источник совпадают.", "To");
		}
		
		Err::check_field($location, "bool", false, "Location", "Делать переход на другой урл");
		
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