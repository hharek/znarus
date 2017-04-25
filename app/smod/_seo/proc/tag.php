<?php
/* Является ли адресом для продвижения */
if (!Type::check("url", G::url()))
{
	return;
}

$seo_url = _Seo_Url::get_by_url(G::url());
if (!empty($seo_url))
{
	if (!empty($seo_url['Title']))
	{
		G::meta_title($seo_url['Title']);
	}
	
	if (!empty($seo_url['Keywords']))
	{
		G::meta_keywords($seo_url['Keywords']);
	}
	
	if (!empty($seo_url['Description']))
	{
		G::meta_description($seo_url['Description']);
	}
}

/* Другие страницы */
if (_Front::$_page_type !== "module")
{
	if (!empty(P::get("_seo", _Front::$_page_type . "_title")))
	{
		G::meta_title(P::get("_seo", _Front::$_page_type . "_title"));
	}
	
	if (!empty(P::get("_seo", _Front::$_page_type . "_keywords")))
	{
		G::meta_keywords(P::get("_seo", _Front::$_page_type . "_keywords"));
	}
	
	if (!empty(T::get("_seo", _Front::$_page_type . "_description")))
	{
		G::meta_description(T::get("_seo", _Front::$_page_type . "_description"));
	}
}
?>