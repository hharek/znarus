<?php
/**
 * Инициализация
 */

/* Основные классы */
require DIR_APP . "/sys/err.php";
require DIR_APP . "/sys/type.php";
require DIR_APP . "/sys/pgsql.php";
require DIR_APP . "/sys/tm.php";
require DIR_APP . "/sys/cache.php";

/*------------------- Объект для работы с файлами -----------------------*/
if (FILE_MANAGER == "sys")
{
	require DIR_APP . "/sys/file.php";
	G::file(new _File(DIR_PUBLIC));
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
	/* Используемые типы кэша */
	$_cache_type = [];														
	foreach (["db_core", "db", "route", "page", "ajax"] as $v)
	{
		if (constant("CACHE_" . strtoupper($v) . "_ENABLE") === true)
		{
			$_cache_type[] = constant("CACHE_" . strtoupper($v) . "_TYPE");
		}
	}
	$_cache_type = array_unique($_cache_type);

	/* Создаём объекты memcache если присутствуют */
	$_memcache_obj = [];													
	foreach (["memcache", "memcachedb", "kt"] as $v)
	{
		if (in_array($v, $_cache_type))
		{
			if (class_exists("Memcache"))
			{
				$_memcache_obj[$v] = new Memcache();
			}
			elseif (class_exists("Memcached"))
			{
				$_memcache_obj[$v] = new Memcached();
			}
			else
			{
				throw new Exception("Не установлен модуль php memcache или memcached.");
			}

			switch ($v)
			{
				case "memcache":
				{
					if (!empty(CACHE_MEMCACHE_SOCKET))
					{
						$_memcache_obj[$v]->addserver("unix://" . CACHE_MEMCACHE_SOCKET, 0);
					}
					elseif (!empty (CACHE_MEMCACHE_HOST) and !empty (CACHE_MEMCACHE_PORT))
					{
						$_memcache_obj[$v]->addserver(CACHE_MEMCACHE_HOST, CACHE_MEMCACHE_PORT);
					}
				}
				break;
				
				case "memcachedb":
				{
					if (!empty(CACHE_MEMCACHEDB_SOCKET))
					{
						$_memcache_obj[$v]->addserver("unix://" . CACHE_MEMCACHEDB_SOCKET, 0);
					}
					elseif (!empty (CACHE_MEMCACHEDB_HOST) and !empty (CACHE_MEMCACHEDB_PORT))
					{
						$_memcache_obj[$v]->addserver(CACHE_MEMCACHEDB_HOST, CACHE_MEMCACHEDB_PORT);
					}
				}
				break;
				
				case "kt":
				{
					$_memcache_obj[$v]->addserver(CACHE_KT_HOST, CACHE_KT_PORT);
				}
				break;
			}
		}
	}
	
	/* Создать объекты */
	foreach (["db_core", "db", "route", "page", "ajax"] as $_name)
	{
		if (constant("CACHE_" . strtoupper($_name) . "_ENABLE") === true)
		{
			switch (constant("CACHE_" . strtoupper($_name) . "_TYPE"))
			{
				case "memcache":
				{
					$_cache_type = "memcache";
					$_cache_param = $_memcache_obj['memcache'];
				}
				break;
			
				case "memcachedb":
				{
					$_cache_type = "memcache";
					$_cache_param = $_memcache_obj['memcachedb'];
				}
				break;
			
				case "kt":
				{
					$_cache_type = "memcache";
					$_cache_param = $_memcache_obj['kt'];
				}
				break;
			
				case "file": 
				{
					$_cache_type = "file";
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

				case "dba":
				{
					$_cache_type = "dba";
					$_cache_param = [DIR_VAR . "/cache_" . $_name . ".dba", CACHE_DBA_HANDLER];
				}
				break;				
			}

			G::{"cache_" . $_name}(new _Cache
			(
				$_name, 
				SALT, 
				DIR_VAR . "/cache_key_" . $_name . ".log", 
				$_cache_type, 
				$_cache_param
			));
		}
		else
		{
			G::{"cache_" . $_name}(new _Cache
			(
				$_name, 
				SALT, 
				DIR_VAR . "/cache_key_" . $_name . ".log", 
				"off"
			));
		}
	}
}
else
{
	G::cache_db_core(new _Cache("db_core", SALT, "off"));
	G::cache_db(new _Cache("db", SALT, "off"));
	G::cache_route(new _Cache("route", SALT, "off"));
	G::cache_page(new _Cache("page", SALT, "off"));
	G::cache_ajax(new _Cache("ajax", SALT, "off"));
}

_Cache_Front::$route = G::cache_route();
_Cache_Front::$page = G::cache_page();
_Cache_Front::$ajax = G::cache_ajax();

Cache::$db_core = G::cache_db_core();
Cache::$db = G::cache_db();
Cache::$route = G::cache_route();
Cache::$page = G::cache_page();
Cache::$ajax = G::cache_ajax();

/*------------------------------ Другое -----------------------------*/
/* Версионость */
if (VERSION_TYPE === "file")
{
	G::version(new _Version(SALT, "file", VERSION_FILE_DIR, VERSION_COMPRESS));
}
elseif (VERSION_TYPE === "dba")
{
	G::version(new _Version(SALT, "dba", [VERSION_DBA_FILE, VERSION_DBA_HANDLER], VERSION_COMPRESS));
}

/* Черновик */
if (DRAFT_TYPE === "file")
{
	G::draft(new _Draft(SALT, "file", DRAFT_FILE_DIR, DRAFT_COMPRESS));
}
elseif (DRAFT_TYPE === "dba")
{
	G::draft(new _Draft(SALT, "dba", [DRAFT_DBA_FILE, DRAFT_DBA_HANDLER], DRAFT_COMPRESS));
}
?>