<?php
/**
 * Пакеты JavaScript
 */
class _Packjs
{
	/**
	 * Содержимое файла .packjs.js по умолчанию
	 * 
	 * @var string
	 */
	private static $_js_content = 
<<<JS
_packjs.{identified} = 
{
	/**
	 * Инициализация
	 */
	init: function ()
	{
		
	},
	
	/**
	 * Создание
	 */
	create: function (param)
	{
		
	},
	
	/**
	 * Освобождаем ресурсы
	 */
	clean: function ()
	{
		
	},
	
	/**
	 * Сохранить данные в html-элементе
	 */
	save: function ()
	{
		
	},
	
	/**
	 * Назначить данные из html-элемента
	 */
	set: function ()
	{
		
	}
};
JS;

	/**
	 * Проверка на существование по ID
	 * 
	 * @param int $id
	 */
	public static function is($id)
	{
		if (!Type::check("uint", $id))
		{
			throw new Exception("Номер Пакета JavaScript у задан неверно. " . Type::get_last_error());
		}

		$query = 
<<<SQL
SELECT 
	true
FROM 
	"packjs"
WHERE 
	"ID" = $1
SQL;
		$rec = G::db_core()->query($query, $id)->single();
		if ($rec === null)
		{
			throw new Exception("Пакета JavaScript с номером «{$id}» не существует.");
		}
	}
	
	/**
	 * Добавить
	 * 
	 * @param string $identified
	 * @param string $name
	 * @param string $description
	 * @param string $version
	 * @param string $url
	 * @param string $category
	 * @param string $depend
	 * @return array
	 */
	public static function add($identified, $name, $description, $version, $url, $category, $depend)
	{
		/* Проверка */
		self::_check($identified, $name, $description, $version, $url, $category);
		self::_check_string_depend($depend);
		
		/* Уникальность */
		self::_unique($identified, $name);
		
		/* Файлы */
		G::file_app()->mkdir("tools/_packjs/" . $identified);
		$js_content = str_replace("{identified}", $identified, self::$_js_content);
		G::file_app()->put("tools/_packjs/" . $identified . "/.packjs.js", $js_content);
		
		/* SQL */
		$data = 
		[
			"Identified" => $identified,
			"Name" => $name,
			"Description" => $description,
			"Version" => $version,
			"Url" => $url,
			"Category" => $category
		];
		$id = G::db_core()->insert("packjs", $data, "ID");
		
		/* Зависемости */
		if (!empty($depend))
		{
			$depend = explode(",", $depend);
			foreach ($depend as $depend_identified)
			{
				self::depend_add($id, self::get_id_by_identified($depend_identified));
			}
		}
		
		return self::get($id);
	}
	
	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $identified
	 * @param string $name
	 * @param string $description
	 * @param string $version
	 * @param string $url
	 * @param string $category
	 * @param string $depend
	 * @return type
	 */
	public static function edit($id, $identified, $name, $description, $version, $url, $category, $depend)
	{
		/* Проверка */
		self::is($id);
		self::_check($identified, $name, $description, $version, $url, $category);
		
		/* Уникальность */
		self::_unique($identified, $name, $id);
		
		/* Файлы */
		$old = self::get($id);
		if ($old['Identified'] !== $identified)
		{
			G::file_app()->mv
			(
				"tools/_packjs/" . $old['Identified'],
				"tools/_packjs/" . $identified
			);
		}
		
		/* SQL */
		$data = 
		[
			"Identified" => $identified,
			"Name" => $name,
			"Description" => $description,
			"Version" => $version,
			"Url" => $url,
			"Category" => $category
		];
		G::db_core()->update("packjs", $data, ["ID" => $id]);
		
		/* Зависемости */
		if ($old['Depend'] !== $depend)
		{
			G::db_core()->delete("packjs_depend", ["Packjs_ID" => $id]);

			if (!empty($depend))
			{
				self::_check_string_depend($depend);
				$depend = explode(",", $depend);
				foreach ($depend as $depend_identified)
				{
					self::depend_add($id, self::get_id_by_identified($depend_identified));
				}
			}
		}
		
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
		/* Проверка */
		$old = self::get($id);
		
		/* Проверить зависемости */
		$query = 
<<<SQL
SELECT
	COUNT(*) as "count"
FROM
	"packjs_depend"
WHERE
	"Depend_ID" = $1
SQL;
		$depend_count = G::db_core()->query($query, $id)->single();
		if ((int)$depend_count !== 0)
		{
			throw new Exception("Невозможно удалить, т.к. есть пакеты зависящие от указанного пакета.");
		}
		
		/* Файлы */
		G::file_app()->rm("tools/_packjs/" . $old['Identified']);
		
		/* SQL */
		G::db_core()->delete("packjs", ["ID" => $id]);
		
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
		
		/* Общий */
		$query = 
<<<SQL
SELECT
	"ID",
	"Identified",
	"Name",
	"Description",
	"Version",
	"Url",
	"Category"
FROM
	"packjs"
WHERE
	"ID" = $1
SQL;
		$packjs = G::db_core()->query($query, $id)->row();
		
		/* Зависемости */
		$packjs['Depend'] = "";
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
		$depend = G::db_core()->query($query, $id)->column();
		if (!empty($depend))
		{
			$packjs['Depend'] = implode(",", $depend);
		}
		
		return $packjs;
	}
	
