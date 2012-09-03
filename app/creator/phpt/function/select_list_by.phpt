	/**
	 * Выборка списка по {ref_entity_name}
	 * 
	 * @param int ${foreign_identified}
	 * @return array
	 */
	public static function select_list_by_{foreign_identified}(${foreign_identified})
	{
		{ref_class}::is_id(${foreign_identified});
		
		$query = 
<<<SQL
SELECT {select_stolb}
FROM "{table}"
WHERE "{foreign_stolb}" = $1{order}
SQL;
		${table} = Reg::db_creator()->query_assoc($query, ${foreign_identified}, "{table}");
		
		return ${table};
	}