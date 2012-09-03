	/**
	 * Удалить
	 * 
	 * @param int ${id_identified}
	 * @return boolean 
	 */
	public static function delete(${id_identified})
	{
		self::is_id(${id_identified});
{foreign_delete}
		/* Запрос */
		$query = 
<<<SQL
DELETE
FROM "{table}"
WHERE "{id_stolb}" = $1
SQL;
		Reg::db()->query($query, ${id_identified}, "{table}", true);
		
		return true;
	}