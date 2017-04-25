<?php
/**
 * Теги
 */
class _Tags
{
	/**
	 * Добавить тег
	 * 
	 * @param string $name
	 * @return int
	 */
	public static function add($name)
	{
		/* Проверка */
		$name = trim($name);
		if (!Type::check("string", ($name)))
		{
			throw new Exception("Тег задан неверно.");
		}

		$name = mb_strtolower($name);

		/* Проверка на уникальность */
		$query = 
<<<SQL
SELECT 
	"ID",
	"Name",
	"Count"
FROM 
	"tags"
WHERE 
	"Name" = $1 
SQL;
		$tags = G::db_core()->query($query, $name)->row();

		/* Добавить новый тег */
		if (empty($tags))
		{
			$data = 
			[
				"Name" => $name
			];
			$id = G::db_core()->insert("tags", $data, "ID");
		}
		/* Добавить счётчик */
		else
		{
			$data = 
			[
				"Count" => (int) $tags['Count'] + 1
			];
			G::db_core()->update("tags", $data, ["ID" => $tags['ID']]);
			$id = $tags['ID'];
		}

		return $id;
	}

	/**
	 * Выборка строки по имени
	 * 
	 * @param string $name
	 * @return array
	 */
	public static function select_line_by_name($name)
	{
		/* Проверка */
		$name = trim($name);
		if (!Type::check("string", ($name)))
		{
			throw new Exception("Тег задан неверно.");
		}

		$name = mb_strtolower($name);

		/* SQL */
		$query = 
<<<SQL
SELECT 
	"ID",
	"Name",
	"Count"
FROM 
	"tags"
WHERE 
	"Name" = $1 
SQL;
		$tags = G::db_core()->query($query, $name)->row();

		return $tags;
	}

	/**
	 * Удалить все теги
	 */
	public static function truncate()
	{
		$query = 
<<<SQL
TRUNCATE "tags"
SQL;
		G::db_core()->query($query);

		$query = 
<<<SQL
ALTER SEQUENCE "tags_seq" RESTART
SQL;
		G::db_core()->query($query);
	}

	/**
	 * Выборка всех тегов
	 * 
	 * @return array
	 */
	public static function select_list()
	{
		$query = 
<<<SQL
SELECT 
	"ID",
	"Name",
	"Count"
FROM 
	"tags"
ORDER BY 
	"Name" ASC
SQL;
		$tags = G::db_core()->query($query)->assoc();

		return $tags;
	}
}
?>