	/**
	 * Выборка всех пакетов
	 * 
	 * @return array
	 */
	public static function get_all()
	{
		$query = 
<<<SQL
SELECT
	"ID",
	"Identified",
	"Name",
	"Version",
	"Url",
	"Category"
FROM
	"packjs"
ORDER BY
	"Identified"
SQL;
		return G::db_core()->query($query)->assoc();
	}
	
	/**
	 * Получить ID по идентификатору
	 * 
	 * @param string $identified
	 * @return int
	 */
	public static function get_id_by_identified($identified)
	{
		if (empty($identified) or !Type::check("identified", $identified))
		{
			throw new Exception("Идентификатор задан неверно.");
		}
		
		$query = 
<<<SQL
SELECT
	"ID"
FROM 
	"packjs"
WHERE 
	"Identified" = $1
SQL;
		$id = G::db_core()->query($query, $identified)->single();
		if ($id === null)
		{
			throw new Exception("Пакета JavaScript с идентификатором «{$identified}» не существует.");
		}
		
		return $id;
	}

	/**
	 * Добавить зависемость
	 * 
	 * @param int $id
	 * @param int $depend_id
	 */
	public static function depend_add($id, $depend_id)
	{
		/* Проверка */
		self::is($id);
		self::is($depend_id);
		
		if ((int)$id === (int)$depend_id)
		{
			throw new Exception("Пакет не может зависеть от самого себя.");
		}
		
		$query = 
<<<SQL
SELECT 
	true
FROM 
	"packjs_depend"
WHERE 
	"Packjs_ID" = $1 AND
	"Depend_ID" = $2
SQL;
		$rec = G::db_core()->query($query, [$id, $depend_id])->single();
		if ($rec !== null)
		{
			throw new Exception("Пакет с номером «{$id}» уже зависит от пакета с номером «{$depend_id}».");
		}
		
		/* Определить порядок загрузки */
		$order = 1;
		$query = 
<<<SQL
SELECT
	MAX("Order")
FROM 
	"packjs_depend"
WHERE 
	"Packjs_ID" = $1 
SQL;
		$order_max = G::db_core()->query($query, $id)->single();
		if ($order_max !== null)
		{
			$order = $order_max + 1;
		}
		
		/* SQL */
		$data = 
		[
			"Packjs_ID" => $id,
			"Depend_ID" => $depend_id,
			"Order" => $order
		];
		G::db_core()->insert("packjs_depend", $data);
	}
	
	/**
	 * Удалить зависемость
	 * 
	 * @param int $id
	 * @param int $depend_id
	 */
	public static function depend_delete($id, $depend_id)
	{
		/* Проверка */
		self::is($id);
		self::is($depend_id);
		
		$query = 
<<<SQL
SELECT 
	true
FROM 
	"packjs_depend"
WHERE 
	"Packjs_ID" = $1 AND
	"Depend_ID" = $2
SQL;
		$rec = G::db_core()->query($query, [$id, $depend_id])->single();
		if ($rec === null)
		{
			throw new Exception("Зависемость «{$id} - {$depend_id}» не найдена.");
		}
		
		/* SQL */
		G::db_core()->delete("packjs_depend", ["Packjs_ID" => $id, "Depend_ID" => $depend_id]);
	}

