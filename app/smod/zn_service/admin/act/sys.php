<?php
/* Операционная система */
$os = array();
$os['uname'] = php_uname();
$os['file_size'] = Reg::file()->size(Reg::path_app()) + Reg::file()->size(Reg::path_www());
$os['file_size_mb'] = round($os['file_size'] / 1048576);
$os['server_ip'] = $_SERVER['SERVER_ADDR'];
$os['server_name'] = $_SERVER['SERVER_NAME'];
$os['server_software'] = $_SERVER['SERVER_SOFTWARE'];
$os['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

/* PostgreSQL */
$pgsql = array();

$pgsql['host'] = Reg::db_host();
$pgsql['user'] = Reg::db_user();
$pgsql['db_name'] = Reg::db_name();
$pgsql['schema_core'] = Reg::db_schema_core();
$pgsql['schema_public'] = Reg::db_schema_public();

$pgsql['version'] = Reg::db_core()->query_one("SHOW SERVER_VERSION");
$pgsql['size'] = Reg::db_core()->query_one("SELECT pg_database_size('" . Reg::db_name() . "')");
$pgsql['size_mb'] = round($pgsql['size'] / 1048576);
$pgsql['server_encoding'] = Reg::db_core()->query_one("SHOW SERVER_ENCODING");
$pgsql['client_encoding'] = Reg::db_core()->query_one("SHOW CLIENT_ENCODING");

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

?>