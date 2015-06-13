<?php
/**
 * Вопросы и ответы
 */
class Faq
{
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 */
	public static function is($id)
	{
		if (!Chf::uint($id))
		{
			throw new Exception("Номер у «Вопроса с ответом» задан неверно. " . Chf::error());
		}

		$query = 
<<<SQL
SELECT 
	true
FROM 
	"faq"
WHERE 
	"ID" = $1
SQL;
		$rec = G::db()->query($query, $id)->single();
		if ($rec === null)
		{
			throw new Exception("Вопроса с ответом с номером «{$id}» не существует.");
		}
	}
	
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
		self::_check($question, $answer);

		/* SQL */
		$data = 
		[
			"Question" => $question,
			"Answer" => $answer
		];
		$id = G::db()->insert("faq", $data, "ID");

		/* Дата последнего изменения */
		P::set("faq", "last_modified", date("Y-m-d H:i:s"));
		
		/* Удалить кэш */
		G::cache_db()->delete_tag("faq");
		_Cache_Front::delete(["module" => "faq"]);

		/* Данные добавленного */
		return self::get($id);
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
		self::is($id);
		self::_check($question, $answer);

		/* SQL */
		$data = 
		[
			"Question" => $question,
			"Answer" => $answer
		];
		G::db()->update("faq", $data, ["ID" => $id]);

		/* Дата последнего изменения */
		P::set("faq", "last_modified", date("Y-m-d H:i:s"));
		
		/* Удалить кэш */
		G::cache_db()->delete_tag("faq");
		_Cache_Front::delete(["module" => "faq"]);

		/* Данные редактируемого */
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
		$old = self::get($id);

		G::db()->delete("faq", ["ID" => $id]);

		/* Дата последнего изменения */
		P::set("faq", "last_modified", date("Y-m-d H:i:s"));
		
		/* Удалить кэш */
		G::cache_db()->delete_tag("faq");
		_Cache_Front::delete(["module" => "faq"]);

		return $old;
	}

	/**
	 * Задать сортировку
	 * 
	 * @param int $id
	 * @param int $sort (up|down|::int)
	 */
	public static function sort($id, $sort)
	{
		self::is($id);

		if (!in_array($sort, ['up', 'down']))
		{
			$sort = (int) $sort;

			$data = 
			[
				"Sort" => $sort
			];
			G::db()->update("faq", $data, ["ID" => $id]);
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
			$other = G::db()->query($query)->assoc();

			if (count($other) < 2)
			{
				throw new Exception("Необходимо хотя бы два пункта меню.");
			}

			foreach ($other as $key => $val)
			{
				if ($val['ID'] == $id)
				{
					break;
				}
			}

			if ($sort === "up")
			{
				if ($key == 0)
				{
					throw new Exception("Выше некуда.");
				}

				$id_next = $other[$key - 1]['ID'];
				$sort_int = $other[$key - 1]['Sort'];
				$sort_int_next = $other[$key]['Sort'];
			}
			elseif ($sort === "down")
			{
				if ($key === count($other) - 1)
				{
					throw new Exception("Ниже некуда.");
				}

				$id_next = $other[$key + 1]['ID'];
				$sort_int = $other[$key + 1]['Sort'];
				$sort_int_next = $other[$key]['Sort'];
			}

			$data = 
			[
				"Sort" => $sort_int
			];
			G::db()->update("faq", $data, ["ID" => $id]);

			$data = 
			[
				"Sort" => $sort_int_next
			];
			G::db()->update("faq", $data, ["ID" => $id_next]);
		}

		/* Дата последнего изменения */
		P::set("faq", "last_modified", date("Y-m-d H:i:s"));
		
		/* Удалить кэш */
		G::cache_db()->delete_tag("faq");
		_Cache_Front::delete(["module" => "faq"]);
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
	"Question",
	"Answer",
	"Sort"
FROM
	"faq"
WHERE
	"ID" = $1
SQL;
		return G::db()->query($query, $id)->row();
	}

	/**
	 * Выборка всех
	 * 
	 * @return array
	 */
	public static function get_all()
	{
		$faq = G::cache_db()->get("faq_all");
		if ($faq === null)
		{
			$faq = G::db()->faq_all()->assoc();
			G::cache_db()->set("faq_all", $faq, "faq");
		}
		
		return $faq;
	}
	
	/**
	 * Функция Page Info
	 * 
	 * @param mixed $param
	 * @return array
	 */
	public static function page_info($param)
	{
		if (empty($param))
		{
			$faq = Faq::get_all();
			$content = "Часто задаваемые вопросы. FAQ. ЧАВО \n";
			foreach ($faq as $val)
			{
				$content .= "\n\n". $val['Question'] . " " . $val['Answer'];
			}
			
			return
			[
				"url" => "/вопрос-ответ",
				"title" => "Вопрос-ответ",
				"content" => $content,
				"tags" => "вопрос-ответ, чаво, часто задаваемые вопросы, faq, F.A.Q., ответы на вопросы, вопросы и ответы, ФАК",
				"last_modified" => P::get("faq", "last_modified")
			];
		}
	}

	/**
	 * Проверка полей
	 * 
	 * @param string $question
	 * @param string $answer
	 */
	private static function _check($question, $answer)
	{
		Err::check_field($question, "text", false, "Question", "Вопрос");
		Err::check_field($answer, "text", true, "Answer", "Ответ");
		Err::exception();
	}
}
?>