	/**
	 * Создать установочный файл
	 * 
	 * @param int $id
	 */
	public static function dump($id)
	{
		/* Сведения по пакету */
		$packjs = self::get($id);
		
		/* Создать packjs.json */
		$json = json_encode($packjs, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
		
		/* Создаём временную папку */
		$dir_dump = sys_get_temp_dir() . "/packjs_dump_" . md5(microtime());
		G::file()->mkdir($dir_dump);
		G::file()->put($dir_dump .  "/packjs.json", $json);
		
		/* Копируем файлы */
		G::file()->cp
		(
			DIR_TOOLS . "/_packjs/" . $packjs['Identified'],
			$dir_dump
		);
		
		G::file()->mv
		(
			$dir_dump . "/" . $packjs['Identified'],
			$dir_dump . "/files"
		);
		
		/* Выдать zip-файл */
		G::file()->zip
		(
			[
				$dir_dump . "/files", 
				$dir_dump . "/packjs.json"
			], 
			"packjs_" . $packjs['Identified'] . ".zip"
		);
		
		/* Удалить временную папку */
		G::file()->rm($dir_dump);
	}
	
	/**
	 * Установить через файл
	 * 
	 * @param string $file
	 */
	public static function install($file)
	{
		/* Проверка */
		$zip = new ZipArchive();
		$zip->open($file, ZipArchive::CHECKCONS);
		$json = $zip->getFromName("packjs.json");
		if ($json === false or $zip->getFromName("files/.packjs.js") === false)
		{
			throw new Exception("Установочный файл имеет неправильную архитектуру.");
		}
		
		/* Установка */
		$packjs = (array)json_decode($json);
		self::add
		(
			$packjs['Identified'], 
			$packjs['Name'], 
			$packjs['Description'], 
			$packjs['Version'], 
			$packjs['Url'], 
			$packjs['Category'], 
			$packjs['Depend']
		);
		
		/* Распаковака и копирование файлов */
		$dir_install = sys_get_temp_dir() . "/packjs_install_" . md5(microtime());
		$zip->extractTo($dir_install);
		
		$ls = G::file()->ls($dir_install . "/files");
		foreach ($ls as $val)
		{
			G::file()->cp
			(
				$dir_install . "/files/" . $val['name'], 
				DIR_TOOLS . "/_packjs/" . $packjs['Identified']
			);
		}
		
		G::file()->rm($dir_install);
	}
	
	/**
	 * Проверка строки «Зависемости»
	 * 
	 * @param string $depend
	 */
	private static function _check_string_depend(&$depend)
	{
		$depend = trim((string)$depend);
		if ($depend === "")
		{
			return;
		}
		
		$depend_ar = explode(",", $depend); $depend_ar_prepare = [];
		foreach ($depend_ar as $identified)
		{
			$identified = trim($identified);
			if (empty($identified) or !Type::check("identified", $identified))
			{
				throw new Exception("Зависемости указаны неверно.");
			}
			
			$query = 
<<<SQL
SELECT
	true
FROM
	"packjs"
WHERE
	"Identified" = $1
SQL;
			$rec = G::db_core()->query($query, $identified)->single();
			if ($rec === null)
			{
				throw new Exception("Отсутствует зависемость «{$identified}».");
			}
			
			$depend_ar_prepare[] = $identified;
		}
		$depend = implode(",", $depend_ar_prepare);
	}
	
	/**
	 * Проверка полей
	 * 
	 * @param string $identified
	 * @param string $name
	 * @param string $description
	 * @param string $version
	 * @param string $url
	 * @param string $category
	 */
	private static function _check(&$identified, $name, $description, $version, $url, $category)
	{
		Err::check_field($identified, "identified", false, "Identified", "Идентификатор");
		$identified = strtolower($identified);
		
		Err::check_field($name, "string", false, "Name", "Наименование");
		Err::check_field($description, "text", true, "Description", "Описание");
		Err::check_field($version, "string", true, "Version", "Версия");
		Err::check_field($url, "string", true, "Url", "Урл");
		Err::check_field($category, "identified", true, "Category", "Категория");
		
		Err::exception();
	}
	
	/**
	 * Проверка на уникальность
	 * 
	 * @param string $identified
	 * @param string $name
	 * @param int $id
	 */
	private static function _unique($identified, $name, $id = null)
	{
		$query = 
<<<SQL
SELECT 
	true
FROM 
	"packjs"
WHERE 
	"Identified" = $1 AND
	"ID" != $2
SQL;
		$rec = G::db_core()->query($query, [$identified, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Пакет JavaScript с полем «Идентификатор» : «{$identified}» уже существует.", "Identified");
		}
		
		$query = 
<<<SQL
SELECT 
	true
FROM 
	"packjs"
WHERE 
	"Name" ILIKE $1 AND
	"ID" != $2
SQL;
		$rec = G::db_core()->query($query, [$name, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Пакет JavaScript с полем «Наименование» : «{$name}» уже существует.", "Name");
		}
		
		Err::exception();
	}
}
?>