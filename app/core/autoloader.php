<?php
/**
 * Генератор файла автозагрузки
 */
class _Autoloader
{
	/**
	 * Создать файл mod/_autoloader.php
	 */
	public static function create()
	{
		$php_code = "<?php\n";

		/* Классы */
		$class = self::mod_class();
		$php_code_class = "";
		foreach ($class as $val)
		{
			$php_code_class .=
<<<PHP
		case "{$val['class']}":
		{
			require_once DIR_APP . "{$val['file']}";
		}
		break;\n\n
PHP;
		}

		$php_code .=
<<<PHP
/**
 * Автозагрузка классов модулей
 * 
 * @param string \$class
 */
function autoloader_mod(\$class)
{
	switch (\$class)
	{
{$php_code_class}
	}
}

spl_autoload_register("autoloader_mod");
PHP;

		/* Другие файлы */
		$other = self::mod_other();
		if (!empty($other))
		{
			$php_code .=
<<<PHP
\n\n/* Другие файлы */\n
PHP;

			foreach ($other as $val)
			{
				$php_code .=
<<<PHP
require_once DIR_APP . "{$val['file']}";
PHP;
			}
		}

		/* Создать файл */
		$php_code .= "\n?>";
		G::file_app()->put("mod/_autoloader.php", $php_code);
	}

	/**
	 * Искать классы в модулях
	 */
	public static function mod_class()
	{
		$mod = G::file_app()->ls("mod", "dir");
		$class = [];

		foreach ($mod as $m_val)
		{
			if (G::file_app()->is_dir("mod/{$m_val['name']}/bin"))
			{
				$file = G::file_app()->ls("mod/{$m_val['name']}/bin");
				foreach ($file as $b_val)
				{
					$parse = _Parser_PHP::parse(DIR_APP . "/mod/{$m_val['name']}/bin/{$b_val['name']}");
					$parse['file'] = "/mod/{$m_val['name']}/bin/{$b_val['name']}";

					if ($parse['type'] === "class")
					{
						$class[] = $parse;
					}
				}
			}
		}

		return $class;
	}

	/**
	 * Искать файлы с функциями и другие файлы
	 */
	public static function mod_other()
	{
		$mod = G::file_app()->ls("mod", "dir");
		$other = [];

		foreach ($mod as $m_val)
		{
			if (G::file_app()->is_dir("mod/{$m_val['name']}/bin"))
			{
				$file = G::file_app()->ls("mod/{$m_val['name']}/bin");
				foreach ($file as $b_val)
				{
					$parse = _Parser_PHP::parse(DIR_APP . "/mod/{$m_val['name']}/bin/{$b_val['name']}");
					$parse['file'] = "/mod/{$m_val['name']}/bin/{$b_val['name']}";

					if ($parse['type'] === "functions" or $parse['type'] === "other")
					{
						$other[] = $parse;
					}
				}
			}
		}

		return $other;
	}
}
?>