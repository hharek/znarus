<?php
/* Общее */
$module = _Module::get($_GET['id']);

/* Bin файлы */
$bin = [];
if(G::file_app()->is_dir("mod/{$module['Identified']}/bin"))
{
	$bin_files = G::file_app()->ls("mod/{$module['Identified']}/bin", "file", "php");
	foreach ($bin_files as $val)
	{
		$bin[] = _Parser_PHP::parse(DIR_APP . "/mod/{$module['Identified']}/bin/{$val['name']}");
	}
}

/* Структура */
$param = _Param::get_by_module($module['ID']);
$admin = _Admin::get_by_module($module['ID']);
$exe = _Exe::get_by_module($module['ID']);
$inc = _Inc::get_by_module($module['ID']);
$ajax = _Ajax::get_by_module($module['ID']);
$proc = _Proc::get_by_module($module['ID']);
$text = _Text::get_by_module($module['ID']);

/* Путь и заголовок */
title("Редактировать модуль «{$module['Identified']} ({$module['Name']})»");
path
([
	"Модули [#module/list]",
	"{$module['Name']}"
]);
?>