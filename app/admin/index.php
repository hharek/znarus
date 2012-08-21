<?php
/***---------------------Загрузка рисунков и всего остального--------------***/
if(is_file(Reg::path_app().Reg::url_path()))
{
	switch (mb_substr(Reg::path_app().Reg::url_path(), -3, 3, "UTF-8"))
	{
		case "jpg":
		{header("Content-Type: image/jpeg");}
		break;
	
		case "png":
		{header("Content-Type: image/png");}
		break;
	
		case "gif":
		{header("Content-Type: image/gif");}
		break;
	
		case "css":
		{header("Content-Type: text/css");}
		break;
	
		case ".js":
		{header("Content-Type: application/x-javascript");}
		break;
	
		default :
		{exit();}
		break;
	}
	
	header("Content-Length: ".filesize(Reg::path_app().Reg::url_path()));
	readfile(Reg::path_app().Reg::url_path());
	
	exit();
}

/***-------------------------------Шаблон-----------------------------***/
require (Reg::path_app()."/admin/tpl/index.phtml");
?>
