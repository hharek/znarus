if(mb_strlen(${identified}, "UTF-8") > 0 and !Chf::{type}(${identified}))
{Err::add("Поле \"{name}\" задано неверно. ".Chf::error(), "{identified}");}
else
{${identified} = null;}