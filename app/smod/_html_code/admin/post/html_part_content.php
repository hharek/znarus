<?php
/* Проверка */
$identified = $_POST['Identified'];
if (!Type::check("identified", $identified))
{
	throw new Exception("Идентификатор куска html-а задан неверно.");
}

/* Сохранить */
G::file_app()->put("html/part/" . $identified . ".html", $_POST['Content']);
_Cache_Front::delete(["html_part" => $identified]);

mess_ok("Кусок html-а «{$identified}» сохранён.»");
?>