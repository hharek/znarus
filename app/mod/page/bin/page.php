<?php
/**
 * Страницы
 */
class Page
{
	/**
	 * Все страницы
	 * 
	 * @var array
	 */
	private static $_page_all = [];
	
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 */
	public static function is($id)
	{
		if (!Chf::uint($id))
		{
			throw new Exception("Номер у страницы задан неверно. " . Chf::error());
		}
		
		$is = G::cache_db()->get("page_is_" . $id);
		if ($is === null)
		{
			$is = (bool)G::db()->page_is($id)->single();
			G::cache_db()->set("page_is_" . $id, $is, "page");
		}
		
		if ($is === false)
		{
			throw new Exception("Страницы с номером «{$id}» не существует.");
		}
	}
	
	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @param string $url
	 * @param string $content
	 * @param string $tags
	 * @param int $parent
	 * @param int $html_id
	 * @return array
	 */
	public static function add($name, $url, $content, $tags, $parent, $html_id)
	{
		/* Проверка */
		self::_check($name, $url, $content, $tags, $parent, $html_id);

		/* Уникальность */
		self::_unqiue($name, $url, $parent);

		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Url" => $url,
			"Content" => $content,
			"Tags" => mb_strtolower($tags),
			"Parent" => $parent === 0 ? null : $parent,
			"Html_ID" => $html_id === 0 ? null : $html_id
		];
		$id = G::db()->insert("page", $data, "ID");
		
		/* Изменить кэш */
		G::cache_db()->delete_tag("page");
		_Cache_Front::delete(["module" => "page"]);

