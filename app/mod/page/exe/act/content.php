<?php
$page = Page::select_line_by_id(Reg::page_id());

Reg::title($page['Name']);
Reg::meta_title($page['Name']);
echo $page['Content'];
?>