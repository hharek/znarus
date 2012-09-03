if(!empty(${identified}) and !is_file(${identified}))
{Err::add("Файла указанного в поле \"{name}\" не существует. ", "{identified}");}
else
{${identified} = null;}