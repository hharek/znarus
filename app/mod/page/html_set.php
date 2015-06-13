<?php
if (in_array(_Front::$_page_type, ["home", "404", "403"]))
{
	$html_set = P::get("page", _Front::$_page_type . "_html");
	if (!empty($html_set) and _Html::exist($html_set) === true)
	{
		return $html_set;
	}
}
elseif (_Front::$_page_type === "module")
{
	$html_data = Page::get_html_data(G::page_id());
	if ($html_data !== null)
	{
		return $html_data['Identified'];
	}
}
?>