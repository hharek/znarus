<?php
/**
 * Статьи
 */
class Articles
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
			throw new Exception("Номер у статьи задан неверно. " . Chf::error());
		}
		
		$is = G::cache_db()->get("articles_is_" . $id);
		if ($is === null)
		{
			$is = (bool)G::db()->articles_is($id)->single();
			G::cache_db()->set("articles_is_" . $id, $is, "articles");
		}
		
		if ($is === false)
		{
			throw new Exception("Статьи с номером «{$id}» не существует.");
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
		$id = G::db()->insert("articles", $data, "ID");
		
		/* Удалить кэш */
		G::cache_db()->delete_tag("articles");
		_Cache_Front::delete(["module" => "articles"]);

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
		G::db()->update("articles", $data, ["ID" => $id]);
		
		/* Удалить кэш */
		G::cache_db()->delete_tag("articles");
		_Cache_Front::delete(["module" => "articles"]);

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

		G::db()->delete("articles", ["ID" => $id]);
		
		G::cache_db()->delete_tag("articles");
		_Cache_Front::delete(["module" => "articles"]);

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

		/* Статья */
		$articles = G::cache_db()->get("articles_" . $id);
		if ($articles === null)
		{
			$articles = G::db()->articles_get($id)->row();
			G::cache_db()->set("articles_" . $id, $articles, "articles");
		}
		
		return $articles;
	}
	
	/**
	 * Получить все статьи
	 * 
	 * @return array
	 */
	public static function get_all()
	{
		$articles = G::cache_db()->get("articles_all");
		if ($articles === null)
		{
			$articles = G::db()->articles_all()->assoc();
			G::cache_db()->set("articles_all", $articles, "articles");
		}
		
		return $articles;
	}

	/**
	 * Получить все урлы
	 * 
	 * @return array
	 */
	public static function get_url_all()
	{
		$url = G::cache_db()->get("articles_url_all");
		if ($url === null)
		{
			$url = G::db()->articles_url_all()->assoc();
			G::cache_db()->set("articles_url_all", $url, "articles");
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
		/* Главная страница модуля «Статьи» */
		if (empty($param))
		{
			/* Все статьи */
			$articles_all = self::get_all();
			$child = [];
			foreach ($articles_all as $val)
			{
				$child[] = 
				[
					"url" => "/статьи/" . $val['Url'],
					"title" => $val['Title'],
					"param" =>
					[
						"id" => $val['ID']
					]
				];
			}
			
			return
			[
				"url" => "/статьи",
				"title" => "Статьи",
				"content" => "Полезные статьи",
				"tags" => "статьи, полезные статьи",
				"child" => $child
			];
		}
		
		/* Другие страницы */
		if (!empty($param['id']))
		{
			$articles = self::get($param['id']);
			
			$date = date("d.m.Y", strtotime($articles['Date']));
			
			$content =
<<<HTML
{$articles['Content']}
Дата: {$date}
Анонс: {$articles['Anons']}
HTML;
			
			return
			[
				"url" => "/статьи/". $articles['Url'],
				"title" => $articles['Title'],
				"content" => $content,
				"tags" => $articles['Tags'],
				"last_modified" => $articles['Last_Modified'],
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
		
		Err::check_field($anons, "string", true, "Anons", "Анонс");
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
	"articles"
WHERE 
	"Title" ILIKE $1 AND
	"ID" != $2
SQL;
		$rec = G::db()->query($query, [$title, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Статья с полем «Заголовок» : «{$title}» уже существует.", "Title");
		}

		$query = 
<<<SQL
SELECT 
	true
FROM 
	"articles"
WHERE 
	"Url" ILIKE $1 AND
	"ID" != $2
SQL;
		$rec = G::db()->query($query, [$url, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Статья с полем «Адрес» : «{$url}» уже существует.", "Url");
		}
		
		Err::exception();
	}
}
?>