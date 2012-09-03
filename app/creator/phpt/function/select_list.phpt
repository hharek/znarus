	/**
	 * Выборка списка {entity_name}
	 * 
	 * @return array
	 */
	public static function select_list()
	{
		$query = 
<<<SQL
SELECT {select_stolb}
FROM "{table}"{order}
SQL;
		${table} = Reg::db_creator()->query_assoc($query, null, "{table}");
		
		return ${table};
	}