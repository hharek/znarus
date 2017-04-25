<?php
if (in_array(_Front::$_page_type, ["home", "404", "403"]))
{
	$html_identified = P::get("_page", _Front::$_page_type . "_html_identified");
	if (!empty($html_identified) and _Html::exist($html_identified) === true)
	{
		return $html_identified;
	}
}
elseif (_Front::$_page_type === "module")
{
	$page = _Page::get(G::page_id());
	if ($page['Html_ID'] !== null)
	{
		$html = _Html::get($page['Html_ID']);
		return $html['Identified'];
	}
}
?>