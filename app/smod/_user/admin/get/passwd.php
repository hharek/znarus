<?php
/* Если root */
if(G::user()['Group_Name'] === "root")
{
	throw new Exception("Чтобы сменить пароль у «" . ROOT_NAME_FULL . "», воспользуйтесь конфигурационным файлом.");
}

/* Заголовок и путь */
title("Сменить пароль");
path(["Сменить пароль"]);
?>