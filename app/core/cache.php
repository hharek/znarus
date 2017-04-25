<?php
/**
 * Класс для объединения работы с _Cache_Front и _Cache
 */
class Cache
{
	/**
	 * Кэш БД «core»
	 * 
	 * @var _Cache
	 */
	public static $db_core;
	
	/**
	 * Кэш БД «public»
	 * 
	 * @var _Cache
	 */
	public static $db;
	
	/**
	 * Кэш маршрута
	 * 
	 * @var _Cache
	 */
	public static $route;
	
	/**
	 * Кэш страниц
	 * 
	 * @var _Cache
	 */
	public static $page;
	
	/**
	 * Кэш аякса
	 * 
	 * @var _Cache 
	 */
	public static $ajax;
	
	/**
	 * Получить сведения по маршрутам и страницам
	 * 
	 * @param string $param
	 * @return array
	 */
	public static function info($param = null)
	{
		return _Cache_Front::info($param);
	}
	
	/**
	 * Удалить кэш страниц и маршрутов
	 * 
	 * @param array $param
	 */
	public static function delete($param)
	{
		return _Cache_Front::delete($param);
	}
	
	/**
	 * Очистить кэш 
	 */
	public static function truncate()
	{
		self::$db_core->truncate();
		self::$db->truncate();
		self::$route->truncate();
		self::$page->truncate();
		self::$ajax->truncate();
	}
}
?>