<?php
if (G::url_path_ar()[0] === "статьи")
{
	/* Все новости */
	if (count(G::url_path_ar()) === 1)
	{
		return "list";
	}
	/* Новость */
	elseif (count(G::url_path_ar()) === 2)
	{
		$url_all = Articles::get_url_all();
		foreach ($url_all as $url)
		{
			if ($url['Url'] === G::url_path_ar()[1])
			{
				G::articles_id($url['ID']);
				
				return "content";
			}
		}
	}
	/* 404 */
	else
	{
		return false;
	}
}
else
{
	return false;
}
?>