<?php
/**
 * Рисунки слайдера
 */
class Slider_A
{
	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @param string $url
	 * @param string $file
	 * @return array
	 */
	public static function add($name, $url, $file)
	{
		/* Проверка */
		Err::check_field($name, "string", false, "Name", "Наименование");
		Err::check_field($url, "url", false, "Url", "Адрес");
		Err::exception();
		
		/* Рисунок */
		if(empty($file))
		{
			Err::add("Рисунок не задан", "File");
			Err::exception();
		}
		
		require_once Reg::path_app() . "/lib/zn_image/zn_image.php";
		try
		{
			ZN_Image::check($file, P::get("slider_a", "width"), P::get("slider_a", "height"));
			$file_name = self::get_file_name() . "." . ZN_Image::get_settings($file)['type'];
			Reg::file()->upload($file, P::get("slider_a", "path") . "/" . $file_name);
		}
		catch (Exception $e)
		{
			Err::add($e->getMessage(), "File");
			Err::exception();
		}
		
		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Url" => $url,
			"File" => $file_name
		];
		$id = Reg::db()->insert("slider_a", $data, "ID");
		
		/* Данные добавленного */
		return self::select_line_by_id($id);
	}
	
	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $name
	 * @param string $url
	 * @param string $file
	 * @return array
	 */
	public static function edit($id, $name, $url, $file)
	{
		/* Проверка */
		self::is_id($id);
		Err::check_field($name, "string", false, "Name", "Наименование");
		Err::check_field($url, "url", false, "Url", "Адрес");
		Err::exception();
		
		/* Рисунок */
		if(!empty($file))
		{
			require_once Reg::path_app() . "/lib/zn_image/zn_image.php";
			try
			{
				ZN_Image::check($file, P::get("slider_a", "width"), P::get("slider_a", "height"));
				$file_name =  self::get_file_name() . "." . ZN_Image::get_settings($file)['type'];
				
				/* Удаление старого и добаление нового */
				$slider_a = self::select_line_by_id($id);
				Reg::file()->rm(P::get("slider_a", "path") . "/" . $slider_a['File']);
				
				Reg::file()->upload($file, P::get("slider_a", "path") . "/" . $file_name);
			}
			catch (Exception $e)
			{
				Err::add($e->getMessage(), "File");
				Err::exception();
			}
		}
		
		/* SQL */
		$data =
		[
			"Name" => $name,
			"Url" => $url
		];
		
		if(!empty($file))
		{
			$data["File"] = $file_name;
		}
		
		Reg::db()->update("slider_a", $data, array("ID" => $id));
		
		/* Данные отредактируемого */
		return self::select_line_by_id($id);
	}
	
	/**
	 * Удалить
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function delete($id)
	{
		$slider_a = self::select_line_by_id($id);
		
		Reg::file()->rm(P::get("slider_a", "path") . "/" . $slider_a['File']);
		
		Reg::db()->delete("slider_a", array("ID" => $id));
		
		return $slider_a;
	}
	
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 */
	public static function is_id($id)
	{
		if(!Chf::uint($id))
		{throw new Exception_Admin("Номер у «рисунка сладера» задан неверно. ".Chf::error());}

$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"slider_a"
WHERE 
	"ID" = $1
SQL;
		$count = Reg::db()->query_one($query, $id, "slider_a");
		if($count < 1)
		{throw new Exception_Admin("Рисунка слайдер с номером «{$id}» не существует.");}
	}
	
	/**
	 * Задать сортировку
	 * 
	 * @param int $id
	 * @param int $sort (up|down|::int)
	 */
	public static function sort($id, $sort)
	{
		self::is_id($id);
		
		if(!in_array($sort, array('up','down')))
		{
			$sort = (int)$sort;
			
			$data =
			[
				"Sort" => $sort
			];
			Reg::db()->update("slider_a", $data, array("ID" => $id));
		}
		else 
		{
			$query =
<<<SQL
SELECT 
	"ID", 
	"Sort"
FROM 
	"slider_a"
ORDER BY 
	"Sort" ASC
SQL;
			$other = Reg::db()->query_assoc($query, null, "slider_a");
			
			if(count($other) < 2)
			{
				throw new Exception_Admin("Необходимо хотя бы два пункта меню.");
			}

			foreach ($other as $key=>$val)
			{
				if($val['ID'] == $id)
				{break;}
			}

			if($sort == "up")
			{
				if($key == 0)
				{throw new Exception_Admin("Выше некуда.");}
				
				$id_next = $other[$key-1]['ID'];
				$sort_int = $other[$key-1]['Sort'];
				$sort_int_next = $other[$key]['Sort'];
			}
			elseif($sort == "down")
			{
				if($key == count($other)-1)
				{throw new Exception_Admin("Ниже некуда.");}
		
				$id_next = $other[$key+1]['ID'];
				$sort_int = $other[$key+1]['Sort'];
				$sort_int_next = $other[$key]['Sort'];
			}

			$data =
			[
				"Sort" => $sort_int
			];
			Reg::db()->update("slider_a", $data, array("ID" => $id));
		
			$data =
			[
				"Sort" => $sort_int_next
			];
			Reg::db()->update("slider_a", $data, array("ID" => $id_next));
		}
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
	"Url",
	"File",
	"Sort"
FROM 
	"slider_a"
WHERE
	"ID" = $1
SQL;
		$slider_a = Reg::db()->query_line($query, $id, "slider_a");
		
		return $slider_a;
	}
	
	/**
	 * Выборка всех
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
	"Url",
	"File",
	"Sort"
FROM 
	"slider_a"
ORDER BY
	"Sort" ASC 
SQL;
		$slider_a = Reg::db()->query_assoc($query, null, "slider_a");
		
		return $slider_a;
	}
	
	/**
	 * Получить случайное имя для файла
	 * 
	 * @return string
	 */
	public static function get_file_name()
	{
		$file_name = md5(microtime());
		$file_name = substr($file_name, 0, 6);
		return $file_name;
	}
}
?>