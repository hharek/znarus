<?php
/* Все страницы */
$query =
<<<SQL
SELECT 
	"ID", 
	"Name", 
	"Url",
	COALESCE("Parent", 0) as "Parent"
FROM 
	"page"
ORDER BY 
	"Name" ASC
SQL;
$page = Reg::db()->query_assoc($query, null, "page");

/**
 * Получить список страниц для sitemap
 * 
 * @param array $page
 * @param string $url
 * @param int $parent
 */
function page_sitemap_xml(&$page, $url, $parent)
{
	$data = [];
	foreach ($page as $key=>$val)
	{
		if((int)$val['Parent'] === (int)$parent)
		{
			$data[] = 
			[
				"url" => $url . "/" . $val['Url']
			];
			
			$child = page_sitemap_xml($page, $url . "/" . $val['Url'], $val['ID']);
			if(!empty($child))
			{
				$data = array_merge($data, $child);
			}
			
			unset($page[$key]);
		}
	}
	
	return $data;
}

/* Данные для карты сайта */
return page_sitemap_xml($page, "", 0);
?>