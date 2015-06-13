<?php
/**
 * Адреса для продвижения
 */
class _Seo_Url
{
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 */
	public static function is($id)
	{
		if (!Chf::uint($id))
		{
			throw new Exception("Номер у урла задан неверно. " . Chf::error());
		}

		$query = 
<<<SQL
SELECT 
	true
FROM 
	"seo_url"
WHERE 
	"ID" = $1
SQL;
		$rec = G::db_core()->query($query, $id)->single();
		if ($rec === null)
		{
			throw new Exception("Урла с номером «{$id}» не существует.");
		}
	}
	
	/**
	 * Добавить
	 * 
	 * @param string $url
	 * @param string $title
	 * @param string $keywords
	 * @param string $description
	 * @return array
	 */
	public static function add($url, $title, $keywords, $description)
	{
		/* Проверка */
		self::_check($url, $title, $keywords, $description);

		/* Уникальность */
		self::_unqiue($url);

		/* Добавить */
		$data = 
		[
			"Url" => $url,
			"Title" => $title,
			"Keywords" => $keywords,
			"Description" => $description
		];
		$id = G::db_core()->insert("seo_url", $data, "ID");
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("seo_url");
		_Cache_Front::delete(["url_path" => $url]);

		/* Данные добавленного */
		return self::get($id);
	}

	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $url
	 * @param string $title
	 * @param string $keywords
	 * @param string $description
	 * @return array
	 */
	public static function edit($id, $url, $title, $keywords, $description)
	{
		/* Проверка */
		self::is($id);
		self::_check($url, $title, $keywords, $description);

		/* Уникальность */
		self::_unqiue($url, $id);

		/* Редактировать */
		$data = 
		[
			"Url" => $url,
			"Title" => $title,
			"Keywords" => $keywords,
			"Description" => $description
		];
		G::db_core()->update("seo_url", $data, ["ID" => $id]);
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("seo_url");
		_Cache_Front::delete(["url_path" => $url]);

		/* Данные удалённого */
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

		G::db_core()->delete("seo_url", ["ID" => $id]);
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("seo_url");
		_Cache_Front::delete(["url_path" => $old['Url']]);

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
	"Url",
	"Title",
	"Keywords",
	"Description"
FROM 
	"seo_url"
WHERE 
	"ID" = $1
SQL;
		return G::db_core()->query($query, $id)->row();
	}

	/**
	 * Выборка всех по урлу
	 * 
	 * @param string $url
	 * @return array
	 */
	public static function find_by_url($url = "")
	{
		$url = trim((string)$url);
		if ($url !== "" and !Chf::url($url))
		{
			throw new Exception("Урл задан неверно. " . Chf::error());
		}
		$url = mb_strtolower($url);
		
		$query = 
<<<SQL
SELECT 
	"ID",
	"Url",
	"Title",
	"Keywords",
	"Description"
FROM 
	"seo_url"
WHERE 
	"Url" LIKE $1
ORDER BY
	"Url" ASC
SQL;
		return G::db_core()->query($query, "%" . $url . "%")->assoc();
	}
	
	/**
	 * Сведения по адресу по продвижению
	 * 
	 * @param string $url
	 * @return array
	 */
	public static function get_by_url($url)
	{
		/* Проверка */
		if (!Chf::url($url))
		{
			throw new Exception("Урл задан неверно. " . Chf::error());
		}
		
		/* Урл */
		$seo_url = G::cache_db_core()->get("seo_url_" . md5($url));
		if ($seo_url === null)
		{
			$seo_url = G::db_core()->seo_url_by_url($url)->row();
			G::cache_db_core()->set("seo_url_" . md5($url), $seo_url, "seo_url");
		}
		
		return $seo_url;
	}

	/**
	 * Проверка полей
	 * 
	 * @param string $url
	 * @param string $title
	 * @param string $keywords
	 * @param string $description
	 */
	private static function _check(&$url, $title, $keywords, $description)
	{
		Err::check_field($url, "url", false, "Url", "Адрес");
		$url = mb_strtolower($url);
		if ($url === "/")
		{
			Err::add("Чтобы указать главную страницу для продвижения используйте админку «Другие страницы»", "Url");
		}
		
		Err::check_field($title, "string", true, "Title", "Тег title");
		Err::check_field($keywords, "text", true, "Keywords", "Тег meta keywords");
		Err::check_field($description, "text", true, "Description", "Тег meta description");
		
		Err::exception();
	}

	/**
	 * Уникальность
	 * 
	 * @param string $url
	 * @param int $id
	 */
	private static function _unqiue($url, $id = null)
	{
		$query = 
<<<SQL
SELECT 
	true
FROM 
	"seo_url"
WHERE 
	"Url" = $1 AND
	"ID" != $2
SQL;
		$rec = G::db_core()->query($query, [$url, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Урл «{$url}» уже существует.", "Url");
		}
		
		Err::exception();
	}
}
?>
