<?php
/* Является ли адресом для продвижения */
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
?>