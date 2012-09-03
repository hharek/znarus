if(mb_strlen(${identified}, "UTF-8") > 0)
{
	if(!in_array(${identified}, array({enum})))
	{Err::add("Поле \"{name}\" задано неверно. ".Chf::error(), "{identified}");}
}
else
{${identified} = null;}