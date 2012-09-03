$query = 
<<<SQL
INSERT INTO "{table}"({insert_into_stolb})
VALUES ({insert_values})
SQL;
		Reg::db()->query($query, array({array_identified}), "{table}", true);