<?php
/* Меню */
$menu = Menu::select_list();

/* Текущее меню */
$menu_id = 0;
if(!empty($_GET['menu_id']))
{$menu_id = (int)$_GET['menu_id'];}

/* Пункты меню */
$item = array();
if(!empty($menu_id))
{
	$item = Menu_Item::select_list_child_by_parent($menu_id, 0);
}
?>