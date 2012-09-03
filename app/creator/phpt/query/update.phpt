$query = 
<<<SQL
UPDATE "{table}"
SET {update_stolb}
WHERE "{id_stolb}" = {id_nomer}
SQL;
		Reg::db()->query($query, array({array_identified}), "{table}", true);