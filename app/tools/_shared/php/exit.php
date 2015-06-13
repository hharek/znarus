<?php
/* Проверка сессии */
if (G::session_check() === false)
{
	header("HTTP/1.0 401 Unauthorized");
	throw new Exception("Для доступа к странице требуется аутентификация.");
}

_User_Action::logout(G::location());

if (G::location() === "constr")
{
	header("Location: /" . URL_CONSTR . "/");
}
elseif (G::location() === "admin")
{
	header("Location: /" . URL_ADMIN . "/");
}
exit();
?>