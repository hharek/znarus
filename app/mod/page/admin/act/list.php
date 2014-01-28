<?php
$page = Page::select_list_child_by_parent(0, 0);

title("Страницы");
path
([
	"Страницы [#page/list]"
]);
?>