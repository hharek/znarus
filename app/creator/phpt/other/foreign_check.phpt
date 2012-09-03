$query = 
<<<SQL
SELECT COUNT(*) as count
FROM "{ref_table}"
WHERE "{ref_foreign_identified}" = $1
SQL;
		$count = Reg::db()->query_one($query, $id, "{ref_table}");
		if($count > 0)
		{throw new Exception("Невозможно удалить т.к. есть \"{ref_entity_name}\" ссылающий на него.");}