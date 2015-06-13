<?php
/* Операционная система */
$os = array();
$os['uname'] = php_uname();
$os['file_size'] = G::file()->size(DIR_APP) + G::file()->size(DIR_WWW);
$os['file_size_mb'] = round($os['file_size'] / 1048576);
$os['server_ip'] = $_SERVER['SERVER_ADDR'];
$os['server_name'] = $_SERVER['SERVER_NAME'];
$os['server_software'] = $_SERVER['SERVER_SOFTWARE'];
$os['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

/* PostgreSQL */
$pgsql = array();

$pgsql['host'] = DB_HOST;
$pgsql['user'] = DB_USER;
$pgsql['db_name'] = DB_NAME;
$pgsql['schema_core'] = DB_SCHEMA_CORE;
$pgsql['schema_public'] = DB_SCHEMA_PUBLIC;

$pgsql['version'] = G::db_core()->query("SHOW SERVER_VERSION")->single();
$pgsql['size'] = G::db_core()->query("SELECT pg_database_size('" . DB_NAME . "')")->single();
$pgsql['size_mb'] = round($pgsql['size'] / 1048576);
$pgsql['server_encoding'] = G::db_core()->query("SHOW SERVER_ENCODING")->single();
$pgsql['client_encoding'] = G::db_core()->query("SHOW CLIENT_ENCODING")->single();

/* Веб-сервер */
$web_server = array();
$web_server['name'] = $_SERVER['SERVER_SOFTWARE'];
$web_server['sapi'] = php_sapi_name();

/* PHP */
$php = array();
$php['version'] = phpversion();
$php['modules'] = get_loaded_extensions();
$php['user'] = get_current_user() . " (uid=" . getmyuid() . " gid=" . getmygid() .")";
$php['memory_limit'] = (int)ini_get("memory_limit");
$php['tmp_path'] = sys_get_temp_dir();

$php['upload_max'] = (int)ini_get("upload_max_filesize");
$php['post_max'] = (int)ini_get("post_max_size");
if((int)$php['upload_max'] > (int)$php['post_max'])
{
	$php['upload_max'] = $php['post_max'];
}

/* Заголовок и путь */
title("Сведения о системе");
path(["Сведения о системе"]);
?>