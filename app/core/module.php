<?php
/**
 * Модуль
 */
class ZN_Module
{
	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param text $desc
	 * @param string $version
	 * @param enum $type (mod|smod)
	 * @param string $url
	 * @param int $html_id
	 * @param bool $active
	 * @return array
	 */
	public static function add($name, $identified, $desc, $version, $type, $url, $html_id, $active)
	{
		/* Проверка */
		Err::check_field($name, "string", false, "Name", "Наименование");
		Err::check_field($identified, "identified", false, "Identified", "Идентификатор");
		Err::check_field($desc, "text", true, "Desc", "Описание");
		Err::check_field($version, "string", false, "Version", "Версия");
		
		if(!in_array($type, array("mod","smod")))
		{Err::add("Поле «Тип» задано неверно. ".Chf::error(), "Type");}
		
		Err::check_field($url, "url", true, "Url", "Урл");
		
		if(!empty($html_id))
		{ZN_Html::is_id($html_id);}
		else
		{$html_id = null;}
		
		Err::check_field($active, "bool", false, "Active", "Активен");
		
		Err::exception();
		
		/* Уникальность */
		self::_unique($name, $identified);
		
		Err::exception();
		
		/* Создать папки и файлы */
		self::_create_files($type, $identified);
		
		/* SQL */
		$data = 
		[
			"Name" => $name, 
			"Identified" => $identified, 
			"Desc" => $desc, 
			"Version" => $version, 
			"Type" => $type, 
			"Url" => $url, 
			"Html_ID" => $html_id, 
			"Active" => $active
		];
		$id = Reg::db_core()->insert("module", $data, "ID");
		
		/* Данные добавленного */
		return self::select_line_by_id($id);
	}
	
	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $name
	 * @param string $identified
	 * @param text $desc
	 * @param string $version
	 * @param string $url
	 * @param int $html_id
	 * @param bool $active
	 * @return array
	 */
	public static function edit($id, $name, $identified, $desc, $version, $url, $html_id, $active)
	{
		/* Проверка */
		self::is_id($id);
		
		Err::check_field($name, "string", false, "Name", "Наименование");
		Err::check_field($identified, "identified", false, "Identified", "Идентификатор");
		Err::check_field($desc, "text", true, "Desc", "Описание");
		Err::check_field($version, "string", false, "Version", "Версия");
		Err::check_field($url, "url", true, "Url", "Урл");
		
		if(!empty($html_id))
		{ZN_Html::is_id($html_id);}
		else
		{$html_id = null;}
		
		Err::check_field($active, "bool", false, "Active", "Активен");
		
		Err::exception();
		
		/* Уникальность */
		self::_unique($name, $identified, $id);
		
		Err::exception();
		
		/* Переименовать файлы */
		$module = self::select_line_by_id($id);
		if($module['Identified'] != $identified)
		{
			Reg::file_app()->mv
			(
				$module['Type'] . "/" . $module['Identified'],
				$module['Type'] . "/" . $identified
			);
		}
		
		/* SQL */
		$data =
		[
			"Name" => $name, 
			"Identified" => $identified, 
			"Desc" => $desc, 
			"Version" => $version, 
			"Url" => $url, 
			"Html_ID" => $html_id, 
			"Active" => $active
		];
		Reg::db_core()->update("module", $data, array("ID" => $id));
		
		/* Данные изменённого */
		return self::select_line_by_id($id);
	}
	
	/**
	 * Удалить
	 * 
	 * @param int $id
	 */
	public static function delete($id)
	{
		/* Проверка */
		self::is_id($id);
		
		/* Удаление зависемостей */
		$phpclass = ZN_Phpclass::select_list_by_module_id($id);
		foreach ($phpclass as $val)
		{ZN_Phpclass::delete($val['ID']);}
		
		$param = ZN_Param::select_list_by_module_id($id);
		foreach ($param as $val)
		{ZN_Param::delete($val['ID']);}
		
		$admin = ZN_Admin::select_list_by_module_id($id);
		foreach ($admin as $val)
		{ZN_Admin::delete($val['ID']);}
		
		$exe = ZN_Exe::select_list_by_module_id($id);
		foreach ($exe as $val)
		{ZN_Exe::delete($val['ID']);}
		
		$inc = ZN_Inc::select_list_by_module_id($id);
		foreach ($inc as $val)
		{ZN_Inc::delete($val['ID']);}
		
		$text = ZN_Text::select_list_by_module_id($id);
		foreach ($text as $val)
		{ZN_Text::delete($val['ID']);}
		
		/* Удалить папку */
		$module = self::select_line_by_id($id);
		Reg::file_app()->rm($module['Type'] . "/" . $module['Identified']);
		
		/* SQL */
		Reg::db_core()->delete("module", array("ID" => $id));
		
		/* Данные удалённого */
		return $module;
	}
	
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 */
	public static function is_id($id)
	{
		if(!Chf::uint($id))
		{throw new Exception_Constr("Номер у модуля задан неверно. ".Chf::error());}
		
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"module"
WHERE 
	"ID" = $1
SQL;
		$count = Reg::db_core()->query_one($query, $id, "module");
		if($count < 1)
		{throw new Exception_Constr("Модуля с номером «{$id}» не существует.");}
	}
	
