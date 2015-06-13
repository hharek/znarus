<?php
/* Доступные модули с админками */
$module = _User_Group_Priv::get_admin_visible_and_allow(G::user()['Group_ID']);

/* Заголовок и путь */
title("Модули");
path
([
	"Модули"
]);
?>