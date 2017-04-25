<?php
/**
 * Основной шаблон
 */
class _Html extends TM
{
	protected static $_name = "Основной шаблон";
	protected static $_schema = "core";
	protected static $_table = "html";
	protected static $_field = 
	[
		[
			"identified" => "ID",
			"name" => "Порядковый номер",
			"type" => "id"
		],
		[
			"identified" => "Name",
			"name" => "Наименование",
			"type" => "string",
			"unique" => true
		],
		[
			"identified" => "Identified",
			"name" => "Идентификатор",
			"type" => "identified",
			"unique" => true
		]
	];
	
	/**
	 * HTML-код для новых шаблонов
	 * 
	 * @var string
	 */
	private static $_html_code = 
<<<HTML
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title><?php echo meta_title(); ?></title>
		<meta name="description" content="<?php echo meta_description(); ?>" />
		<meta name="keywords" content="<?php echo meta_keywords(); ?>" />
	</head>
	<body>
		<h1><?php echo title(); ?></h1>
		<?php echo content(); ?>
	</body>
</html>
HTML;

	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @param string $identified
	 * @return array
	 */
	public static function add($name, $identified)
	{
		/* Проверка */
		self::_check($name, $identified);

		/* Уникальность */
		self::_unique($name, $identified);

		/* Файл */
		G::file_app()->put("html/" . $identified . ".html", self::$_html_code);

		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified
		];
		$id = G::db_core()->insert("html", $data, "ID");
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("html");

		/* Данные добавленного */
		return self::get($id);
	}

	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $name
	 * @param string $identified
	 * @return array
	 */
	public static function edit($id, $name, $identified)
	{
		/* Проверка */
		self::is($id);
		self::_check($name, $identified);
		
		/* Уникальность */
		self::_unique($name, $identified, $id);

		/* Файл */
		$old = self::get($id);
		G::file_app()->mv
		(
			"html/" . $old['Identified'] . ".html", 
			"html/" . $identified . ".html"
		);

		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified
		];
		G::db_core()->update("html", $data, ["ID" => $id]);
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("html");
		_Cache_Front::delete(["html" => $identified]);
		
		/* Переименовать шаблон по умолчанию */
		if ($old['Identified'] === P::get("html_default"))
		{
			P::set("html_default", $identified);
		}
		
		/* Данные изменённого */
		return self::get($id);
	}

	/**
	 * Удалить
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function remove($id)
	{
		/* Проверка */
		$old = self::get($id);

		/* Нельзя удалить шаблон по умолчанию */
		if (P::get("html_default") === $old['Identified'])
		{
			throw new Exception("Нельзя удалить шаблон по умолчанию.");
		}

		/* Файл */
		G::file_app()->rm("html/" . $old['Identified'] . ".html");

		/* SQL */
		G::db_core()->delete("html", ["ID" => $id]);
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("html");

		/* Данные удалённого */
		return $old;
	}

	/**
	 * Назначить шаблоном по умолчанию
	 * 
	 * @param int $id
	 */
	public static function set_default($id)
	{
		$html = self::get($id);
		
		P::set("html_default", $html['Identified']);
	}

	/**
	 * Проверка по идентификатору
	 * 
	 * @param string $identified
	 */
	public static function is_identified($identified)
	{
		if (!Type::check("identified", $identified))
		{
			throw new Exception("Идентификатор у шаблона задан неверно. " . Type::get_last_error());
		}
		
		$html_is = G::cache_db_core()->get("html_is_identified_" . $identified);
		if ($html_is === null)
		{
			$html_is = (bool)G::db_core()->html_is_identified($identified)->single();
			G::cache_db_core()->set("html_is_identified_" . $identified, $html_is, "html");
		}
		
		if (!$html_is)
		{
			throw new Exception("Шаблона с идентификтором «{$identified}» не существует.");
		}
	}
	
	/**
	 * Проверка на существование по идентификатору
	 * 
	 * @param string $identified
	 * @return boolean
	 */
	public static function exist($identified)
	{
		if (!Type::check("identified", $identified))
		{
			return false;
		}
		
		$html_is = G::cache_db_core()->get("html_is_identified_" . $identified);
		if ($html_is === null)
		{
			$html_is = (bool)G::db_core()->html_is_identified($identified)->single();
			G::cache_db_core()->set("html_is_identified_" . $identified, $html_is, "html");
		}
		
		if ($html_is === false)
		{
			return false;
		}
		
		return true;
	}

	/**
	 * Выборка всех
	 * 
	 * @return array
	 */
	public static function get_all()
	{
		$query = 
<<<SQL
SELECT
	"ID",
	"Name",
	"Identified"
FROM 
	"html"
ORDER BY 
	"Identified" ASC
SQL;
		return G::db_core()->query($query)->assoc();
	}
	
	/**
	 * Получить html по идентификатору
	 * 
	 * @param string $identified
	 * @return array
	 */
	public static function get_by_identified($identified)
	{
		/* Проверка */
		if (!Type::check("identified", $identified))
		{
			throw new Exception("Идентификатор шаблона задан неверно. ");
		}
		
		/* Выборка */
		$html = G::cache_db_core()->get("html_" . $identified);
		if ($html === null)
		{
			$html = G::db_core()->html_by_identified($identified)->row();
			if (empty($html))
			{
				throw new Exception("Шаблона с идентификатором «{$identified}» не существует.");
			}
			G::cache_db_core()->set("html_" . $identified, $html, "html");
		}
		
		return $html;
	}

	/**
	 * Проверка полей
	 * 
	 * @param string $name
	 * @param string $identified
	 */
	private static function _check($name, &$identified)
	{
		Err::check_field($name, "string", false, "Name", "Наименование");
		
		Err::check_field($identified, "identified", false, "Identified", "Идентификатор");
		$identified = strtolower($identified);
		
		Err::exception();
	}

	/**
	 * Уникальность
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param int $id
	 */
	private static function _unique($name, $identified, $id = null)
	{
		$query = 
<<<SQL
SELECT 
	true
FROM 
	"html"
WHERE 
	"Name" ILIKE $1 AND 
	"ID" != $2
SQL;
		$rec = G::db_core()->query($query, [$name, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Шаблон с полем «Наименование» : «{$name}» уже существует.", "Name");
		}

		$query = 
<<<SQL
SELECT 
	true
FROM 
	"html"
WHERE 
	"Identified" = $1 AND
	"ID" != $2
SQL;
		$rec = G::db_core()->query($query, [$identified, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Шаблон с полем «Идентификатор» : «{$identified}» уже существует.", "Identified");
		}
		
		Err::exception();
	}
}
?>