<?php
/**
 * Инициализация
 */

/* Основные классы */
require DIR_APP . "/sys/err.php";
require DIR_APP . "/sys/chf.php";
require DIR_APP . "/sys/pgsql.php";
require DIR_APP . "/sys/cache.php";

/*------------------- Объект для работы с файлами -----------------------*/
if (FILE_MANAGER == "sys")
{
	require DIR_APP . "/sys/file.php";
	G::file(new _File(DIR_WWW));
	G::file_app(new _File(DIR_APP));
}
elseif (FILE_MANAGER == "ftp")
{
	require DIR_APP . "/sys/ftp.php";
	G::file(new _FTP(FILE_FTP_HOST, FILE_FTP_USER, FILE_FTP_PASS, FILE_FTP_PATH_WWW, FILE_FTP_PORT, FILE_FTP_SSL));
	G::file_app(clone G::file());
	G::file_app()->set_path(FILE_FTP_PATH_APP);
}

/*---------------------- Объекты для работы с базой ----------------------*/
G::db(new _Pgsql(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_SCHEMA_PUBLIC, DB_PORT, DB_PERSISTENT, DB_SSL));

if (DB_LOG === true)
{
	G::db()->log_enable(DB_LOG_FILE);
}

G::db_core(clone G::db());
G::db_core()->schema(DB_SCHEMA_CORE);

/* Автозагрузчики */
require DIR_APP . "/core/_autoloader.php";
require DIR_APP . "/lib/_autoloader.php";
require DIR_APP . "/mod/_autoloader.php";

/*------------------------------ Кэширование -----------------------------*/
if(CACHE_ENABLE === true)
{
	/* Подготавливаем объект memcache */
	if 
	(
		CACHE_DB_CORE_TYPE === "memcache" or CACHE_DB_CORE_TYPE === "mixed" or
		CACHE_DB_TYPE === "memcache" or CACHE_DB_TYPE === "mixed" or
		CACHE_ROUTE_TYPE === "memcache" or CACHE_ROUTE_TYPE === "mixed" or 
		CACHE_PAGE_TYPE === "memcache" or CACHE_PAGE_TYPE === "mixed"
	)
	{
		$_memcache = new Memcache();
		if (!empty(CACHE_MEMCACHE_SOCKET))
		{
			$_memcache->connect("unix://" . CACHE_MEMCACHE_SOCKET, 0);
		}
		elseif (!empty (CACHE_MEMCACHE_HOST) and !empty (CACHE_MEMCACHE_PORT))
		{
			$_memcache->connect(CACHE_MEMCACHE_HOST, CACHE_MEMCACHE_PORT);
		}
	}
	
	/* Создать объекты */
	$_cache_all = ["db_core", "db", "route", "page"];
	foreach ($_cache_all as $_name)
	{
		if (constant("CACHE_" . strtoupper($_name) . "_ENABLE") === true)
		{
			switch (constant("CACHE_" . strtoupper($_name) . "_TYPE"))
			{
				case "file": 
				{
					$_cache_param = CACHE_FILE_DIR . "/" . $_name;
					if (!is_dir($_cache_param))
					{
						if (!is_dir(CACHE_FILE_DIR))
						{
							mkdir(CACHE_FILE_DIR);
						}
						mkdir($_cache_param);
					}
				}
				break;

				case "memcache":
				{
					$_cache_param = $_memcache;
				}
				break;

				case "dba":
				{
					$_cache_param = dba_open(DIR_VAR . "/cache_" . $_name . ".dba", "c-", CACHE_DBA_HANDLER);
				}
				break;

				case "mixed":
				{
					$_cache_param = 
					[
						$_memcache,
						dba_open(DIR_VAR . "/cache_" . $_name . ".dba", "c-", CACHE_DBA_HANDLER)
					];
				}
				break;
			}

			G::{"cache_" . $_name}(new _Cache($_name, SALT, constant("CACHE_" . strtoupper($_name) . "_TYPE"), $_cache_param));
		}
		else
		{
			G::{"cache_" . $_name}(new _Cache($_name, SALT, "off"));
		}
	}
}
else
{
	G::cache_db_core(new _Cache("db_core", SALT, "off"));
	G::cache_db(new _Cache("db", SALT, "off"));
	G::cache_route(new _Cache("route", SALT, "off"));
	G::cache_page(new _Cache("page", SALT, "off"));
}

_Cache_Front::$route = G::cache_route();
_Cache_Front::$page = G::cache_page();

/*------------------------------ Другое -----------------------------*/
/* Версионость */
if (VERSION_TYPE === "file")
{
	G::version(new _Version(SALT, "file", VERSION_FILE_DIR));
}
elseif (VERSION_TYPE === "dba")
{
	G::version(new _Version(SALT, "dba", dba_open(VERSION_DBA, "c-", VERSION_DBA_HANDLER)));
}

/* Черновик */
if (DRAFT_TYPE === "file")
{
	G::draft(new _Draft(SALT, "file", DRAFT_FILE_DIR));
}
elseif (DRAFT_TYPE === "dba")
{
	G::draft(new _Draft(SALT, "dba", dba_open(DRAFT_DBA, "c-", DRAFT_DBA_HANDLER)));
}
?>