		/* Данные добавленного */
		return self::get($id);
	}

	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $name
	 * @param string $url
	 * @param string $content
	 * @param string $tags
	 * @param int $parent
	 * @param string $html_id
	 * @return array
	 */
	public static function edit($id, $name, $url, $content, $tags, $parent, $html_id)
	{
		/* Проверка */
		self::is($id);
		self::_check($name, $url, $content, $tags, $parent, $html_id);

		/* Уникальность */
		self::_unqiue($name, $url, $parent, $id);

		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Url" => $url,
			"Content" => $content,
			"Tags" => mb_strtolower($tags),
			"Parent" => $parent === 0 ? null : $parent,
			"Html_ID" => $html_id === 0 ? null : $html_id,
			"Last_Modified" => "now()"
		];
		G::db()->update("page", $data, array("ID" => $id));
		
		/* Удалить кэширование */
		G::cache_db()->delete_tag("page");
		_Cache_Front::delete(["module" => "page"]);
		
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

		G::db()->delete("page", ["ID" => $id]);
		
		G::cache_db()->delete_tag("page");
		_Cache_Front::delete(["module" => "page"]);

		return $old;
	}

	/**
	 * Выборка строки по ID
	 * 
	 * @param int $id
	 * @return string
	 */
	public static function get($id)
	{
		/* Проверка */
		self::is($id);

		/* Выборка */
		$page = G::cache_db()->get("page_get_" . $id);
		if ($page === null)
		{
			$page = G::db()->page_get($id)->row();
			G::cache_db()->set("page_get_" . $id, $page, "page");
		}
		
		return $page;
	}

	/**
	 * Выборка всех страниц с подчинёнными (рекурсия)
	 * 
	 * @param int $parent
	 */
	public static function get_child_by_parent($parent, $current = 0)
	{
		/* Корень */
		$parent = (int) $parent;

		/* Текущая страница */
		$current = (int) $current;

		/* Все страницы */
		if (empty(self::$_page_all))
		{
			self::$_page_all = G::db()->page_all()->assoc();
		}

		/* Перебор */
		$child = [];
		foreach (self::$_page_all as $key => $val)
		{
			if ((int)$val['ID'] === $current)
			{
				continue;
			}

			if ((int)$val['Parent'] === $parent)
			{
				$child[] = 
				[
					'ID' => $val['ID'],
					'Name' => $val['Name'],
					'Url' => $val['Url'],
					'Child' => self::get_child_by_parent($val['ID'], $current)
				];

				unset(self::$_page_all[$key]);
			}
		}

		return $child;
	}
	
	/**
	 * Получить все урлы
	 * 
	 * @return array
	 */
	public static function get_url_all()
	{
		$url = G::cache_db()->get("page_url_all");
		if ($url === null)
		{
			$url = self::_get_url_by_parent();
			G::cache_db()->set("page_url_all", $url, "page");
		}
		
		return $url;
	}
	
	/**
	 * Получить урл
	 * 
	 * @param type $id
	 */
	public static function get_url($id)
	{
		$url_all = self::get_url_all();
		foreach ($url_all as $val)
		{
			if ((int)$val['ID'] === (int)$id)
			{
				return $val['Url'];
			}
		}
	}

	/**
	 * Получить данные по шаблону страницы
	 * 
	 * @param int $id
	 * @return int
	 */
	public static function get_html_data($id)
	{
		self::is($id);
		
		$html = G::cache_db()->get("page_html_" . $id);
		if ($html === null)
		{
			$html = G::db()->page_html_by_id($id)->row();
			G::cache_db()->set("page_html_" . $id, $html, "page");
		}
		
		return $html;
	}
	
	/**
	 * Получить информацию по странице
	 * 
	 * @param mixed $param
	 * @return array
	 */
	public static function page_info($param)
	{
		/* Главная страница модуля «Страницы» */
		if (empty($param))
		{
			return
			[
				"title" => "Страницы",
				"disable" => true,
				"child" => self::_get_child_by_parent_info(0, "/")
			];
		}
		
		/* Все другие страницы */
		if (!empty($param['id']))
		{
			/* Сведения по странице */
			$page = self::get($param['id']);
			
			/* Урл по странице */
			$url = self::get_url($page['ID']);
			
			/* Данные */
			return
			[
				"url" => $url,
				"title" => $page['Name'],
				"content" => $page['Content'],
				"tags" => $page['Tags'],
				"last_modified" => $page['Last_Modified'],
				"child" => self::_get_child_by_parent_info($page['ID'], $url)
			];
		}
	}

	/**
	 * Проверка полей
	 * 
	 * @param string $name
	 * @param string $url
	 * @param string $content
	 * @param string $tags
	 * @param string $parent
	 * @param string $html_id
	 */
	private static function _check($name, &$url, $content, &$tags, &$parent, &$html_id)
	{
		Err::check_field($name, "string", false, "Name", "Наименование");
		
		Err::check_field($url, "url_part", false, "Url", "Урл");
		$url = mb_strtolower($url);
		
		Err::check_field($content, "html", true, "Content", "Содержимое");
		
		Err::check_field($tags, "tags", true, "Tags", "Теги");
		$tags = mb_strtolower($tags);

		$parent = (int)$parent;
		if ($parent !== 0)
		{
			self::is($parent);
		}

		$html_id = (int)$html_id;
		if ($html_id !== 0)
		{
			try
			{
				_Html::is($html_id);
			}
			catch (Exception $e)
			{
				Err::add("Шаблон указан неверно.", "Html_ID");
			}
		}

		Err::exception();
	}

	/**
	 * Уникальность
	 * 
	 * @param string $name
	 * @param string $url
	 * @param int $parent
	 * @param int $id
	 */
	private static function _unqiue($name, $url, $parent, $id = null)
	{
		$query = 
<<<SQL
SELECT 
	true
FROM 
	"page"
WHERE 
	"Name" ILIKE $1 AND
	COALESCE("Parent", 0) = $2 AND
	"ID" != $3
SQL;
		$rec = G::db()->query($query, [$name, (int)$parent, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Страница с полем «Наименование» : «{$name}» уже существует.", "Name");
		}

		$query = 
<<<SQL
SELECT 
	true
FROM 
	"page"
WHERE 
	"Url" = $1 AND
	COALESCE("Parent", 0) = $2 AND
	"ID" != $3
SQL;
		$rec = G::db()->query($query, [$url, (int)$parent, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Страница с полем «Урл» : «{$url}» уже существует.", "Url");
		}
		
		Err::exception();
	}
	
	/**
	 * Получить все урлы
	 * 
	 * @param int $parent
	 * @return array
	 */
	private static function _get_url_by_parent($parent = 0, $url = "")
	{
		/* Корень */
		$parent = (int)$parent;
		
		/* Все страницы */
		if (empty(self::$_page_all))
		{
			self::$_page_all = G::db()->page_all()->assoc();
		}
		
		/* Перебор */
		$url_parent = [];
		foreach (self::$_page_all as $page)
		{
			if ((int)$page['Parent'] === $parent)
			{
				$url_parent[] = 
				[
					"ID" => $page['ID'],
					"Url" => $url . "/" . $page['Url'] . URL_END
				];
				
				$url_parent = array_merge($url_parent, self::_get_url_by_parent($page['ID'], $url . "/" . $page['Url']));
			}
		}
		
		return $url_parent;
	}
	
	/**
	 * Получить все страницы
	 * 
	 * @param int $parent
	 */
	public static function _get_child_by_parent_info($parent, $url)
	{
		/* Проверка */
		$parent = (int)$parent;
		if ($parent !== 0)
		{
			self::is($parent);
		}
		
		/* Выборка */
		$query = 
<<<SQL
SELECT 
	"ID",
	"Name",
	"Url"
FROM
	"page"
WHERE
	COALESCE ("Parent", 0) = $1
ORDER BY
	"Name" ASC
SQL;
		$page_child = G::db()->query($query, $parent)->assoc();
		
		$child = [];
		foreach ($page_child as $val)
		{
			$child[] = 
			[
				"url" => $url . $val['Url'],
				"title" => $val['Name'],
				"param" => 
				[
					"id" => $val['ID']
				]
			];
		}
		
		return $child;
	}
}
?>