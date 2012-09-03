$query = 
<<<SQL
SELECT "{ref_id_identified}"
FROM "{ref_table}"
WHERE "{ref_foreign_identified}" = $1
SQL;
		${ref_table} = Reg::db()->query_column($query, ${id_identified}, "{ref_table}");
		if(!empty (${ref_table}))
		{
			foreach (${ref_table} as $val)
			{{ref_class}::delete($val);}
		}