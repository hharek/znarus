<?php
if(Reg::url_path() === "/обратная-связь")
{
	return "form";
}

if(Reg::url_path() === "/обратная-связь/сообщение-отправлено")
{
	return "mess_ok";
}
?>