<?php
/* Общее */
$module = ZN_Module::select_line_by_id($_GET['id']);

/* Структура */
$phpclass = ZN_Phpclass::select_list_by_module_id($module['ID']);
$param = ZN_Param::select_list_by_module_id($module['ID']);
$admin = ZN_Admin::select_list_by_module_id($module['ID']);
$exe = ZN_Exe::select_list_by_module_id($module['ID']);
$inc = ZN_Inc::select_list_by_module_id($module['ID']);
$proc_start = ZN_Proc::select_list_by_module_id($module['ID'], "start");
$proc_end = ZN_Proc::select_list_by_module_id($module['ID'], "end");
$text = ZN_Text::select_list_by_module_id($module['ID']);

/* Путь и заголовок */
title("Редактировать модуль «{$module['Identified']} ({$module['Name']})»");
path
([
	"Модули [#module/list]",
	"{$module['Name']} [#module/edit?id={$_GET['id']}]"
]);
?>