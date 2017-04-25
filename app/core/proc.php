<?php
/**
 * Процедуры
 */
class _Proc
{
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 */
	public static function is($id)
	{
		if (!Type::check("uint", $id))
		{
			throw new Exception("Номер у процедуры задан неверно. " . Type::get_last_error());
		}

		$query = 
<<<SQL
SELECT 
	true
FROM 
	"proc"
WHERE 
	"ID" = $1
SQL;
		$rec = G::db_core()->query($query, $id)->single();
		if ($rec === null)
		{
			throw new Exception("Процедуры с номером «{$id}» не существует.");
		}
	}
	
	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param int $module_id
	 * @return array
	 */
	public static function add($name, $identified, $module_id)
	{
		/* Проверка */
		self::_check($name, $identified);
		_Module::is($module_id);

		/* Уникальность */
		self::_unique($name, $identified, $module_id);

		/* Файлы */
		$module = _Module::get($module_id);
		$dir_proc = $module['Type'] . "/" . $module['Identified'] . "/proc";
		if (!G::file_app()->is_dir($dir_proc))
		{
			G::file_app()->mkdir($dir_proc);
		}
		G::file_app()->put($dir_proc . "/" . $identified . ".php", "<?php\n\n?>");

		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified,
			"Module_ID" => $module_id
		];
		$id = G::db_core()->insert("proc", $data, "ID");
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("proc");

		/* Данные добавленного */
		return self::get($id);
	}

	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $name
	 * @param string $identified
	 * @param bool $active
	 * @return array
	 */
	public static function edit($id, $name, $identified, $active)
	{
		/* Проверка */
		self::is($id);
		self::_check($name, $identified, $active);

		/* Уникальность */
		$old = self::get($id);
		self::_unique($name, $identified, $old['Module_ID'], $id);

		/* Файлы */
		$module = _Module::get($old['Module_ID']);
		$dir_proc = $module['Type'] . "/" . $module['Identified'] . "/proc";
		G::file_app()->mv
		(
			$dir_proc . "/" . $old['Identified'] . ".php", 
			$dir_proc . "/" . $identified . ".php"
		);

		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified,
			"Active" => (int)$active
		];
		G::db_core()->update("proc", $data, ["ID" => $id]);
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("proc");

		/* Данные изменённого */
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
		
		/* Файлы */
		$module = _Module::get($old['Module_ID']);
		$dir_proc = $module['Type'] . "/" . $module['Identified'] . "/proc";
		
		G::file_app()->rm($dir_proc . "/" . $old['Identified'] . ".php");
		if (count(G::file_app()->ls($dir_proc)) === 0)
		{
			G::file_app()->rm($dir_proc);
		}

		/* SQL */
		G::db_core()->delete("proc", ["ID" => $id]);
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("proc");

		/* Данные удалённого */
		return $old;
	}

	/**
	 * Установить порядок загрузки
	 * 
	 * @param int $id
	 * @param int $order (up|down)
	 */
	public static function order($id, $order)
	{
		$id = (int) $id;
		self::is($id);

		if (!in_array($order, ['up', 'down']))
		{
			$order = (int) $order;

			$data = 
			[
				"Order" => $order
			];
			G::db_core()->update("proc", $data, ["ID" => $id]);
		}
		else
		{
			$query = 
<<<SQL
SELECT 
	"ID", 
	"Order"
FROM 
	"proc"
ORDER BY 
	"Order" ASC
SQL;
			$other = G::db_core()->query($query)->assoc();

			if (count($other) < 2)
			{
				throw new Exception("Необходимо хотя бы два исполнителя.");
			}

			foreach ($other as $key => $val)
			{
				if ((int)$val['ID'] === $id)
				{
					break;
				}
			}

			if ($order === "up")
			{
				if ($key === 0)
				{
					throw new Exception("Выше некуда.");
				}

				$id_next = $other[$key - 1]['ID'];
				$order_int = $other[$key - 1]['Order'];
				$order_int_next = $other[$key]['Order'];
			}
			elseif ($order === "down")
			{
				if ($key === count($other) - 1)
				{
					throw new Exception("Ниже некуда.");
				}

				$id_next = $other[$key + 1]['ID'];
				$order_int = $other[$key + 1]['Order'];
				$order_int_next = $other[$key]['Order'];
			}

			$data = 
			[
				"Order" => $order_int
			];
			G::db_core()->update("proc", $data, ["ID" => $id]);

			$data = 
			[
				"Order" => $order_int_next
			];
			G::db_core()->update("proc", $data, ["ID" => $id_next]);
		}
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("proc");
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
	"Name",
	"Identified",
	"Module_ID",
	"Order"::int,
	"Active"::int
FROM 
	"proc"
WHERE 
	"ID" = $1
SQL;
		return G::db_core()->query($query, $id)->row();
	}

	/**
	 * Выборка всех по модулю
	 * 
	 * @param int $module_id
	 * @return array
	 */
	public static function get_by_module($module_id)
	{
		_Module::is($module_id);

		$query = 
<<<SQL
SELECT
	"ID",
	"Name",
	"Identified",
	"Module_ID",
	"Order"::int,
	"Active"::int
FROM 
	"proc"
WHERE 
	"Module_ID" = $1 
ORDER BY
	"Identified" ASC
SQL;
		return G::db_core()->query($query, $module_id)->assoc();
	}
	
	/**
	 * Получить все процедуры по типу
	 * 
	 * @param boolean $only_active
	 * @return array
	 */
	public static function get_all($only_active = false)
	{
		/* Проверка */
		$only_active = (int)(bool)$only_active;
		
		/* Выборка */
		$proc = G::cache_db_core()->get("proc_all_" . $only_active);
		if ($proc === null)
		{
			$proc = G::db_core()->proc_all($only_active)->assoc();
			G::cache_db_core()->set("proc_all_" . $only_active, $proc, "proc");
		}
		
		return $proc;
	}

	/**
	 * Проверка полей
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param bool $active
	 */
	private static function _check($name, &$identified, &$active = null)
	{
		Err::check_field($name, "string", false, "Name", "Наименование");
		
		Err::check_field($identified, "identified", false, "Identified", "Идентификатор");
		$identified = strtolower($identified);

		if ($active !== null)
		{
			Err::check_field($active, "bool", false, "Active", "Активность");
		}
		
		Err::exception();
	}

	/**
	 * Уникальность
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param int $module_id
	 * @param int $id
	 */
	private static function _unique($name, $identified, $module_id, $id = null)
	{
		$query = 
<<<SQL
SELECT 
	true
FROM 
	"proc"
WHERE 
	"Name" ILIKE $1 AND 
	"Module_ID" = $2 AND
	"ID" != $3
SQL;
		$rec = G::db_core()->query($query, [$name, $module_id, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Процедура с полем «Наименование» : «{$name}» уже существует.", "Name");
		}

		$query = 
<<<SQL
SELECT 
	true
FROM 
	"proc"
WHERE 
	"Identified" = $1 AND
	"Module_ID" = $2 AND
	"ID" != $3
SQL;
		$rec = G::db_core()->query($query, [$identified, $module_id, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Процедура с полем «Идентификатор» : «{$identified}» уже существует.", "Identified");
		}
		
		Err::exception();
	}
}
?>