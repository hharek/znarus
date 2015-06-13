<?php
/**
 * Автозагрузка классов модулей
 * 
 * @param string $class
 */
function autoloader_mod($class)
{
	switch ($class)
	{
		case "Articles":
		{
			require_once DIR_APP . "/mod/articles/bin/articles.php";
		}
		break;

		case "Faq":
		{
			require_once DIR_APP . "/mod/faq/bin/faq.php";
		}
		break;

		case "Menu":
		{
			require_once DIR_APP . "/mod/menu/bin/menu.php";
		}
		break;

		case "Menu_Item":
		{
			require_once DIR_APP . "/mod/menu/bin/menu_item.php";
		}
		break;

		case "News":
		{
			require_once DIR_APP . "/mod/news/bin/news.php";
		}
		break;

		case "Page":
		{
			require_once DIR_APP . "/mod/page/bin/page.php";
		}
		break;


	}
}

spl_autoload_register("autoloader_mod");
?>