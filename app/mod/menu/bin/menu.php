<?php
/**
 * Меню
 */
class Menu extends TM
{
	protected static $_name = "Меню";
	protected static $_table = "menu";
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
			"unique" => true,
			"order" => "asc"
		]	
	];
	
	/**
	 * Добавить
	 * 
	 * @param array $data
	 * @return array
	 */
	public static function add (array $data) : array
	{
		/* Удалить кэш */
		Cache::delete(["module" => "menu"]);
		
		return static::insert($data);
	}
	
	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param array $data
	 * @return array
	 */
	public static function edit (int $id, array $data) : array
	{
		/* Удалить кэш */
		Cache::delete(["module" => "menu"]);
		
		return static::update($data, $id);
	}
	
	/**
	 * Удалить
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function remove (int $id) : array
	{
		/* Удалить кэш */
		Cache::delete(["module" => "menu"]);
		
		return static::delete($id);
	}
}
?>