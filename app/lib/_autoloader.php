<?php
/**
 * Автозагрузка классов библиотек
 * 
 * @param string $class
 */
function autoloader_lib($class)
{
	switch ($class)
	{
		/* Less.php Compiler */
		case "Less_Parser":
		{
			require __DIR__ . "/less_parser/Less.php";
		}
		break;
		
		/* lessphp */
		case "lessc":
		{
			require __DIR__ . "/lessphp/lessc.inc.php";
		}
		break;
		
		/* Sphinx */
		case "SphinxClient":
		{
			require __DIR__ . "/sphinxapi/sphinxapi.php";
		}
		break;
		
		/* simple_html_dom */
		case "simple_html_dom":
		{
			require __DIR__ . "/simple_html_dom/simple_html_dom.php";
		}
		break;
	}
}

spl_autoload_register("autoloader_lib");


/* PHPMailer */
require __DIR__ . "/phpmailer/PHPMailerAutoload.php";
		
?>