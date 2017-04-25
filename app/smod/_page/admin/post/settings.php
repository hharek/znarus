<?php
if ((int)$_POST['url_auto_length'] < 3 or (int)$_POST['url_auto_length'] > 16)
{
	throw new Exception("Длина адреса должно быть от 4 до 16 символов.");
}

if (!Type::check("url_part", $_POST['url_auto_prefix']))
{
	throw new Exception("Префикс содержит недопустимые символы.");
}

if (mb_strlen($_POST['url_auto_prefix']) > 4)
{
	throw new Exception("Префикс не должен превышать 4-ые символа.");
}

foreach ($_POST as $key => $val)
{
	P::set("_page", $key, $val);
}

mess_ok("Настройки изменены");
?>