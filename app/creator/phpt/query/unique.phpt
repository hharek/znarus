		$query = 
<<<SQL
SELECT COUNT(*) as count
FROM "{table}"
{stolb}
SQL;
		if(!is_null(${id_identified}))
		{$query .= "AND \"{id_stolb}\" != '{${id_identified}}'";}
		$count = Reg::db()->query_one($query, array({array_stolb}), "{table}");
		if($count > 0)
		{Err::add("{entity_name} с полем \"{field_first_name}\" : \"{str_param}\" уже существует.", {field_first});}