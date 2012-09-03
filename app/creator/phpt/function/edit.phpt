	/**
	 * Редактировать
	 * {param}
	 * @return boolean 
	 */
	public static function edit({arg})
	{
		{check_field}
		
		{unique}
		
		{query_update}
		
		return true;
	}