if(empty(${identified}))
{Err::add("Не указан файл в поле \"{name}\". ", "{identified}");}
else
{
	if(!is_file(${identified}))
	{Err::add("Файла указанного в поле \"{name}\" не существует. ", "{identified}");}
}