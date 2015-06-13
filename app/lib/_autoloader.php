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
		/* PHPMailer */
		case "PHPMailer" :
		{
			require __DIR__ . "/phpmailer/class.phpmailer.php";
		}
		
		case "POP3" :
		{
			require __DIR__ . "/phpmailer/class.pop3.php";
		}
		
		case "SMTP" :
		{
			require __DIR__ . "/phpmailer/class.smtp.php";
		}
		
		/* lessphp */
		case "lessc":
		{
			require __DIR__ . "/lessphp/lessc.inc.php";
		}
		
		/* Sphinx */
		case "SphinxClient":
		{
			require __DIR__ . "/sphinxapi/sphinxapi.php";
		}
	}
}

spl_autoload_register("autoloader_lib");
?>