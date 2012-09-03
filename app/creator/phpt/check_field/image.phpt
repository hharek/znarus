if(empty(${identified}))
{Err::add("Не указан файл в поле \"{name}\". ", "{identified}");}
elseif(!empty(${identified}) and !is_file(${identified}))
{Err::add("Файла указанного в поле \"{name}\" не существует. ", "{identified}");}
else
{
	$settings = @getimagesize($file);
	if(empty($settings))
	{Err::add("Файл указанный в поле \"{name}\" не является рисунком. ", "{identified}");}
}