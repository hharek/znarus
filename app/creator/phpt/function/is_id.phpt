	/**
	 * Проверка на существование
	 * 
	 * @param int ${id_identified}
	 * @return bool
	 */
	public static function is_id(${id_identified})
	{
		if(!Chf::uint(${id_identified}))
		{throw new Exception("Номер у \"{entity_name}\" задан неверно. ".Chf::error());}
		
		$query = 
<<<SQL
SELECT COUNT(*) as count
FROM "{table}"
WHERE "ID" = $1
SQL;
		$count = Reg::db()->query_one($query, ${id_identified}, "{table}");
		if($count < 1)
		{throw new Exception("Номера \"{${id_identified}}\" у \"{entity_name}\" не существует.");}
		
		return true;
	}