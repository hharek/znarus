	/**
	 * Выборка строки {entity_name}
	 * 
	 * @param int ${id_identified}
	 * @return bool
	 */
	public static function select_line_by_id(${id_identified})
	{
		self::is_id(${id_identified});
		
		$query = 
<<<SQL
SELECT {select_stolb}
FROM "{table}"
WHERE "{id_stolb}" = $1
SQL;
		${table} = Reg::db()->query_line($query, ${id_identified}, "{table}");
		
		return ${table};
	}