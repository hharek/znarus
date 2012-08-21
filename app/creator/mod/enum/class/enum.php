<?php
/**
 * Перечисления
 */
class ZN_Enum
{
	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @param int $field_id
	 * @return bool
	 */
	public static function add($name, $field_id)
	{
		/* Проверка */
		if(!Chf::string($name))
        {Err::add("Наименование задано неверно. ".Chf::error(), "name");}
		
		ZN_Field::is_id($field_id);
		
		Err::exception();
		
		/* Уникальность */
		self::_unique($name, $field_id);
		
		Err::exception();
		
		/* Добавить */
		$query = 
<<<SQL
INSERT INTO "enum" ("Name", "Field_ID")
VALUES ($1, $2)
SQL;
		Reg::db_creator()->query($query, array($name, $field_id), "enum", true);
		
		return true;
	}
	
	/**
	 * Редактировать
	 *
	 * @param int $id
	 * @param string $name
	 * @return bool 
	 */
	public static function edit($id, $name)
	{
		/* Проверка */
		self::is_id($id);
		
		if(!Chf::string($name))
        {Err::add("Наименование задано неверно. ".Chf::error(), "name");}
		
		Err::exception();
		
		/* Уникальность */
		$query = 
<<<SQL
SELECT "Field_ID"
FROM "enum"
WHERE "ID" = $1
SQL;
		$field_id = Reg::db_creator()->query_one($query, $id, "enum");
		
		self::_unique($name, $field_id, $id);
		
		Err::exception();
		
		/* Редактировать */
		$query = 
<<<SQL
UPDATE "enum"
SET 
	"Name" = $1
WHERE "ID" = $2
SQL;
		Reg::db_creator()->query($query, array($name, $id), "enum", true);
		
		return true;
	}
	
	/**
	 * Удалить
	 * 
	 * @param int $id
	 * @return bool
	 */
	public static function delete($id)
	{
		/* Проверка */
		self::is_id($id);
		
		/* Удалить */
		$query = 
<<<SQL
DELETE
FROM "enum"
WHERE "ID" = $1
SQL;
		Reg::db_creator()->query($query, $id, "enum", true);
		
		return true;
	}
	
	/**
	 * Проверка на существование
	 * 
	 * @param int $id
	 * @return bool
	 */
	public static function is_id($id)
	{
		if(!Chf::uint($id))
		{throw new Exception_Creator("Номер перечисления задан неверно. ". Chf::error());}
		
		$query = 
<<<SQL
SELECT COUNT(*) as count
FROM "enum"
WHERE "ID" = $1
SQL;
		$count = Reg::db_creator()->query_one($query, $id, "enum");
		if($count < 1)
		{throw new Exception_Creator("Перечисления с номером \"{$id}\" не существует.");}
		
		return true;
	}
	
	/**
	 * Сортировка верх
	 * 
	 * @param int $id
	 * @return bool
	 */
	public static function sort_up($id)
	{
		$enum = self::select_line_by_id($id);
		
		$query = 
<<<SQL
SELECT "ID", "Sort"
FROM "enum"
WHERE "Field_ID" = $1
ORDER BY "Sort" ASC
SQL;
        $enum_field = Reg::db_creator()->query_assoc($query, $enum['Field_ID'], "enum");
		if(count($enum_field) < 2)
		{return true;}
		
		foreach ($enum_field as $key=>$val)
		{
			if($val['ID'] == $id)
			{
				break;
			}
		}
		
		if($key == 0)
		{throw new Exception_Creator("Выше некуда.");}
		
		$id = $enum_field[$key]['ID'];
		$id_up = $enum_field[$key-1]['ID'];
		$sort = $enum_field[$key-1]['Sort'];
		$sort_up = $enum_field[$key]['Sort'];
		
		$query = 
<<<SQL
UPDATE "enum"
SET 
	"Sort" = $1
WHERE "ID" = $2
SQL;
		Reg::db_creator()->query($query, array($sort, $id), "enum", true);
		
		$query = 
<<<SQL
UPDATE "enum"
SET 
	"Sort" = $1
WHERE "ID" = $2
SQL;
		Reg::db_creator()->query($query, array($sort_up, $id_up), "enum", true);
		
		return true;
	}
	
	/**
	 * Сортировка вниз
	 * 
	 * @param int $id
	 * @return bool
	 */
	public static function sort_down($id)
	{
		$enum = self::select_line_by_id($id);
		
		$query = 
<<<SQL
SELECT "ID", "Sort"
FROM "enum"
WHERE "Field_ID" = $1
ORDER BY "Sort" ASC
SQL;
        $enum_field = Reg::db_creator()->query_assoc($query, $enum['Field_ID'], "enum");
		if(count($enum_field) < 2)
		{return true;}
		
		foreach ($enum_field as $key=>$val)
		{
			if($val['ID'] == $id)
			{
				break;
			}
		}
		
		if($key == count($enum_field)-1)
		{throw new Exception_Creator("Ниже некуда.");}
		
		$id = $enum_field[$key]['ID'];
		$id_up = $enum_field[$key+1]['ID'];
		$sort = $enum_field[$key+1]['Sort'];
		$sort_up = $enum_field[$key]['Sort'];
		
		$query = 
<<<SQL
UPDATE "enum"
SET 
	"Sort" = $1
WHERE "ID" = $2
SQL;
		Reg::db_creator()->query($query, array($sort, $id), "enum", true);
		
		$query = 
<<<SQL
UPDATE "enum"
SET 
	"Sort" = $1
WHERE "ID" = $2
SQL;
		Reg::db_creator()->query($query, array($sort_up, $id_up), "enum", true);
		
		return true;
	}
	
	/**
	 * Уникальность
	 * 
	 * @param string $name
	 * @param int $field_id
	 * @param int $id
	 * @return bool
	 */
	private static function _unique($name, $field_id, $id=null)
	{
		/* Наименование */
		$query = 
<<<SQL
SELECT COUNT(*) as count
FROM "enum"
WHERE "Name" = $1
AND "Field_ID" = $2
SQL;
		if(!is_null($id))
		{$query .= "AND \"ID\" != '{$id}'";}
		
		$count = Reg::db_creator()->query_one($query, array($name, $field_id), "enum");
		if($count > 0)
		{Err::add("Перечисление с именем \"{$name}\" уже существует.", "name");}
		
		return true;
	}
	
	/**
	 * Выборка списка по полю
	 * 
	 * @param int $field_id
	 * @return array
	 */
	public static function select_list_by_field_id($field_id)
	{
		ZN_Field::is_id($field_id);
		
		$query = 
<<<SQL
SELECT "ID", "Name"
FROM "enum"
WHERE "Field_ID" = $1
ORDER BY "Sort" ASC
SQL;
        $enum = Reg::db_creator()->query_assoc($query, $field_id, "enum");
		
		return $enum;
	}
	
	/**
	 * Выборка строки по ID
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function select_line_by_id($id)
	{
		ZN_Enum::is_id($id);
		
		$query = 
<<<SQL
SELECT "ID", "Name", "Field_ID"
FROM "enum"
WHERE "ID" = $1
SQL;
		$enum = Reg::db_creator()->query_line($query, $id, "enum");
		
		return $enum;
	}
	
	
}
?>
