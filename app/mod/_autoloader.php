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


	}
}

spl_autoload_register("autoloader_mod");
?>