	/**
	 * Выборка строки по ID
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function select_line_by_id($id)
	{
		self::is_id($id);
		
		$query = 
<<<SQL
SELECT				
	"ID",
	"Name", 
	"Identified", 
	"Desc", 
	"Version", 
	"Type", 
	"Url", 
	"Html_ID", 
	"Active"::int
FROM 
	"module"
WHERE 
	"ID" = $1
SQL;
		$module = Reg::db_core()->query_line($query, $id, "module");
		
		return $module;
	}
	
	/**
	 * Выборка всех
	 * 
	 * @param enum $type (all|mod|smod)
	 * @return array
	 */
	public static function select_list($type = "all")
	{
		if(!in_array($type, array("all","smod","mod")))
		{
			throw new Exception_Constr("Тип модулей задан неверно.");
		}
		
		$sql_type = "";
		if($type != "all")
		{
			$sql_type = "WHERE \"Type\" = '{$type}'";
		}
		
		$query = 
<<<SQL
SELECT
	"ID",
	"Name", 
	"Identified", 
	"Desc", 
	"Version", 
	"Type", 
	"Url", 
	"Html_ID", 
	"Active"::int
FROM 
	"module"
{$sql_type}
ORDER BY 
	"Identified" ASC
SQL;
		$module = Reg::db_core()->query_assoc($query, null, "module");
		
		return $module;
	}
	
	/**
	 * Уникальность
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param int $id
	 */
	private static function _unique($name, $identified, $id=null)
	{
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"module"
WHERE 
	"Name" = $1
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db_core()->query_one($query, $name, "module");
		if($count > 0)
		{Err::add("Модуль с полем «Наименование» : «{$name}» уже существует.", "Name");}

		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"module"
WHERE 
	"Identified" = $1
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db_core()->query_one($query, $identified, "module");
		if($count > 0)
		{Err::add("Модуль с полем «Идентификатор» : «{$identified}» уже существует.", "Identified");}
	}
	
	/**
	 * Создать файлы и папки для модуля
	 * 
	 * @param string $identified
	 * @return boolean
	 */
	private static function _create_files($type, $identified)
	{
		/* Создание папки для модуля */
		Reg::file_app()->mkdir($type . "/" . $identified);
		
//		/* Список файлов и папок */
//		$files_str = 
//<<<FILES
//admin
//	act
//	html
//	js
//ajax
//	html
//	act
//class
//exe
//	act
//	html
//	js
//inc
//	act
//	html
//	js
//sevice
//	sitemap.php
//	tag.php
//	url_check.php
//	html_set.php
//sql
//FILES;
//
//		$files = explode("\n", $files_str);
//		$path_ar = array();
//		foreach ($files as $name)
//		{
//			/* Уровень */
//			$level = 0;
//			for($i=0; $i<strlen($name); $i++)
//			{
//				if($name[$i] == "\t")
//				{
//					$level++;
//				}
//			}
//
//			/* Полный путь к файлу */
//			$path_ar = array_slice($path_ar, 0, $level);
//			$path_ar[$level] = str_replace("\t", "", $name);
//			$path = implode("/", $path_ar);
//
//			/* Создание */
//			if(strpos($path, ".") === false)
//			{
//				Reg::file_app()->mkdir($type . "/" . $identified . "/" . $path);
//			}
//			else
//			{
//				Reg::file_app()->put($type . "/" . $identified . "/" . $path, "");
//			}
//		}
		
		return true;
	}
}
?>