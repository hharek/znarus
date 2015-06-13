<?php
/**
 * Парсер пхп-файла
 */
class _Parser_PHP
{
	/**
	 * Распарсить файл
	 * 
	 * @param string $file
	 */
	public static function parse($file)
	{
		/* Данные */
		$data = [];
		$data['file'] = basename($file);
		
		/* Разбираем на токены */
		$token_all = token_get_all(file_get_contents($file));
		
		/* Если является классом */
		foreach ($token_all as $key => $val)
		{
			if
			(
				$token_all[$key][0] === T_DOC_COMMENT and 
				$token_all[$key + 1][0] === T_WHITESPACE and
				$token_all[$key + 2][0] === T_CLASS and
				$token_all[$key + 3][0] === T_WHITESPACE and
				$token_all[$key + 4][0] === T_STRING
			)
			{
				$data['type'] = "class";
				$data['class'] = $token_all[$key + 4][1];
				$data['name'] = self::comment_get_name($token_all[$key][1]);
				
				return $data;
			}
		}
		
		/* Если является файлом с функциями */
		foreach ($token_all as $key => $val)
		{
			if
			(
				$token_all[$key][0] === T_DOC_COMMENT and 
				$token_all[$key + 1][0] === T_WHITESPACE and
				$token_all[$key + 2][0] === T_FUNCTION and
				$token_all[$key + 3][0] === T_WHITESPACE and
				$token_all[$key + 4][0] === T_STRING
			)
			{
				$data['type'] = "functions";
				break;
			}
		}
		
		if(isset($data['type']) and $data['type'] === "functions")
		{
			foreach ($token_all as $key => $val)
			{
				if($token_all[$key][0] === T_DOC_COMMENT)
				{
					$data['name'] = self::comment_get_name($token_all[$key][1]);
					return $data;
				}
			}
		}
		
		/* Другой файл php */
		$data['type'] = "other";
		$data['name'] = "";
		foreach ($token_all as $key => $val)
		{
			if($token_all[$key][0] === T_DOC_COMMENT)
			{
				$data['name'] = self::comment_get_name($token_all[$key][1]);
				return $data;
			}
		}
		
		return $data;
	}
	
	/**
	 * Получить наименование из комментария
	 * 
	 * @param string $comment
	 */
	public static function comment_get_name($comment)
	{
		/* Удаляем ненужные символы */
		$comment = str_replace(["/**", "*/"], "", $comment);
		$comment = trim($comment);
		
		/* Разбираем по строкам */
		$ar = explode("\n", $comment);
		foreach ($ar as $val)
		{
			$name = trim($val);
			if(substr($name, 0, 1) === "*")
			{
				$name = trim(substr($name, 1));
			}
			
			if(!empty($name))
			{
				return $name;
			}
		}
		
		return "";
	}
}
?>