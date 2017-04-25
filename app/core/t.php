<?php
/**
 * Выборка и изменение текстов
 */
class T
{
	/**
	 * Получить текст
	 * 
	 * @return mixed
	 */
	public static function get()
	{
		/* Аргументы */
		if (func_num_args() === 1)
		{
			$module_identified = "sys";
			$text_identified = func_get_arg(0);
		}
		elseif (func_num_args() === 2)
		{
			$module_identified = func_get_arg(0);
			$text_identified = func_get_arg(1);
		}
		else
		{
			throw new Exception("Необходимо указать один или два аргумента");
		}

		/* Значение  */
		$text = self::_get_by_identified($module_identified, $text_identified);

		return $text['Value'];
	}

	/**
	 * Назначить новый текст
	 */
	public static function set()
	{
		/* Аргументы */
		if (func_num_args() === 2)
		{
			$module_identified = "sys";
			$text_identified = func_get_arg(0);
			$value = func_get_arg(1);
		}
		elseif (func_num_args() === 3)
		{
			$module_identified = func_get_arg(0);
			$text_identified = func_get_arg(1);
			$value = func_get_arg(2);
		}
		else
		{
			throw new Exception("Необходимо указать два или три аргумента");
		}

		/* Проверка */
		$text = self::_get_by_identified($module_identified, $text_identified);

		/* SQL */
		$data = 
		[
			"Value" => $value
		];
		G::db_core()->update("text", $data, ["ID" => $text['ID']]);
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("text");
	}

	/**
	 * Получить данные по тексту
	 * 
	 * @param string $module_identified
	 * @param string $text_identified
	 * @return array
	 */
	private static function _get_by_identified($module_identified, $text_identified)
	{
		/* Проверка */
		if (!Type::check("identified", $module_identified))
		{
			throw new Exception("Идентификатор у модуля задан неверно. " . Type::get_last_error());
		}

		if (!Type::check("identified", $text_identified))
		{
			throw new Exception("Идентификатор у текста задан неверно. " . Type::get_last_error());
		}

		/* Текст */
		$text = G::cache_db_core()->get("text_get_" . $module_identified . "_" . $text_identified);
		if ($text === null)
		{
			$text = G::db_core()->text_get($module_identified, $text_identified)->row();
			if (empty($text))
			{
				throw new Exception("Параметра «{$module_identified}» - «{$text_identified}» не существует.");
			}
			G::cache_db_core()->set("text_get_" . $module_identified . "_" . $text_identified, $text, "text");
		}

		return $text;
	}
}
?>