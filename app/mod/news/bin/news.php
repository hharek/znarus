<?php
/**
 * Новости
 */
class News
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
			throw new Exception("Номер у новости задан неверно. " . Chf::error());
		}
		
		$is = G::cache_db()->get("news_is_" . $id);
		if ($is === null)
		{
			$is = (bool)G::db()->news_is($id)->single();
			G::cache_db()->set("news_is_" . $id, $is, "news");
		}
		
		if ($is === false)
		{
			throw new Exception("Новости с номером «{$id}» не существует.");
		}
	}
	
	/**
	 * Добавить
	 * 
	 * @param string $date
	 * @param string $title
	 * @param string $url
	 * @param string $anons
	 * @param string $content
	 * @param string $tags
	 * @return array
	 */
	public static function add($date, $title, $url, $anons, $content, $tags)
	{
		/* Проверка */
		self::_check($date, $title, $url, $anons, $content, $tags);

		/* Уникальность */
		self::_unique($title, $url);

		/* SQL */
		$data = 
		[
			"Date" => $date,
			"Title" => $title,
			"Url" => $url,
			"Anons" => $anons,
			"Content" => $content,
			"Tags" => $tags
		];
		$id = G::db()->insert("news", $data, "ID");
		
		/* Удалить кэш */
		G::cache_db()->delete_tag("news");
		_Cache_Front::delete(["module" => "news"]);

		/* Данные добавленного */
		return self::get($id);
	}

	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $date
	 * @param string $title
	 * @param string $url
	 * @param string $anons
	 * @param string $content
	 * @param string $tags
	 * @return array
	 */
	public static function edit($id, $date, $title, $url, $anons, $content, $tags)
	{
		/* Проверка */
		self::is($id);
		self::_check($date, $title, $url, $anons, $content, $tags);

		/* Уникальность */
		self::_unique($title, $url, $id);

		/* SQL */
		$data = 
		[
			"Date" => $date,
			"Title" => $title,
			"Url" => $url,
			"Anons" => $anons,
			"Content" => $content,
			"Tags" => $tags,
			"Last_Modified" => "now()"
		];
		G::db()->update("news", $data, array("ID" => $id));
		
		/* Удалить кэш */
		G::cache_db()->delete_tag("news");
		_Cache_Front::delete(["module" => "news"]);

		/* Данные отредактированного */
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

		G::db()->delete("news", ["ID" => $id]);
		
		G::cache_db()->delete_tag("news");
		_Cache_Front::delete(["module" => "news"]);

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
		/* Проверка */
		self::is($id);

		/* Новость */
		$news = G::cache_db()->get("news_" . $id);
		if ($news === null)
		{
			$news = G::db()->news_get($id)->row();
			G::cache_db()->set("news_" . $id, $news, "news");
		}
		
		return $news;
	}
	
	/**
	 * Получить все новости
	 * 
	 * @return array
	 */
	public static function get_all()
	{
		$news = G::cache_db()->get("news_all");
		if ($news === null)
		{
			$news = G::db()->news_all()->assoc();
			G::cache_db()->set("news_all", $news, "news");
		}
		
		return $news;
	}

	/**
	 * Получить все урлы
	 * 
	 * @return array
	 */
	public static function get_url_all()
	{
		$url = G::cache_db()->get("news_url_all");
		if ($url === null)
		{
			$url = G::db()->news_url_all()->assoc();
			G::cache_db()->set("news_url_all", $url, "news");
		}
		
		return $url;
	}
	
	/**
	 * Функция Page Info
	 * 
	 * @param mixed $param
	 * @return array
	 */
	public static function page_info($param)
	{
		/* Главная страница модуля «Новости» */
		if (empty($param))
		{
			/* Все новости */
			$news_all = self::get_all();
			$child = [];
			foreach ($news_all as $val)
			{
				$child[] = 
				[
					"url" => "/новости/" . $val['Url'],
					"title" => $val['Title'],
					"param" =>
					[
						"id" => $val['ID']
					]
				];
			}
			
			return
			[
				"url" => "/новости",
				"title" => "Новости",
				"content" => "Последние новости. Новости сайта",
				"tags" => "новости, новости сайта, последние новости",
				"child" => $child
			];
		}
		
		/* Другие страницы */
		if (!empty($param['id']))
		{
			$news = self::get($param['id']);
			
			$date = date("d.m.Y", strtotime($news['Date']));
			
			$content =
<<<HTML
{$news['Content']}
Дата: {$date}
Анонс: {$news['Anons']}
HTML;
			
			return
			[
				"url" => "/новости/". $news['Url'],
				"title" => $news['Title'],
				"content" => $content,
				"tags" => $news['Tags'],
				"last_modified" => $news['Last_Modified'],
			];
		}
	}

	/**
	 * Проверка полей
	 * 
	 * @param string $date
	 * @param string $title
	 * @param string $url
	 * @param string $anons
	 * @param string $content
	 * @param string $tags
	 */
	private static function _check(&$date, $title, &$url, $anons, $content, &$tags)
	{
		Err::check_field($date, "date", false, "Date", "Дата");
		$date = date("Y-m-d", strtotime($date));
		
		Err::check_field($title, "string", false, "Title", "Заголовок");
		
		Err::check_field($url, "url_part", false, "Url", "Адрес");
		$url = mb_strtolower($url);
		
		Err::check_field($anons, "text", true, "Anons", "Анонс");
		Err::check_field($content, "html", true, "Content", "Содержание");
		
		Err::check_field($tags, "tags", true, "Tags", "Теги");
		$tags = mb_strtolower($tags);
		
		Err::exception();
	}

	/**
	 * Уникальность
	 * 
	 * @param string $title
	 * @param string $url
	 * @param int $id
	 */
	private static function _unique($title, $url, $id = null)
	{
		$query = 
<<<SQL
SELECT 
	true
FROM 
	"news"
WHERE 
	"Title" ILIKE $1 AND
	"ID" != $2
SQL;
		$rec = G::db()->query($query, [$title, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Новость с полем «Заголовок» : «{$title}» уже существует.", "Title");
		}

		$query = 
<<<SQL
SELECT 
	true
FROM 
	"news"
WHERE 
	"Url" ILIKE $1 AND
	"ID" != $2
SQL;
		$rec = G::db()->query($query, [$url, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Новость с полем «Адрес» : «{$url}» уже существует.", "Url");
		}
		
		Err::exception();
	}
}
?>