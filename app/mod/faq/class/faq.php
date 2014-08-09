<?php
/**
 * Вопросы и ответы
 */
class Faq
{
	/**
	 * Добавить
	 * 
	 * @param string $question
	 * @param string $answer
	 * @return array
	 */
	public static function add($question, $answer)
	{
		/* Проверка */
		Err::check_field($question, "text", false, "Question", "Вопрос");
		Err::check_field($answer, "text", true, "Answer", "Ответ");
		Err::exception();
		
		/* SQL */
		$data = 
		[
			"Question" => $question,
			"Answer" => $answer
		];
		$id = Reg::db()->insert("faq", $data, "ID");
		
		/* Дата последнего изменения */
		P::set("faq", "last_modified", date("Y-m-d H:i:s"));
		
		/* Данные добавленного */
		return self::select_line_by_id($id);
	}
	
	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $question
	 * @param string $answer
	 * @return array
	 */
	public static function edit($id, $question, $answer)
	{
		/* Проверка  */
		self::is_id($id);
		Err::check_field($question, "text", false, "Question", "Вопрос");
		Err::check_field($answer, "text", true, "Answer", "Ответ");
		Err::exception();
		
		/* SQL */
		$data =
		[
			"Question" => $question,
			"Answer" => $answer
		];
		Reg::db()->update("faq", $data, array("ID" => $id));
		
		/* Дата последнего изменения */
		P::set("faq", "last_modified", date("Y-m-d H:i:s"));
		
		/* Данные редактируемого */
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
		$faq = self::select_line_by_id($id);
		
		Reg::db()->delete("faq", array("ID" => $id));
		
		/* Дата последнего изменения */
		P::set("faq", "last_modified", date("Y-m-d H:i:s"));
		
		return $faq;
	}
	
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 */
	public static function is_id($id)
	{
		if(!Chf::uint($id))
		{throw new Exception_Admin("Номер у «Вопроса с ответом» задан неверно. ".Chf::error());}

$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"faq"
WHERE 
	"ID" = $1
SQL;
		$count = Reg::db()->query_one($query, $id, "faq");
		if($count < 1)
		{throw new Exception_Admin("Вопроса с ответом с номером «{$id}» не существует.");}
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
			Reg::db()->update("faq", $data, array("ID" => $id));
		}
		else 
		{
			$query =
<<<SQL
SELECT 
	"ID", 
	"Sort"
FROM 
	"faq"
ORDER BY 
	"Sort" ASC
SQL;
			$other = Reg::db()->query_assoc($query, null, "faq");
			
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
			Reg::db()->update("faq", $data, array("ID" => $id));
		
			$data =
			[
				"Sort" => $sort_int_next
			];
			Reg::db()->update("faq", $data, array("ID" => $id_next));
		}
		
		/* Дата последнего изменения */
		P::set("faq", "last_modified", date("Y-m-d H:i:s"));
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
	"Question",
	"Answer",
	"Sort"
FROM 
	"faq"
WHERE
	"ID" = $1
SQL;
		$faq = Reg::db()->query_line($query, $id, "faq");
		
		return $faq;
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
	"Question",
	"Answer",
	"Sort"
FROM 
	"faq"
ORDER BY
	"Sort" ASC
SQL;
		$faq = Reg::db()->query_assoc($query, null, "faq");
		
		return $faq;
	}
}
?>